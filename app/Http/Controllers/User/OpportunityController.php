<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Opportunities\Opportunity;
use App\Models\Opportunities\OpportunityProposal;
use App\Models\Opportunities\SavedOpportunity;
use App\Models\Reference\Industry;
use App\Services\S3Service;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OpportunityController extends Controller
{
    protected $notificationService;
    protected $s3Service;

    public function __construct(NotificationService $notificationService, S3Service $s3Service)
    {
        $this->notificationService = $notificationService;
        $this->s3Service = $s3Service;
    }

    /**
     * Display opportunities listing page
     */
    public function index(Request $request)
    {
        return $this->getOpportunities($request);
    }

    /**
     * Get opportunities (AJAX/API)
     */
    public function getOpportunities(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'per_page' => 'nullable|integer|min:5|max:50',
            'page' => 'nullable|integer|min:1',
            'category' => 'nullable|string|in:looking_for_partner,need_consultant,project_work,fulltime_contract',
            'industry_id' => 'nullable|integer|exists:industries,id',
            'work_type' => 'nullable|string|in:remote,on_site,hybrid',
            'status' => 'nullable|string|in:open,in_review,shortlisted,awarded,completed,closed',
            'search' => 'nullable|string|max:255',
            'sort' => 'nullable|string|in:latest,oldest,deadline,proposals',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $perPage = $request->get('per_page', 15);
        $userId = Auth::id();

        $query = Opportunity::with([
            'user:id,first_name,last_name,slug,photo,user_position',
            'user.company:id,user_id,company_name,company_logo',
            'industry:id,name',
        ])
        ->withCount(['proposals', 'savedByUsers']);

        // Filter by category
        if ($request->has('category')) {
            $query->byCategory($request->category);
        }

        // Filter by industry
        if ($request->has('industry_id')) {
            $query->byIndustry($request->industry_id);
        }

        // Filter by work type
        if ($request->has('work_type')) {
            $query->byWorkType($request->work_type);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->byStatus($request->status);
        } else {
            // Default: only show open/active opportunities
            $query->open();
        }

        // Search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereJsonContains('tags', $search);
            });
        }

        // Sort
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'deadline':
                $query->orderBy('deadline', 'asc');
                break;
            case 'proposals':
                $query->orderBy('proposals_count', 'desc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $opportunities = $query->paginate($perPage);

        // Check if user has saved each opportunity
        if ($userId) {
            $savedOpportunityIds = SavedOpportunity::where('user_id', $userId)
                ->pluck('opportunity_id')
                ->toArray();

            $opportunities->getCollection()->transform(function ($opportunity) use ($savedOpportunityIds) {
                $opportunity->is_saved = in_array($opportunity->id, $savedOpportunityIds);
                return $opportunity;
            });
        }

        return response()->json([
            'status' => true,
            'message' => 'Opportunities fetched successfully.',
            'data' => $opportunities,
        ]);
    }

    /**
     * Get industries for opportunity creation
     */
    public function create()
    {
        $industries = Industry::orderBy('name')->get(['id', 'name']);
        
        return response()->json([
            'status' => true,
            'message' => 'Industries fetched successfully.',
            'data' => [
                'industries' => $industries,
                'categories' => [
                    'looking_for_partner' => 'Looking for Partner',
                    'need_consultant' => 'Need Consultant',
                    'project_work' => 'Project Work',
                    'fulltime_contract' => 'Full-time Contract',
                ],
            ],
        ]);
    }

    /**
     * Store a new opportunity
     */
    public function store(Request $request)
    {
        // Check if user has role_id = 4 (regular user)
        if (Auth::user()->role_id !== 4) {
            return response()->json([
                'status' => false,
                'message' => 'Only regular users can post opportunities.',
            ], 403);
        }

        // Check rate limit: 1 opportunity per 24 hours
        $recentOpportunity = Opportunity::where('user_id', Auth::id())
            ->where('created_at', '>=', now()->subDay())
            ->first();

        if ($recentOpportunity) {
            return response()->json([
                'status' => false,
                'message' => 'You can only post one opportunity per 24 hours.',
            ], 429);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:20',
            'category' => 'required|string|in:looking_for_partner,need_consultant,project_work,fulltime_contract',
            'industry_id' => 'nullable|integer|exists:industries,id',
            'budget' => 'nullable|numeric|min:0',
            'timeline' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:255',
            'work_type' => 'nullable|string|in:remote,on_site,hybrid',
            'deadline' => 'nullable|date|after:today',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'contact_preference' => 'nullable|string|in:email,phone,both',
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'file|mimes:pdf,doc,docx|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Handle file uploads
            $attachmentUrls = [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $this->s3Service->uploadFile($file, 'opportunities/attachments');
                    $attachmentUrls[] = $path;
                }
            }

            $opportunity = Opportunity::create([
                'user_id' => Auth::id(),
                'title' => $request->title,
                'description' => $request->description,
                'category' => $request->category,
                'industry_id' => $request->industry_id,
                'budget' => $request->budget,
                'timeline' => $request->timeline,
                'deadline' => $request->deadline,
                'location' => $request->location,
                'work_type' => $request->work_type,
                'tags' => $request->tags,
                'contact_email' => $request->contact_email ?? Auth::user()->email,
                'contact_phone' => $request->contact_phone,
                'contact_preference' => $request->contact_preference ?? 'email',
                'attachment_urls' => $attachmentUrls,
                'status' => 'open',
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Opportunity created successfully.',
                'data' => $opportunity->load(['user', 'industry']),
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create opportunity', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to create opportunity.',
            ], 500);
        }
    }

    /**
     * Show opportunity details
     */
    public function show($slug)
    {
        $opportunity = Opportunity::where('slug', $slug)
            ->with([
                'user:id,first_name,last_name,slug,photo,user_position,email',
                'user.company:id,user_id,company_name,company_logo',
                'industry:id,name',
                'proposals' => function ($query) {
                    $query->with('user:id,first_name,last_name,slug,photo')
                        ->latest()
                        ->limit(10);
                },
            ])
            ->withCount(['proposals', 'savedByUsers'])
            ->firstOrFail();

        // Increment views count
        $opportunity->incrementViewsCount();

        // Check if user has saved this opportunity
        $isSaved = false;
        $hasProposed = false;
        if (Auth::check()) {
            $isSaved = SavedOpportunity::where('user_id', Auth::id())
                ->where('opportunity_id', $opportunity->id)
                ->exists();

            $hasProposed = OpportunityProposal::where('user_id', Auth::id())
                ->where('opportunity_id', $opportunity->id)
                ->exists();
        }

        $opportunity->is_saved = $isSaved;
        $opportunity->has_proposed = $hasProposed;

        return response()->json([
            'status' => true,
            'message' => 'Opportunity fetched successfully.',
            'data' => $opportunity,
        ]);
    }

    /**
     * Get opportunity for editing
     */
    public function edit($id)
    {
        $opportunity = Opportunity::where('user_id', Auth::id())
            ->with(['industry:id,name'])
            ->findOrFail($id);

        // Only allow editing if status is 'open' and not expired
        if ($opportunity->status !== 'open' || $opportunity->isExpired()) {
            return response()->json([
                'status' => false,
                'message' => 'This opportunity cannot be edited.',
            ], 403);
        }

        $industries = Industry::orderBy('name')->get(['id', 'name']);
        
        return response()->json([
            'status' => true,
            'message' => 'Opportunity fetched for editing.',
            'data' => [
                'opportunity' => $opportunity,
                'industries' => $industries,
                'categories' => [
                    'looking_for_partner' => 'Looking for Partner',
                    'need_consultant' => 'Need Consultant',
                    'project_work' => 'Project Work',
                    'fulltime_contract' => 'Full-time Contract',
                ],
            ],
        ]);
    }

    /**
     * Update opportunity
     */
    public function update(Request $request, $id)
    {
        $opportunity = Opportunity::where('user_id', Auth::id())
            ->findOrFail($id);

        // Only allow editing if status is 'open' and not expired
        if ($opportunity->status !== 'open' || $opportunity->isExpired()) {
            return response()->json([
                'status' => false,
                'message' => 'This opportunity cannot be edited.',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:20',
            'category' => 'required|string|in:looking_for_partner,need_consultant,project_work,fulltime_contract',
            'industry_id' => 'nullable|integer|exists:industries,id',
            'budget' => 'nullable|numeric|min:0',
            'timeline' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:255',
            'work_type' => 'nullable|string|in:remote,on_site,hybrid',
            'deadline' => 'nullable|date|after:today',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'contact_preference' => 'nullable|string|in:email,phone,both',
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'file|mimes:pdf,doc,docx|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Handle file uploads
            $attachmentUrls = $opportunity->attachment_urls ?? [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $this->s3Service->uploadFile($file, 'opportunities/attachments');
                    $attachmentUrls[] = $path;
                }
            }

            $opportunity->update([
                'title' => $request->title,
                'description' => $request->description,
                'category' => $request->category,
                'industry_id' => $request->industry_id,
                'budget' => $request->budget,
                'timeline' => $request->timeline,
                'deadline' => $request->deadline,
                'location' => $request->location,
                'work_type' => $request->work_type,
                'tags' => $request->tags,
                'contact_email' => $request->contact_email,
                'contact_phone' => $request->contact_phone,
                'contact_preference' => $request->contact_preference,
                'attachment_urls' => $attachmentUrls,
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Opportunity updated successfully.',
                'data' => $opportunity->load(['user', 'industry']),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update opportunity', [
                'opportunity_id' => $id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to update opportunity.',
            ], 500);
        }
    }

    /**
     * Delete opportunity
     */
    public function destroy($id)
    {
        $opportunity = Opportunity::where('user_id', Auth::id())
            ->findOrFail($id);

        // Only allow deletion if no proposals have been accepted
        $hasAcceptedProposal = $opportunity->acceptedProposal()->exists();
        if ($hasAcceptedProposal) {
            return response()->json([
                'status' => false,
                'message' => 'Cannot delete opportunity with accepted proposals.',
            ], 403);
        }

        try {
            $opportunity->delete();

            return response()->json([
                'status' => true,
                'message' => 'Opportunity deleted successfully.',
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to delete opportunity', [
                'opportunity_id' => $id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to delete opportunity.',
            ], 500);
        }
    }

    /**
     * Get user's posted opportunities
     */
    public function myOpportunities(Request $request)
    {
        $perPage = $request->get('per_page', 15);

        $opportunities = Opportunity::where('user_id', Auth::id())
            ->with(['industry:id,name'])
            ->withCount(['proposals', 'savedByUsers'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'status' => true,
            'message' => 'Your opportunities fetched successfully.',
            'data' => $opportunities,
        ]);
    }

    /**
     * Save/unsave opportunity
     */
    public function toggleSave($id)
    {
        $opportunity = Opportunity::findOrFail($id);
        $userId = Auth::id();

        $saved = SavedOpportunity::where('user_id', $userId)
            ->where('opportunity_id', $id)
            ->first();

        if ($saved) {
            $saved->delete();
            $opportunity->decrementSavesCount();
            $message = 'Opportunity removed from saved.';
        } else {
            SavedOpportunity::create([
                'user_id' => $userId,
                'opportunity_id' => $id,
            ]);
            $opportunity->incrementSavesCount();
            $message = 'Opportunity saved successfully.';
        }

        return response()->json([
            'status' => true,
            'message' => $message,
            'is_saved' => !$saved,
        ]);
    }

    /**
     * Get saved opportunities
     */
    public function savedOpportunities(Request $request)
    {
        $perPage = $request->get('per_page', 15);

        $savedOpportunityIds = SavedOpportunity::where('user_id', Auth::id())
            ->pluck('opportunity_id');

        $opportunities = Opportunity::whereIn('id', $savedOpportunityIds)
            ->with([
                'user:id,first_name,last_name,slug,photo',
                'industry:id,name',
            ])
            ->withCount(['proposals', 'savedByUsers'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $opportunities->getCollection()->transform(function ($opportunity) {
            $opportunity->is_saved = true;
            return $opportunity;
        });

        return response()->json([
            'status' => true,
            'message' => 'Saved opportunities fetched successfully.',
            'data' => $opportunities,
        ]);
    }
}
