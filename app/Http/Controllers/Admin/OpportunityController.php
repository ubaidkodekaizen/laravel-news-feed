<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Opportunities\Opportunity;
use App\Models\Opportunities\OpportunityProposal;
use App\Models\Reference\Industry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OpportunityController extends Controller
{
    /**
     * Display opportunities listing page
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 20);
        $query = Opportunity::with([
            'user:id,first_name,last_name,slug,email',
            'user.company:id,user_id,company_name',
            'industry:id,name',
        ])
        ->withCount(['proposals', 'savedByUsers']);

        // Filters
        if ($request->has('status') && $request->status !== '') {
            $query->byStatus($request->status);
        }

        if ($request->has('category') && $request->category !== '') {
            $query->byCategory($request->category);
        }

        if ($request->has('industry_id') && $request->industry_id !== '') {
            $query->byIndustry($request->industry_id);
        }

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Sort
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'proposals':
                $query->orderBy('proposals_count', 'desc');
                break;
            case 'views':
                $query->orderBy('views_count', 'desc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $opportunities = $query->paginate($perPage);
        $industries = Industry::orderBy('name')->get(['id', 'name']);

        return response()->json([
            'status' => true,
            'message' => 'Opportunities fetched successfully.',
            'data' => [
                'opportunities' => $opportunities,
                'industries' => $industries,
            ],
        ]);
    }

    /**
     * Show opportunity details
     */
    public function show($id)
    {
        $opportunity = Opportunity::with([
            'user:id,first_name,last_name,slug,photo,email,phone',
            'user.company:id,user_id,company_name,company_logo,company_website',
            'industry:id,name',
            'proposals' => function ($query) {
                $query->with('user:id,first_name,last_name,slug,photo,email')
                    ->latest();
            },
        ])
        ->withCount(['proposals', 'savedByUsers', 'ratings'])
        ->findOrFail($id);

        return response()->json([
            'status' => true,
            'message' => 'Opportunity fetched successfully.',
            'data' => $opportunity,
        ]);
    }

    /**
     * Update opportunity status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:open,in_review,shortlisted,awarded,completed,closed',
        ]);

        $opportunity = Opportunity::findOrFail($id);

        try {
            $opportunity->update([
                'status' => $request->status,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Opportunity status updated successfully.',
                'data' => $opportunity,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update opportunity status', [
                'opportunity_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to update opportunity status.',
            ], 500);
        }
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured($id)
    {
        $opportunity = Opportunity::findOrFail($id);

        try {
            $opportunity->update([
                'is_featured' => !$opportunity->is_featured,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Opportunity featured status updated successfully.',
                'data' => $opportunity,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to toggle featured status', [
                'opportunity_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to update featured status.',
            ], 500);
        }
    }

    /**
     * Delete opportunity
     */
    public function destroy($id)
    {
        $opportunity = Opportunity::findOrFail($id);

        try {
            // Check if opportunity has accepted proposals
            $hasAcceptedProposal = $opportunity->acceptedProposal()->exists();
            if ($hasAcceptedProposal) {
                return response()->json([
                    'status' => false,
                    'message' => 'Cannot delete opportunity with accepted proposals.',
                ], 403);
            }

            $opportunity->delete();

            return response()->json([
                'status' => true,
                'message' => 'Opportunity deleted successfully.',
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to delete opportunity', [
                'opportunity_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to delete opportunity.',
            ], 500);
        }
    }

    /**
     * Restore deleted opportunity
     */
    public function restore($id)
    {
        $opportunity = Opportunity::withTrashed()->findOrFail($id);

        try {
            $opportunity->restore();

            return response()->json([
                'status' => true,
                'message' => 'Opportunity restored successfully.',
                'data' => $opportunity,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to restore opportunity', [
                'opportunity_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to restore opportunity.',
            ], 500);
        }
    }

    /**
     * Get opportunity proposals
     */
    public function getProposals($id, Request $request)
    {
        $perPage = $request->get('per_page', 20);

        $opportunity = Opportunity::findOrFail($id);

        $query = OpportunityProposal::where('opportunity_id', $id)
            ->with([
                'user:id,first_name,last_name,slug,photo,email',
                'user.company:id,user_id,company_name,company_logo',
            ]);

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->byStatus($request->status);
        }

        // Sort
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'status':
                $query->orderBy('status', 'asc');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        $proposals = $query->paginate($perPage);

        return response()->json([
            'status' => true,
            'message' => 'Proposals fetched successfully.',
            'data' => $proposals,
        ]);
    }

    /**
     * Get statistics
     */
    public function getStatistics()
    {
        $stats = [
            'total' => Opportunity::count(),
            'open' => Opportunity::byStatus('open')->count(),
            'in_review' => Opportunity::byStatus('in_review')->count(),
            'awarded' => Opportunity::byStatus('awarded')->count(),
            'completed' => Opportunity::byStatus('completed')->count(),
            'closed' => Opportunity::byStatus('closed')->count(),
            'featured' => Opportunity::featured()->count(),
            'expired' => Opportunity::needsExpiration()->count(),
            'total_proposals' => OpportunityProposal::count(),
            'pending_proposals' => OpportunityProposal::pending()->count(),
            'accepted_proposals' => OpportunityProposal::accepted()->count(),
        ];

        return response()->json([
            'status' => true,
            'message' => 'Statistics fetched successfully.',
            'data' => $stats,
        ]);
    }
}
