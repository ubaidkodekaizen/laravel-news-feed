<?php

namespace App\Http\Controllers\User;

use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\Controller;
use App\Models\Feed\Post;
use App\Models\Feed\PostMedia;
use App\Models\Feed\PostComment;
use App\Models\Feed\Reaction;
use App\Models\Feed\PostShare;
use App\Services\S3Service;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Traits\FormatsUserData;
use App\Models\Reference\Industry as IndustryModel;
use App\Models\User;
use App\Models\ProfileView;
use App\Traits\HasUserPhotoData;
use Illuminate\Support\Arr;

class FeedController extends Controller
{
    use FormatsUserData;
    use HasUserPhotoData;

    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display the news feed page.
     */
    public function index(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return $this->getFeed($request);
        }

        $userId = Auth::id();
        // Get profile views count (safe if table doesn't exist)
        try {
            $profileViews = \Illuminate\Support\Facades\Schema::hasTable('profile_views') 
                ? (Auth::user()->profile_views_count ?? 0) 
                : 0;
        } catch (\Exception $e) {
            $profileViews = 0;
        }

        $authUser = Auth::user()->load('company');
        $authUserData = $this->formatUserData($authUser);

        $company = $authUser->company;
        if ($company) {
            $companyLogoUrl = getImageUrl($company->company_logo) ?? null;
            $authUserData['company'] = [
                'id' => $company->id,
                'name' => $company->company_name,
                'slug' => $company->company_slug,
                'email' => $company->company_email ?? null,
                'web_url' => $company->company_web_url ?? null,
                'position' => $company->company_position ?? null,
                'logo' => $companyLogoUrl,
                'has_logo' => (bool) $companyLogoUrl,
            ];
        } else {
            $authUserData['company'] = null;
        }

        $posts = Post::where('user_id', $userId)
            ->where('status', 'active')
            ->whereNull('deleted_at')
            ->withCount(['reactions', 'comments'])
            ->get();

        $postImpressions = $posts->sum(function ($post) {
            return ($post->reactions_count ?? 0) + ($post->comments_count ?? 0);
        });

        $recentProducts = \App\Models\Business\Product::select('id', 'title', 'short_description', 'original_price', 'discounted_price', 'product_image', 'user_id')
            ->with('user:id,first_name,last_name,photo')
            ->whereHas('user', fn($q) => $q->whereNull('deleted_at'))
            ->whereNull('deleted_at')
            ->latest()
            ->limit(2)
            ->get()
            ->map(fn($product) => (object) [
                'id' => $product->id,
                'name' => $product->title,
                'description' => $product->short_description,
                'price' => $product->discounted_price ?? $product->original_price,
                'image_url' => getImageUrl($product->product_image) ?? asset('assets/images/servicePlaceholderImg.png'),
            ]);

        $recentServices = \App\Models\Business\Service::select('id', 'title', 'short_description', 'original_price', 'discounted_price', 'service_image', 'user_id')
            ->with('user:id,first_name,last_name,photo')
            ->whereHas('user', fn($q) => $q->whereNull('deleted_at'))
            ->whereNull('deleted_at')
            ->latest()
            ->limit(3)
            ->get()
            ->map(fn($service) => (object) [
                'id' => $service->id,
                'name' => $service->title,
                'description' => $service->short_description,
                'price' => $service->discounted_price ?? $service->original_price,
                'image_url' => getImageUrl($service->service_image) ?? asset('assets/images/servicePlaceholderImg.png'),
            ]);

        $industryConsolidation = [
            'Finance' => ['Finance', 'Financial Advisor', 'Financial Services', 'FinTech', 'Sharia Compliant Financial Services', 'Investment', 'Private Equity', 'Residential Mortgage', 'Payment Solution'],
            'Healthcare' => ['Healthcare', 'Medical Practices', 'Medical Billing', 'MedTech', 'Mental Health Therapist', 'Biopharma', 'Pharmaceuticals', 'FemTech'],
            'Technology' => ['Technology', 'Salesforce', 'Salesforce Consulting', 'Telecommunications', '3D Printing'],
            'Marketing' => ['Marketing', 'Marketing Services', 'Digital Marketing', 'Advertising Services'],
            'Construction' => ['Construction', 'Interior design'],
            'Educational Services' => ['Educational Services', 'Coaching'],
            'Legal' => ['Legal', 'Law Practice'],
            'Non-profit' => ['Non Profit', 'Non-profit', 'Non-profit Organizations'],
            'Business Consulting' => ['Business Consulting', 'Business Consulting and Services', 'Outsourcing and Offshoring Consulting'],
            'Staffing' => ['Staffing', 'Head Hunter', 'Resource Augmentation'],
            'Retail' => ['Retail', 'Restaurant', 'Halal Meat'],
            'Real Estate and Rental and Leasing' => ['Real Estate and Rental and Leasing'],
            'Administrative and Support and Waste Management and Remediation Services' => ['Administrative and Support and Waste Management and Remediation Services', 'Cleaning Services'],
            'Professional, Scientific, and Technical Services' => ['Professional, Scientific, and Technical Services', 'Creative Design', 'Writing and Editing', 'Ideation'],
            'Engineering' => ['Engineering', 'mechanical or industrial engineering'],
            'Logistics' => ['Logistics'],
            'Accounting' => ['Accounting'],
            'Printing' => ['Printing'],
            'InsurTech' => ['InsurTech'],
        ];

        $allIndustryVariations = collect($industryConsolidation)->flatten()->unique()->values();

        $recentIndustryExperts = User::where('status', 'complete')
            ->whereHas('company', function ($q) use ($allIndustryVariations) {
                $q->where('status', 'complete')
                    ->where(function ($sub) use ($allIndustryVariations) {
                        foreach ($allIndustryVariations as $variation) {
                            $sub->orWhere('company_industry', 'LIKE', "%{$variation}%");
                        }
                    });
            })
            ->with('company')
            ->latest()
            ->limit(2)
            ->get();

        if (method_exists($this, 'addPhotoDataToCollection')) {
            $recentIndustryExperts = $this->addPhotoDataToCollection($recentIndustryExperts);
        }

        $suggestedConnections = collect();
        try {
            if (Schema::hasTable('connections')) {
                $suggestedConnections = \App\Models\User::where('id', '!=', Auth::id())
                    ->where('status', 'active')
                    ->whereNull('deleted_at')
                    ->whereNotIn('id', fn($q) => $q->select('connected_user_id')
                        ->from('connections')
                        ->where('user_id', Auth::id())
                        ->where('status', 'accepted'))
                    ->whereNotIn('id', fn($q) => $q->select('user_id')
                        ->from('connections')
                        ->where('connected_user_id', Auth::id())
                        ->where('status', 'accepted'))
                    ->inRandomOrder()
                    ->limit(2)
                    ->get();
            } else {
                $suggestedConnections = \App\Models\User::where('id', '!=', Auth::id())
                    ->where('status', 'active')
                    ->whereNull('deleted_at')
                    ->inRandomOrder()
                    ->limit(2)
                    ->get();
            }
        } catch (\Exception $e) {
            Log::warning('Error fetching suggested connections: ' . $e->getMessage());
        }

        $ads = \App\Models\Ad::where('status', 'active')
            ->orderBy('featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(fn($ad) => (object) [
                'id' => $ad->id,
                'media' => getImageUrl($ad->media) ?? asset('assets/images/ad-placeholder.png'),
                'url' => $ad->url,
                'featured' => $ad->featured,
            ]);

        return view('pages.news-feed', [
            'posts' => [],
            'profileViews' => $profileViews,
            'postImpressions' => $postImpressions,
            'recentProducts' => $recentProducts,
            'recentServices' => $recentServices,
            'recentIndustryExperts' => $recentIndustryExperts,
            'suggestedConnections' => $suggestedConnections,
            'authUserData' => $authUserData,
            'ads' => $ads,
        ]);
    }

    public function addPhotoDataToCollection($items)
    {
        return $items->map(function ($item) {
            if (is_array($item) && array_key_exists('user', $item)) {
                $this->addPhotoData($item['user']);
                return $item;
            }
            return $this->addPhotoData($item);
        });
    }

    /**
     * Display a single post detail page
     */
    public function showPostPage($slug)
    {
        $userId = Auth::id();

        $authUser = Auth::user();
        $authUserData = $this->formatUserData($authUser);

        $post = Post::with([
            'user:id,first_name,last_name,slug,photo,user_position',
            'media',
            'reactions.user:id,first_name,last_name,slug,photo',
            'comments' => fn($q) => $q->where('status', 'active')
                ->whereNull('parent_id')
                ->with([
                    'user:id,first_name,last_name,slug,photo',
                    'replies' => fn($q) => $q->where('status', 'active')
                        ->with('user:id,first_name,last_name,slug,photo')
                        ->orderBy('created_at', 'asc'),
                    'reactions' => fn($r) => $r->where('user_id', $userId)->where('reaction_type', 'appreciate')
                ])
                ->withCount(['reactions as user_has_reacted' => fn($r) => $r->where('user_id', $userId)->where('reaction_type', 'appreciate')])
                ->orderBy('created_at', 'asc'),
            'originalPost.user:id,first_name,last_name,slug,photo,user_position',
            'originalPost.media'
        ])
            ->where('slug', $slug)
            ->where('status', 'active')
            ->whereNull('deleted_at')
            ->first();

        if (!$post) {
            abort(404, 'Post not found');
        }

        if ($post->visibility === 'private' && $post->user_id !== $userId) {
            abort(403, 'You do not have permission to view this post');
        }

        $transformedPost = $this->transformPost($post, $userId);

        return view('pages.post-detail', [
            'post' => $transformedPost,
            'authUserData' => $authUserData
        ]);
    }

    /**
     * Get posts for the feed (paginated for infinite scroll).
     */
    public function getFeed(Request $request)
    {
        $request->validate([
            'per_page' => 'nullable|integer|min:5|max:50',
            'page' => 'nullable|integer|min:1',
            'sort' => 'nullable|string|in:latest,popular,oldest',
        ]);

        $perPage = $request->get('per_page', 10);
        $sort = $request->get('sort', 'latest');
        $userId = Auth::id();

        $query = Post::with([
            'user:id,first_name,last_name,slug,photo,user_position',
            'media',
            'reactions' => fn($q) => $q->where('user_id', $userId),
            'comments' => fn($q) => $q->where('status', 'active')
                ->whereNull('parent_id')
                ->with([
                    'user:id,first_name,last_name,slug,photo',
                    'reactions' => fn($r) => $r->where('user_id', $userId)->where('reaction_type', 'appreciate')
                ])
                ->withCount(['reactions as user_has_reacted' => fn($r) => $r->where('user_id', $userId)->where('reaction_type', 'appreciate')])
                ->orderBy('created_at', 'asc')
                ->limit(2),
            'originalPost.user:id,first_name,last_name,slug,photo,user_position',
            'originalPost.media',
        ])
            ->where('status', 'active')
            ->whereNull('deleted_at');

        switch ($sort) {
            case 'popular':
                $query->orderByRaw('(reactions_count + comments_count + shares_count) DESC');
                break;
            case 'oldest':
                $query->oldest();
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        $posts = $query->paginate($perPage);

        $transformedPosts = $posts->getCollection()->map(function ($post) use ($userId) {
            return $this->transformPost($post, $userId);
        });

        return response()->json([
            'success' => true,
            'data' => $transformedPosts,
            'current_page' => $posts->currentPage(),
            'last_page' => $posts->lastPage(),
            'per_page' => $posts->perPage(),
            'total' => $posts->total(),
            'has_more' => $posts->hasMorePages(),
        ]);
    }

    /**
     * Transform post data for consistent output.
     */
    private function transformPost($post, $userId = null)
    {
        if (!$post || !$post->user) {
            return null;
        }

        $userData = $this->formatUserData($post->user);

        $userReaction = null;
        if ($userId && $post->relationLoaded('reactions')) {
            $userReaction = $post->reactions->first();
        }

        // Get S3Service instance for converting thumbnail paths to URLs
        $s3Service = app(S3Service::class);

        $transformed = [
            'id' => $post->id,
            'slug' => $post->slug,
            'content' => $post->content,
            'visibility' => $post->visibility ?? 'public',
            'created_at' => $post->created_at,
            'updated_at' => $post->updated_at,
            'likes_count' => $post->reactions_count ?? 0,
            'comments_count' => $post->comments_count ?? 0,
            'shares_count' => $post->shares_count ?? 0,
            'original_post_id' => $post->original_post_id,
            'comments_enabled' => (bool) $post->comments_enabled,
            'user' => [
                'id' => $userData['id'],
                'name' => trim($userData['first_name'] . ' ' . $userData['last_name']) ?: 'Unknown User',
                'first_name' => $userData['first_name'],
                'last_name' => $userData['last_name'],
                'position' => $post->user->user_position ?? $post->user->position ?? '',
                'avatar' => $userData['photo'],
                'initials' => $userData['user_initials'],
                'has_photo' => $userData['user_has_photo'],
                'slug' => $post->user->slug ?? '',
            ],
            'media' => $post->media->map(function($m) use ($s3Service) {
                // For videos, use thumbnail_path converted to URL if exists, otherwise use media_url
                // For images, use thumbnail_path converted to URL if exists, otherwise use media_url
                $thumbnailUrl = $m->media_url; // Default fallback
                if ($m->thumbnail_path) {
                    // Convert thumbnail_path to full URL
                    $thumbnailUrl = $s3Service->getUrl($m->thumbnail_path);
                } elseif ($m->media_type === 'image') {
                    // For images, use media_url as thumbnail
                    $thumbnailUrl = $m->media_url;
                }
                // For videos without thumbnail_path, keep media_url as fallback
                
                return [
                    'id' => $m->id,
                    'media_type' => $m->media_type,
                    'media_url' => $m->media_url,
                    'thumbnail_url' => $thumbnailUrl,
                    'mime_type' => $m->mime_type,
                    'file_name' => $m->file_name,
                    'duration' => $m->duration,
                ];
            })->toArray(),
            'user_reaction' => $userReaction ? [
                'type' => $userReaction->reaction_type,
                'created_at' => $userReaction->created_at,
            ] : null,
            'reactions' => $post->relationLoaded('reactions') ? $post->reactions->map(fn($r) => [
                'type' => $r->reaction_type,
                'user_id' => $r->user_id,
            ])->toArray() : [],
            'comments' => $post->comments->map(function ($comment) use ($userId) {
                $commentUserData = $this->formatUserData($comment->user);
                return [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'created_at' => $comment->created_at,
                    'user_has_reacted' => $comment->user_has_reacted > 0,
                    'user_id' => $comment->user_id,
                    'user' => [
                        'id' => $commentUserData['id'],
                        'name' => trim($commentUserData['first_name'] . ' ' . $commentUserData['last_name']),
                        'avatar' => $commentUserData['photo'],
                        'initials' => $commentUserData['user_initials'],
                        'has_photo' => $commentUserData['user_has_photo'],
                        'slug' => $comment->user->slug ?? '',
                    ],
                    'replies' => []
                ];
            })->toArray(),
        ];

        // Add original post data if this is a shared post
        if ($post->original_post_id && $post->relationLoaded('originalPost') && $post->originalPost) {
            $originalUserData = $this->formatUserData($post->originalPost->user);
            $transformed['original_post'] = [
                'id' => $post->originalPost->id,
                'slug' => $post->originalPost->slug,
                'content' => $post->originalPost->content,
                'created_at' => $post->originalPost->created_at,
                'user' => [
                    'id' => $originalUserData['id'],
                    'name' => trim($originalUserData['first_name'] . ' ' . $originalUserData['last_name']),
                    'position' => $post->originalPost->user->user_position ?? '',
                    'avatar' => $originalUserData['photo'],
                    'initials' => $originalUserData['user_initials'],
                    'has_photo' => $originalUserData['user_has_photo'],
                ],
                'media' => $post->originalPost->media->map(function($m) use ($s3Service) {
                    // For videos, use thumbnail_path converted to URL if exists, otherwise use media_url
                    // For images, use thumbnail_path converted to URL if exists, otherwise use media_url
                    $thumbnailUrl = $m->media_url; // Default fallback
                    if ($m->thumbnail_path) {
                        // Convert thumbnail_path to full URL
                        $thumbnailUrl = $s3Service->getUrl($m->thumbnail_path);
                    } elseif ($m->media_type === 'image') {
                        // For images, use media_url as thumbnail
                        $thumbnailUrl = $m->media_url;
                    }
                    // For videos without thumbnail_path, keep media_url as fallback
                    
                    return [
                        'id' => $m->id,
                        'media_type' => $m->media_type,
                        'media_url' => $m->media_url,
                        'thumbnail_url' => $thumbnailUrl,
                        'mime_type' => $m->mime_type,
                    ];
                })->toArray(),
            ];
        }

        return $transformed;
    }

    /**
     * Get a single post with all details.
     */
    public function getPost($id)
    {
        try {
            $userId = Auth::id();

            $post = Post::with([
                'user:id,first_name,last_name,slug,photo,user_position',
                'media',
                'reactions' => fn($q) => $q->where('user_id', $userId),
                'comments' => fn($q) => $q->where('status', 'active')
                    ->whereNull('parent_id')
                    ->with([
                        'user:id,first_name,last_name,slug,photo',
                        'replies' => fn($q) => $q->where('status', 'active')
                            ->with('user:id,first_name,last_name,slug,photo')
                            ->orderBy('created_at', 'asc'),
                        'reactions' => fn($r) => $r->where('user_id', $userId)->where('reaction_type', 'appreciate')
                    ])
                    ->withCount(['reactions as user_has_reacted' => fn($r) => $r->where('user_id', $userId)->where('reaction_type', 'appreciate')])
                    ->orderBy('created_at', 'asc'),
                'originalPost.user:id,first_name,last_name,slug,photo',
                'originalPost.media'
            ])
                ->where('id', $id)
                ->where('status', 'active')
                ->whereNull('deleted_at')
                ->firstOrFail();

            // Check if user can view this post
            if ($post->visibility === 'private' && $post->user_id !== $userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to view this post'
                ], 403);
            }

            $userReaction = $post->reactions()->where('user_id', $userId)->first();

            return response()->json([
                'success' => true,
                'data' => $this->transformPost($post, $userId),
                'user_reaction' => $userReaction
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching post: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Post not found'
            ], 404);
        }
    }

    /**
     * Create a new post.
     */
    public function createPost(Request $request)
    {
        $request->validate([
            'content' => 'nullable|string|max:10000',
            'media' => 'nullable|array|max:10',
            'media.*' => 'file|mimes:jpeg,jpg,png,gif,webp,mp4,mov,avi,mkv,webm|max:10240',
            'comments_enabled' => 'nullable|boolean',
            'visibility' => 'nullable|string|in:public,private,connections',
        ]);

        if (!$request->content && !$request->hasFile('media')) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide content or media for your post.'
            ], 422);
        }

        // Validate total media size (10MB)
        if ($request->hasFile('media')) {
            $totalSize = collect($request->file('media'))->sum('size');
            if ($totalSize > 10 * 1024 * 1024) {
                return response()->json([
                    'success' => false,
                    'message' => 'Total media size exceeds 10MB limit.'
                ], 422);
            }
        }

        try {
            DB::beginTransaction();

            $post = new Post();
            $post->user_id = Auth::id();
            $post->content = $request->content;
            $post->comments_enabled = $request->get('comments_enabled', true);
            $post->visibility = $request->get('visibility', 'public');
            $post->status = 'active';
            $post->slug = $this->generateUniqueSlug($request->content);
            $post->save();

            if ($request->hasFile('media')) {
                $s3Service = app(S3Service::class);
                $order = 0;

                foreach ($request->file('media') as $file) {
                    $uploadResult = $s3Service->uploadMedia($file, 'posts');

                    $postMedia = new PostMedia();
                    $postMedia->post_id = $post->id;
                    $postMedia->media_type = $uploadResult['type'];
                    $postMedia->media_path = $uploadResult['path'];
                    $postMedia->media_url = $uploadResult['url'];
                    $postMedia->file_name = $uploadResult['file_name']; // Use S3Service result for consistency
                    $postMedia->file_size = $uploadResult['file_size']; // Use S3Service result for consistency
                    $postMedia->mime_type = $uploadResult['mime_type']; // Use S3Service result for consistency
                    
                    // Save thumbnail for videos (generated by FFmpeg if available)
                    if ($uploadResult['type'] === 'video' && isset($uploadResult['thumbnail_path'])) {
                        $postMedia->thumbnail_path = $uploadResult['thumbnail_path'];
                    }
                    
                    // Save duration for videos
                    if ($uploadResult['type'] === 'video' && isset($uploadResult['duration'])) {
                        $postMedia->duration = $uploadResult['duration'];
                    }
                    
                    $postMedia->order = $order++;
                    $postMedia->save();
                }
            }

            DB::commit();

            $post->load(['user', 'media', 'reactions', 'comments']);

            return response()->json([
                'success' => true,
                'message' => 'Post created successfully!',
                'data' => $this->transformPost($post, Auth::id())
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating post: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to create post. Please try again.'
            ], 500);
        }
    }


    /**
     * Get post data for editing (separate from getPost to avoid confusion)
     */
    public function getPostData($id)
    {
        try {
            $userId = Auth::id();

            $post = Post::with(['media'])
                ->where('id', $id)
                ->where('user_id', $userId)
                ->where('status', 'active')
                ->whereNull('deleted_at')
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $post->id,
                    'content' => $post->content,
                    'visibility' => $post->visibility ?? 'public',
                    'comments_enabled' => (bool) $post->comments_enabled,
                    'media' => $post->media->map(fn($m) => [
                        'id' => $m->id,
                        'media_type' => $m->media_type,
                        'media_url' => $m->media_url,
                        'mime_type' => $m->mime_type,
                        'file_name' => $m->file_name,
                    ])->toArray(),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching post data for edit: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load post data'
            ], 404);
        }
    }



    /**
     * Generate unique slug for post.
     */
    private function generateUniqueSlug($content)
    {
        $dateStr = now()->format('d-m-Y');
        $timeStr = now()->format('His');
        $slugBase = 'post';

        if ($content) {
            $contentText = strip_tags($content);
            $title = Str::limit($contentText, 50, '');
            $slugBase = Str::slug($title);

            if (empty($slugBase)) {
                $slugBase = 'post';
            }
        }

        $slug = $slugBase . '-' . $dateStr . '-' . $timeStr;

        if (Post::where('slug', $slug)->exists()) {
            $slug = $slugBase . '-' . $dateStr . '-' . $timeStr . '-' . Str::random(6);
        }

        return $slug;
    }

    /**
     * Update a post.
     */
    public function updatePost(Request $request, $id)
    {
        $request->validate([
            'content' => 'nullable|string|max:10000',
            'comments_enabled' => 'nullable|boolean',
            'visibility' => 'nullable|string|in:public,private,connections',
            'media' => 'nullable|array|max:10',
            'media.*' => 'file|mimes:jpeg,jpg,png,gif,webp,mp4,mov,avi,mkv,webm|max:10240',
            'remove_media_ids' => 'nullable|array',
            'remove_media_ids.*' => 'integer|exists:post_media,id',
        ]);

        $post = Post::where('user_id', Auth::id())
            ->where('id', $id)
            ->where('status', 'active')
            ->firstOrFail();

        // Validate total media size if adding new media
        if ($request->hasFile('media')) {
            $existingMediaSize = $post->media()->whereNotIn('id', $request->get('remove_media_ids', []))->sum('file_size');
            $newMediaSize = collect($request->file('media'))->sum('size');
            $totalSize = $existingMediaSize + $newMediaSize;

            if ($totalSize > 10 * 1024 * 1024) {
                return response()->json([
                    'success' => false,
                    'message' => 'Total media size exceeds 10MB limit.'
                ], 422);
            }
        }

        try {
            DB::beginTransaction();

            $post->content = $request->content ?? $post->content;
            $post->comments_enabled = $request->has('comments_enabled')
                ? $request->comments_enabled
                : $post->comments_enabled;
            $post->visibility = $request->get('visibility', $post->visibility);
            $post->save();

            // Remove specified media
            if ($request->has('remove_media_ids')) {
                $mediaToRemove = $post->media()->whereIn('id', $request->remove_media_ids)->get();
                foreach ($mediaToRemove as $media) {
                    // Delete from S3
                    if ($media->media_path) {
                        try {
                            Storage::disk('s3')->delete($media->media_path);
                        } catch (\Exception $e) {
                            Log::warning('Failed to delete media from S3: ' . $e->getMessage());
                        }
                    }
                    $media->delete();
                }
            }

            // Add new media
            if ($request->hasFile('media')) {
                $s3Service = app(S3Service::class);
                $order = $post->media()->max('order') ?? -1;

                foreach ($request->file('media') as $file) {
                    $order++;
                    $uploadResult = $s3Service->uploadMedia($file, 'posts');

                    $postMedia = new PostMedia();
                    $postMedia->post_id = $post->id;
                    $postMedia->media_type = $uploadResult['type'];
                    $postMedia->media_path = $uploadResult['path'];
                    $postMedia->media_url = $uploadResult['url'];
                    $postMedia->file_name = $uploadResult['file_name']; // Use S3Service result for consistency
                    $postMedia->file_size = $uploadResult['file_size']; // Use S3Service result for consistency
                    $postMedia->mime_type = $uploadResult['mime_type']; // Use S3Service result for consistency
                    
                    // Save thumbnail for videos (generated by FFmpeg if available)
                    if ($uploadResult['type'] === 'video' && isset($uploadResult['thumbnail_path'])) {
                        $postMedia->thumbnail_path = $uploadResult['thumbnail_path'];
                    }
                    
                    // Save duration for videos
                    if ($uploadResult['type'] === 'video' && isset($uploadResult['duration'])) {
                        $postMedia->duration = $uploadResult['duration'];
                    }
                    
                    $postMedia->order = $order;
                    $postMedia->save();
                }
            }

            DB::commit();

            $post->load(['user', 'media', 'reactions', 'comments']);

            return response()->json([
                'success' => true,
                'message' => 'Post updated successfully!',
                'data' => $this->transformPost($post, Auth::id())
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating post: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update post. Please try again.'
            ], 500);
        }
    }

    /**
     * Delete a post (soft delete).
     */
    public function deletePost($id)
    {
        try {
            $post = Post::where('user_id', Auth::id())
                ->where('id', $id)
                ->firstOrFail();

            $post->status = 'deleted';
            $post->save();
            $post->delete();

            return response()->json([
                'success' => true,
                'message' => 'Post deleted successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting post: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete post. Please try again.'
            ], 500);
        }
    }

    /**
     * Add or update a reaction.
     */
    public function addReaction(Request $request)
    {
        $request->validate([
            'reactionable_type' => 'required|string|in:Post,PostComment,App\Models\Feed\Post,App\Models\Feed\PostComment',
            'reactionable_id' => 'required|integer',
            'reaction_type' => 'required|string|in:appreciate,cheers,support,insight,curious,smile', // Original reaction types
        ]);

        try {
            $userId = Auth::id();

            // Normalize reactionable_type to full namespace
            $reactionableType = $request->reactionable_type;
            if ($reactionableType === 'Post') {
                $reactionableType = 'App\Models\Feed\Post';
            } elseif ($reactionableType === 'PostComment') {
                $reactionableType = 'App\Models\Feed\PostComment';
            }

            $reactionableId = $request->reactionable_id;

            $existingReaction = Reaction::where('reactionable_type', $reactionableType)
                ->where('reactionable_id', $reactionableId)
                ->where('user_id', $userId)
                ->first();

            if ($existingReaction) {
                if ($existingReaction->reaction_type === $request->reaction_type) {
                    $existingReaction->delete();
                    return response()->json([
                        'success' => true,
                        'message' => 'Reaction removed',
                        'reaction' => null
                    ]);
                } else {
                    $existingReaction->reaction_type = $request->reaction_type;
                    $existingReaction->save();
                    return response()->json([
                        'success' => true,
                        'message' => 'Reaction updated',
                        'reaction' => $existingReaction
                    ]);
                }
            } else {
                $reaction = new Reaction();
                $reaction->reactionable_type = $reactionableType;
                $reaction->reactionable_id = $reactionableId;
                $reaction->user_id = $userId;
                $reaction->reaction_type = $request->reaction_type;
                $reaction->save();

                // Send notification if reaction is on a post (not comment)
                if ($reactionableType === 'App\Models\Feed\Post') {
                    try {
                        $post = Post::find($reactionableId);
                        if ($post && $post->user_id !== $userId) {
                            $reactor = Auth::user();
                            $this->notificationService->sendPostReactionNotification(
                                $post->user_id,
                                $reactor,
                                $post,
                                $request->reaction_type
                            );
                        }
                    } catch (\Exception $e) {
                        Log::error('Failed to send post reaction notification', [
                            'error' => $e->getMessage()
                        ]);
                        // Don't fail the request if notification fails
                    }
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Reaction added',
                    'reaction' => $reaction
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error adding reaction: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to add reaction. Please try again.'
            ], 500);
        }
    }

    /**
     * Get reactions for a post with user details.
     */
    public function getReactionsList(Request $request, $postId)
    {
        try {
            $post = Post::findOrFail($postId);

            $reactions = $post->reactions()
                ->with('user:id,first_name,last_name,photo,user_position')
                ->get()
                ->map(function ($reaction) {
                    $userData = $this->formatUserData($reaction->user);
                    return [
                        'id' => $reaction->id,
                        'type' => $reaction->reaction_type,
                        'created_at' => $reaction->created_at,
                        'user' => [
                            'id' => $userData['id'],
                            'name' => trim($userData['first_name'] . ' ' . $userData['last_name']),
                            'avatar' => $userData['photo'],
                            'initials' => $userData['user_initials'],
                            'has_photo' => $userData['user_has_photo'],
                            'position' => $reaction->user->user_position ?? '',
                        ]
                    ];
                });

            return response()->json([
                'success' => true,
                'count' => $reactions->count(),
                'reactions' => $reactions
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching reactions: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load reactions.'
            ], 500);
        }
    }

    /**
     * Get shares for a post with user details.
     */
    public function getSharesList(Request $request, $postId)
    {
        try {
            $post = Post::findOrFail($postId);

            $shares = $post->shares()
                ->with('user:id,first_name,last_name,photo,user_position')
                ->latest()
                ->get()
                ->map(function ($share) {
                    $userData = $this->formatUserData($share->user);
                    return [
                        'id' => $share->id,
                        'share_type' => $share->share_type,
                        'shared_content' => $share->shared_content,
                        'created_at' => $share->created_at,
                        'user' => [
                            'id' => $userData['id'],
                            'name' => trim($userData['first_name'] . ' ' . $userData['last_name']),
                            'avatar' => $userData['photo'],
                            'initials' => $userData['user_initials'],
                            'has_photo' => $userData['user_has_photo'],
                            'position' => $share->user->user_position ?? '',
                        ]
                    ];
                });

            return response()->json([
                'success' => true,
                'count' => $shares->count(),
                'shares' => $shares
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching shares: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load shares.'
            ], 500);
        }
    }

    /**
     * Remove a reaction.
     */
    public function removeReaction(Request $request)
    {
        $request->validate([
            'reactionable_type' => 'required|string|in:Post,PostComment,App\Models\Feed\Post,App\Models\Feed\PostComment',
            'reactionable_id' => 'required|integer',
        ]);

        try {
            $userId = Auth::id();

            // Normalize reactionable_type to full namespace
            $reactionableType = $request->reactionable_type;
            if ($reactionableType === 'Post') {
                $reactionableType = 'App\Models\Feed\Post';
            } elseif ($reactionableType === 'PostComment') {
                $reactionableType = 'App\Models\Feed\PostComment';
            }

            $reaction = Reaction::where('reactionable_type', $reactionableType)
                ->where('reactionable_id', $request->reactionable_id)
                ->where('user_id', $userId)
                ->firstOrFail();

            $reaction->delete();

            return response()->json([
                'success' => true,
                'message' => 'Reaction removed'
            ]);
        } catch (\Exception $e) {
            Log::error('Error removing reaction: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove reaction.'
            ], 500);
        }
    }

    /**
     * Add a comment to a post.
     */
    public function addComment(Request $request, $postId)
    {
        $request->validate([
            'content' => 'required|string|max:5000',
            'parent_id' => 'nullable|integer|exists:post_comments,id',
        ]);

        try {
            $post = Post::where('id', $postId)
                ->where('status', 'active')
                ->whereNull('deleted_at')
                ->firstOrFail();

            if (!$post->comments_enabled) {
                return response()->json([
                    'success' => false,
                    'message' => 'Comments are disabled for this post'
                ], 403);
            }

            if ($request->parent_id) {
                $parentComment = PostComment::where('id', $request->parent_id)
                    ->where('post_id', $postId)
                    ->where('status', 'active')
                    ->firstOrFail();
            }

            $comment = new PostComment();
            $comment->post_id = $postId;
            $comment->user_id = Auth::id();
            $comment->parent_id = $request->parent_id;
            $comment->content = $request->content;
            $comment->status = 'active';
            $comment->save();

            // Send notification if comment is on someone else's post (not a reply)
            if ($post->user_id !== Auth::id() && !$request->parent_id) {
                try {
                    $commenter = Auth::user();
                    $this->notificationService->sendPostCommentNotification(
                        $post->user_id,
                        $commenter,
                        $post,
                        $comment
                    );
                } catch (\Exception $e) {
                    Log::error('Failed to send post comment notification', [
                        'error' => $e->getMessage()
                    ]);
                    // Don't fail the request if notification fails
                }
            }

            // Send notification if this is a reply to a comment
            if ($request->parent_id && isset($parentComment) && $parentComment->user_id !== Auth::id()) {
                try {
                    $replier = Auth::user();
                    $this->notificationService->sendCommentReplyNotification(
                        $parentComment->user_id,
                        $replier,
                        $post,
                        $comment,
                        $parentComment
                    );
                } catch (\Exception $e) {
                    Log::error('Failed to send comment reply notification', [
                        'error' => $e->getMessage()
                    ]);
                    // Don't fail the request if notification fails
                }
            }

            $comment->load(['user:id,first_name,last_name,slug,photo']);

            $userData = $this->formatUserData($comment->user);

            return response()->json([
                'success' => true,
                'message' => 'Comment added successfully!',
                'data' => [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'created_at' => $comment->created_at,
                    'parent_id' => $comment->parent_id,
                    'user_has_reacted' => false,
                    'user_id' => $comment->user_id,
                    'user' => [
                        'id' => $userData['id'],
                        'name' => trim($userData['first_name'] . ' ' . $userData['last_name']),
                        'avatar' => $userData['photo'],
                        'initials' => $userData['user_initials'],
                        'has_photo' => $userData['user_has_photo'],
                        'slug' => $comment->user->slug ?? '',
                    ],
                    'replies' => [],
                ]
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error adding comment: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to add comment. Please try again.'
            ], 500);
        }
    }

    /**
     * Update a comment.
     */
    public function updateComment(Request $request, $commentId)
    {
        $request->validate([
            'content' => 'required|string|max:5000',
        ]);

        try {
            $comment = PostComment::where('user_id', Auth::id())
                ->where('id', $commentId)
                ->where('status', 'active')
                ->firstOrFail();

            $comment->content = $request->content;
            $comment->save();

            $comment->load('user:id,first_name,last_name,slug,photo');
            $userData = $this->formatUserData($comment->user);

            return response()->json([
                'success' => true,
                'message' => 'Comment updated successfully!',
                'data' => [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'updated_at' => $comment->updated_at,
                    'user' => [
                        'id' => $userData['id'],
                        'name' => trim($userData['first_name'] . ' ' . $userData['last_name']),
                        'avatar' => $userData['photo'],
                        'initials' => $userData['user_initials'],
                        'has_photo' => $userData['user_has_photo'],
                        'slug' => $comment->user->slug ?? '',
                    ],
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating comment: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update comment. Please try again.'
            ], 500);
        }
    }

    /**
     * Delete a comment.
     */
    public function deleteComment($commentId)
    {
        try {
            $comment = PostComment::where('user_id', Auth::id())
                ->where('id', $commentId)
                ->firstOrFail();

            $comment->status = 'deleted';
            $comment->save();
            $comment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Comment deleted successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting comment: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete comment. Please try again.'
            ], 500);
        }
    }

    /**
     * Get comments for a post with pagination.
     */
    public function getComments(Request $request, $postId)
    {
        try {
            $perPage = $request->get('per_page', 20);
            $userId = Auth::id();

            $post = Post::where('id', $postId)
                ->where('status', 'active')
                ->whereNull('deleted_at')
                ->firstOrFail();

            $comments = PostComment::where('post_id', $postId)
                ->where('status', 'active')
                ->whereNull('parent_id')
                ->with([
                    'user:id,first_name,last_name,slug,photo',
                    'replies' => fn($q) => $q->where('status', 'active')
                        ->with('user:id,first_name,last_name,slug,photo')
                        ->orderBy('created_at', 'asc'),
                    'reactions' => fn($r) => $r->where('user_id', $userId)->where('reaction_type', 'appreciate')
                ])
                ->withCount(['reactions as user_has_reacted' => fn($r) => $r->where('user_id', $userId)->where('reaction_type', 'appreciate')])
                ->orderBy('created_at', 'asc')
                ->paginate($perPage);

            $transformedComments = $comments->getCollection()->map(function ($comment) {
                $commentUserData = $this->formatUserData($comment->user);

                $repliesData = $comment->replies->map(function ($reply) {
                    $replyUserData = $this->formatUserData($reply->user);
                    return [
                        'id' => $reply->id,
                        'content' => $reply->content,
                        'created_at' => $reply->created_at,
                        'user_id' => $reply->user_id,
                        'user' => [
                            'id' => $replyUserData['id'],
                            'name' => trim($replyUserData['first_name'] . ' ' . $replyUserData['last_name']),
                            'avatar' => $replyUserData['photo'],
                            'initials' => $replyUserData['user_initials'],
                            'has_photo' => $replyUserData['user_has_photo'],
                            'slug' => $reply->user->slug ?? '',
                        ],
                    ];
                });

                return [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'created_at' => $comment->created_at,
                    'user_has_reacted' => $comment->user_has_reacted > 0,
                    'user_id' => $comment->user_id,
                    'user' => [
                        'id' => $commentUserData['id'],
                        'name' => trim($commentUserData['first_name'] . ' ' . $commentUserData['last_name']),
                        'avatar' => $commentUserData['photo'],
                        'initials' => $commentUserData['user_initials'],
                        'has_photo' => $commentUserData['user_has_photo'],
                        'slug' => $comment->user->slug ?? '',
                    ],
                    'replies' => $repliesData,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'data' => $transformedComments,
                    'current_page' => $comments->currentPage(),
                    'last_page' => $comments->lastPage(),
                    'per_page' => $comments->perPage(),
                    'total' => $comments->total(),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching comments: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load comments.'
            ], 500);
        }
    }

    /**
     * Share a post.
     */
    public function sharePost(Request $request, $postId)
    {
        $request->validate([
            'shared_content' => 'nullable|string|max:10000',
            'share_type' => 'nullable|string|in:share,repost',
        ]);

        try {
            $originalPost = Post::where('id', $postId)
                ->where('status', 'active')
                ->whereNull('deleted_at')
                ->firstOrFail();

            DB::beginTransaction();

            $shareType = $request->share_type ?? 'repost';

            // ALWAYS create a new post for both share and repost
            $sharedPost = new Post();
            $sharedPost->user_id = Auth::id();
            $sharedPost->original_post_id = $postId;
            $sharedPost->content = $request->shared_content ?? '';
            $sharedPost->comments_enabled = true;
            $sharedPost->visibility = 'public';
            $sharedPost->status = 'active';
            $sharedPost->slug = $this->generateUniqueSlug($request->shared_content ?? 'repost');
            $sharedPost->save();

            // Create share record
            $share = new PostShare();
            $share->post_id = $postId;
            $share->user_id = Auth::id();
            $share->shared_post_id = $sharedPost->id;
            $share->shared_content = $request->shared_content;
            $share->share_type = $shareType;
            $share->save();

            // Send notification if sharing someone else's post
            if ($originalPost->user_id !== Auth::id()) {
                try {
                    $sharer = Auth::user();
                    $this->notificationService->sendPostShareNotification(
                        $originalPost->user_id,
                        $sharer,
                        $originalPost
                    );
                } catch (\Exception $e) {
                    Log::error('Failed to send post share notification', [
                        'error' => $e->getMessage()
                    ]);
                    // Don't fail the request if notification fails
                }
            }

            DB::commit();

            // Load relationships for response
            $sharedPost->load(['user', 'media', 'originalPost.user', 'originalPost.media']);

            return response()->json([
                'success' => true,
                'message' => 'Post shared successfully!',
                'data' => $this->transformPost($sharedPost, Auth::id())
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error sharing post: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to share post. Please try again.'
            ], 500);
        }
    }

    /**
     * Get user's posts.
     */
    public function getUserPosts(Request $request, $userId = null)
    {
        try {
            $targetUserId = $userId ?? Auth::id();
            $perPage = $request->get('per_page', 15);

            $posts = Post::with([
                'user:id,first_name,last_name,slug,photo,user_position',
                'media',
                'reactions' => fn($q) => $q->where('user_id', Auth::id()),
                'comments' => fn($q) => $q->where('status', 'active')
                    ->with(['user:id,first_name,last_name,slug,photo'])
                    ->orderBy('created_at', 'asc')
                    ->limit(3),
            ])
                ->where('user_id', $targetUserId)
                ->where('status', 'active')
                ->whereNull('deleted_at')
                ->latest()
                ->paginate($perPage);

            $transformedPosts = $posts->getCollection()->map(
                fn($post) => $this->transformPost($post, Auth::id())
            );

            return response()->json([
                'success' => true,
                'data' => $transformedPosts,
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
                'has_more' => $posts->hasMorePages(),
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching user posts: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load posts.'
            ], 500);
        }
    }

    /**
     * Get reaction count for a post.
     */
    public function getReactionCount($postId)
    {
        try {
            $post = Post::findOrFail($postId);

            $reactions = $post->reactions()
                ->with('user:id,first_name,last_name,photo')
                ->get()
                ->map(function ($reaction) {
                    return [
                        'type' => $reaction->reaction_type,
                        'user_id' => $reaction->user_id,
                        'user_name' => $reaction->user->first_name . ' ' . $reaction->user->last_name,
                    ];
                });

            return response()->json([
                'success' => true,
                'count' => $reactions->count(),
                'reactions' => $reactions
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching reaction count: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load reaction count.'
            ], 500);
        }
    }

    /**
     * Get comment count for a post.
     */
    public function getCommentCount($postId)
    {
        try {
            $post = Post::findOrFail($postId);
            $count = $post->comments()->where('status', 'active')->count();

            return response()->json([
                'success' => true,
                'count' => $count
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching comment count: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load comment count.'
            ], 500);
        }
    }

    /**
     * Get profile views for the authenticated user.
     */
    public function getProfileViews(Request $request)
    {
        try {
            $userId = Auth::id();

            // Check if profile_views table exists
            if (!Schema::hasTable('profile_views')) {
                return response()->json([
                    'success' => true,
                    'count' => 0,
                    'views' => []
                ]);
            }

            $profileViews = ProfileView::where('viewed_user_id', $userId)
                ->whereNotNull('viewer_id')
                ->with('viewer:id,first_name,last_name,photo,user_position,slug')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($view) {
                    $userData = $this->formatUserData($view->viewer);
                    return [
                        'id' => $view->id,
                        'viewed_at' => $view->created_at,
                        'user' => [
                            'id' => $userData['id'],
                            'name' => trim($userData['first_name'] . ' ' . $userData['last_name']),
                            'avatar' => $userData['photo'],
                            'initials' => $userData['user_initials'],
                            'has_photo' => $userData['user_has_photo'],
                            'position' => $view->viewer->user_position ?? '',
                            'slug' => $view->viewer->slug ?? '',
                        ]
                    ];
                });

            return response()->json([
                'success' => true,
                'count' => $profileViews->count(),
                'views' => $profileViews
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching profile views: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load profile views.'
            ], 500);
        }
    }
}
