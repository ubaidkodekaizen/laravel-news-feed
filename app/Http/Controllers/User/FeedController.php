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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Traits\FormatsUserData;

class FeedController extends Controller
{
    use FormatsUserData;

    /**
     * Display the news feed page.
     */
    public function index(Request $request)
    {
        // For AJAX requests (infinite scroll), return JSON
        if ($request->ajax() || $request->wantsJson()) {
            return $this->getFeed($request);
        }

        // For initial page load, return view with minimal data
        // Posts will be loaded via AJAX for better performance

        // Get profile stats
        $userId = Auth::id();
        $profileViews = 0; // Implement tracking later

        $postImpressions = Post::where('user_id', $userId)
            ->where('status', 'active')
            ->whereNull('deleted_at')
            ->sum('reactions_count') +
            Post::where('user_id', $userId)
            ->where('status', 'active')
            ->whereNull('deleted_at')
            ->sum('comments_count');

        // Get sidebar data
        $recentProducts = \App\Models\Business\Product::with('user')
            ->whereHas('user', fn($q) => $q->whereNull('deleted_at'))
            ->whereNull('deleted_at')
            ->latest()
            ->limit(3)
            ->get()
            ->map(fn($product) => (object) [
                'id' => $product->id,
                'name' => $product->title,
                'description' => $product->short_description,
                'price' => $product->discounted_price ?? $product->original_price,
                'image_url' => getImageUrl($product->product_image) ?? asset('assets/images/servicePlaceholderImg.png'),
            ]);

        $recentServices = \App\Models\Business\Service::with('user')
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

        $recentIndustries = \App\Models\Reference\Industry::withCount([
            'users' => fn($q) => $q->whereNull('deleted_at')
        ])
            ->having('users_count', '>', 0)
            ->orderBy('users_count', 'desc')
            ->limit(3)
            ->get()
            ->map(fn($industry) => (object) [
                'id' => $industry->id,
                'name' => $industry->name,
                'description' => 'Connect with professionals in ' . $industry->name,
                'members_count' => $industry->users_count,
                'logo_url' => asset('assets/images/servicePlaceholderImg.png'),
            ]);

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
                    ->limit(3)
                    ->get();
            } else {
                $suggestedConnections = \App\Models\User::where('id', '!=', Auth::id())
                    ->where('status', 'active')
                    ->whereNull('deleted_at')
                    ->inRandomOrder()
                    ->limit(3)
                    ->get();
            }
        } catch (\Exception $e) {
            Log::warning('Error fetching suggested connections: ' . $e->getMessage());
        }

        return view('pages.news-feed', [
            'posts' => [], // Will be loaded via AJAX
            'profileViews' => $profileViews,
            'postImpressions' => $postImpressions,
            'recentProducts' => $recentProducts,
            'recentServices' => $recentServices,
            'recentIndustries' => $recentIndustries,
            'suggestedConnections' => $suggestedConnections,
        ]);
    }

    /**
     * Display a single post detail page
     */
    public function showPostPage($slug)
    {
        $userId = Auth::id();

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
                    'reactions.user:id,first_name,last_name,slug,photo'
                ])
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

        // Check visibility permissions
        if ($post->visibility === 'private' && $post->user_id !== $userId) {
            abort(403, 'You do not have permission to view this post');
        }

        // Transform post data
        $transformedPost = $this->transformPost($post, $userId);

        return view('pages.post-detail', [
            'post' => $transformedPost
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
                ->with(['user:id,first_name,last_name,slug,photo'])
                ->orderBy('created_at', 'asc')
                ->limit(2),
            'originalPost.user:id,first_name,last_name,slug,photo,user_position',
            'originalPost.media',
        ])
            ->where('status', 'active')
            ->whereNull('deleted_at');

        // Apply sorting
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

        // Transform posts data
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
        $userData = $this->formatUserData($post->user);

        $userReaction = null;
        if ($userId && $post->relationLoaded('reactions')) {
            $userReaction = $post->reactions->first();
        }

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
            'media' => $post->media->map(fn($m) => [
                'id' => $m->id,
                'media_type' => $m->media_type,
                'media_url' => $m->media_url,
                'thumbnail_url' => $m->thumbnail_path ?? $m->media_url,
                'mime_type' => $m->mime_type,
                'file_name' => $m->file_name,
                'duration' => $m->duration,
            ])->toArray(),
            'user_reaction' => $userReaction ? [
                'type' => $userReaction->reaction_type,
                'created_at' => $userReaction->created_at,
            ] : null,
            'comments' => $post->comments->take(2)->map(function ($c) {
                $commentUserData = $this->formatUserData($c->user);
                return [
                    'id' => $c->id,
                    'content' => $c->content,
                    'created_at' => $c->created_at,
                    'user' => [
                        'id' => $commentUserData['id'],
                        'name' => trim($commentUserData['first_name'] . ' ' . $commentUserData['last_name']),
                        'avatar' => $commentUserData['photo'],
                        'initials' => $commentUserData['user_initials'],
                        'has_photo' => $commentUserData['user_has_photo'],
                    ],
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
                'media' => $post->originalPost->media->map(fn($m) => [
                    'media_type' => $m->media_type,
                    'media_url' => $m->media_url,
                ])->toArray(),
            ];
        }

        return $transformed;
    }

    /**
     * Get a single post with all details.
     */
    public function getPost($slug)
    {
        $userId = Auth::id();

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
                    'reactions.user:id,first_name,last_name,slug,photo'
                ])
                ->orderBy('created_at', 'asc'),
            'originalPost.user:id,first_name,last_name,slug,photo',
            'originalPost.media'
        ])
            ->where('slug', $slug)
            ->where('status', 'active')
            ->whereNull('deleted_at')
            ->firstOrFail();

        $userReaction = $post->reactions()->where('user_id', $userId)->first();

        return response()->json([
            'success' => true,
            'data' => $this->transformPost($post, $userId),
            'user_reaction' => $userReaction
        ]);
    }

    /**
     * Create a new post.
     */
    public function createPost(Request $request)
    {
        $request->validate([
            'content' => 'nullable|string|max:10000',
            'media' => 'nullable|array|max:10',
            'media.*' => 'file|mimes:jpeg,jpg,png,gif,webp,mp4,mov,avi,mkv|max:4096', // 4MB max
            'comments_enabled' => 'nullable|boolean',
            'visibility' => 'nullable|string|in:public,private,connections',
        ]);

        // Validate that at least content or media is provided
        if (!$request->content && !$request->hasFile('media')) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide content or media for your post.'
            ], 422);
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

            // Handle media uploads
            if ($request->hasFile('media')) {
                $s3Service = app(S3Service::class);
                $order = 0;

                foreach ($request->file('media') as $file) {
                    // Validate file size (4MB = 4096KB)
                    if ($file->getSize() > 4096 * 1024) {
                        throw new \Exception('File ' . $file->getClientOriginalName() . ' exceeds 4MB limit.');
                    }

                    $uploadResult = $s3Service->uploadMedia($file, 'posts');

                    $postMedia = new PostMedia();
                    $postMedia->post_id = $post->id;
                    $postMedia->media_type = $uploadResult['type'];
                    $postMedia->media_path = $uploadResult['path'];
                    $postMedia->media_url = $uploadResult['url'];
                    $postMedia->file_name = $file->getClientOriginalName();
                    $postMedia->file_size = $file->getSize();
                    $postMedia->mime_type = $file->getMimeType();
                    $postMedia->order = $order++;

                    // For videos, you might want to generate a thumbnail
                    if ($uploadResult['type'] === 'video') {
                        // Implement video thumbnail generation if needed
                        $postMedia->duration = null; // Set video duration if you can extract it
                    }

                    $postMedia->save();
                }
            }

            DB::commit();

            $post->load(['user', 'media']);

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
                'message' => $e->getMessage()
            ], 500);
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
        ]);

        $post = Post::where('user_id', Auth::id())
            ->where('id', $id)
            ->where('status', 'active')
            ->firstOrFail();

        $post->content = $request->content ?? $post->content;
        $post->comments_enabled = $request->has('comments_enabled')
            ? $request->comments_enabled
            : $post->comments_enabled;
        $post->visibility = $request->get('visibility', $post->visibility);
        $post->save();

        $post->load(['user', 'media']);

        return response()->json([
            'success' => true,
            'message' => 'Post updated successfully!',
            'data' => $this->transformPost($post, Auth::id())
        ]);
    }

    /**
     * Delete a post (soft delete).
     */
    public function deletePost($id)
    {
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
    }

    /**
     * Add or update a reaction.
     */
    public function addReaction(Request $request)
    {
        $request->validate([
            'reactionable_type' => 'required|string|in:App\Models\Feed\Post,App\Models\Feed\PostComment',
            'reactionable_id' => 'required|integer',
            'reaction_type' => 'required|string|in:like,love,celebrate,insightful,funny,haha,wow,sad,angry',
        ]);

        $userId = Auth::id();

        $existingReaction = Reaction::where('reactionable_type', $request->reactionable_type)
            ->where('reactionable_id', $request->reactionable_id)
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
            $reaction->reactionable_type = $request->reactionable_type;
            $reaction->reactionable_id = $request->reactionable_id;
            $reaction->user_id = $userId;
            $reaction->reaction_type = $request->reaction_type;
            $reaction->save();

            return response()->json([
                'success' => true,
                'message' => 'Reaction added',
                'reaction' => $reaction
            ]);
        }
    }

    /**
     * Get reactions for a post with user details.
     */
    public function getReactionsList(Request $request, $postId)
    {
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
    }

    /**
     * Get shares for a post with user details.
     */
    public function getSharesList(Request $request, $postId)
    {
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
    }

    /**
     * Remove a reaction.
     */
    public function removeReaction(Request $request)
    {
        $request->validate([
            'reactionable_type' => 'required|string|in:App\Models\Feed\Post,App\Models\Feed\PostComment',
            'reactionable_id' => 'required|integer',
        ]);

        $reaction = Reaction::where('reactionable_type', $request->reactionable_type)
            ->where('reactionable_id', $request->reactionable_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $reaction->delete();

        return response()->json([
            'success' => true,
            'message' => 'Reaction removed'
        ]);
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

        // If parent_id provided, verify it belongs to this post
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
                'user' => [
                    'id' => $userData['id'],
                    'name' => trim($userData['first_name'] . ' ' . $userData['last_name']),
                    'avatar' => $userData['photo'],
                    'initials' => $userData['user_initials'],
                    'has_photo' => $userData['user_has_photo'],
                ],
                'replies' => [],
            ]
        ], 201);
    }

    /**
     * Update a comment.
     */
    public function updateComment(Request $request, $commentId)
    {
        $request->validate([
            'content' => 'required|string|max:5000',
        ]);

        $comment = PostComment::where('user_id', Auth::id())
            ->where('id', $commentId)
            ->where('status', 'active')
            ->firstOrFail();

        $comment->content = $request->content;
        $comment->save();

        return response()->json([
            'success' => true,
            'message' => 'Comment updated successfully!',
            'data' => $comment
        ]);
    }

    /**
     * Delete a comment.
     */
    public function deleteComment($commentId)
    {
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
    }

    /**
     * Get comments for a post with pagination.
     */
    public function getComments(Request $request, $postId)
    {
        $perPage = $request->get('per_page', 20);

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
                'reactions.user:id,first_name,last_name,slug,photo'
            ])
            ->orderBy('created_at', 'asc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $comments
        ]);
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

        $originalPost = Post::where('id', $postId)
            ->where('status', 'active')
            ->whereNull('deleted_at')
            ->firstOrFail();

        try {
            DB::beginTransaction();

            $shareType = $request->share_type ?? 'share';
            $sharedPost = null;

            if ($shareType === 'repost') {
                $sharedPost = new Post();
                $sharedPost->user_id = Auth::id();
                $sharedPost->original_post_id = $postId;
                $sharedPost->content = $request->shared_content;
                $sharedPost->comments_enabled = true;
                $sharedPost->visibility = 'public';
                $sharedPost->status = 'active';
                $sharedPost->slug = $this->generateUniqueSlug($request->shared_content);
                $sharedPost->save();
            }

            $share = new PostShare();
            $share->post_id = $postId;
            $share->user_id = Auth::id();
            $share->shared_post_id = $sharedPost ? $sharedPost->id : null;
            $share->shared_content = $request->shared_content;
            $share->share_type = $shareType;
            $share->save();

            DB::commit();

            $share->load(['user', 'post.user']);

            return response()->json([
                'success' => true,
                'message' => 'Post shared successfully!',
                'data' => $share
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
            fn($post) =>
            $this->transformPost($post, Auth::id())
        );

        return response()->json([
            'success' => true,
            'data' => $transformedPosts,
            'current_page' => $posts->currentPage(),
            'last_page' => $posts->lastPage(),
            'has_more' => $posts->hasMorePages(),
        ]);
    }

    /**
     * Get reaction count for a post.
     */
    public function getReactionCount($postId)
    {
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
    }

    /**
     * Get comment count for a post.
     */
    public function getCommentCount($postId)
    {
        $post = Post::findOrFail($postId);
        $count = $post->comments()->where('status', 'active')->count();

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }
}
