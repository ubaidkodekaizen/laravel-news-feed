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
    public function index()
    {
        // eager load relations we need
        $postsPaginator = \App\Models\Feed\Post::with([
            'user',
            'media',
            'comments.user',
            'reactions',
            'originalPost.user',
            'originalPost.media',
        ])->latest()->paginate(10);

        // transform to the shape used in your Blade partials
        $posts = $postsPaginator->getCollection()->map(function ($p) {
            // Use trait method to format user data
            $userData = $this->formatUserData($p->user);

            return [
                'id' => $p->id,
                'content' => $p->content,
                'created_at' => $p->created_at,
                'likes_count' => $p->reactions_count ?? $p->reactions()->count(),
                'comments_count' => $p->comments_count ?? $p->comments()->count(),
                'shares_count' => $p->shares_count ?? $p->shares()->count(),
                'original_post_id' => $p->original_post_id,
                'user' => [
                    'id' => $userData['id'],
                    'name' => trim($userData['first_name'] . ' ' . $userData['last_name']) ?: 'Unknown User',
                    'position' => $p->user->user_position ?? $p->user->position ?? $p->user->job_title ?? '',
                    'avatar' => $userData['photo'] ?? null,
                    'initials' => $userData['user_initials'],
                    'has_photo' => $userData['user_has_photo'],
                ],
                'media' => $p->media->map(function ($m) {
                    return [
                        'media_type' => $m->media_type,
                        'media_url' => $m->media_url,
                        'mime_type' => $m->mime_type,
                        'file_name' => $m->file_name,
                    ];
                })->toArray(),
                'original_post' => $p->original_post_id && $p->originalPost ? [
                    'id' => $p->originalPost->id,
                    'slug' => $p->originalPost->slug,
                    'content' => $p->originalPost->content,
                    'created_at' => $p->originalPost->created_at,
                    'user' => (function () use ($p) {
                        $originalUserData = $this->formatUserData($p->originalPost->user);
                        return [
                            'id' => $originalUserData['id'],
                            'name' => trim($originalUserData['first_name'] . ' ' . $originalUserData['last_name']) ?: 'Unknown',
                            'position' => $p->originalPost->user->user_position ?? $p->originalPost->user->position ?? '',
                            'avatar' => $originalUserData['photo'] ?? null,
                            'initials' => $originalUserData['user_initials'],
                            'has_photo' => $originalUserData['user_has_photo'],
                        ];
                    })(),
                    'media' => $p->originalPost->media->map(function ($m) {
                        return [
                            'media_type' => $m->media_type,
                            'media_url' => $m->media_url,
                        ];
                    })->toArray(),
                ] : null,
                'comments' => $p->comments->take(2)->map(function ($c) {
                    // Use trait method to format comment user data
                    $commentUserData = $this->formatUserData($c->user);

                    return [
                        'id' => $c->id,
                        'content' => $c->content,
                        'created_at' => $c->created_at,
                        'user' => [
                            'id' => $commentUserData['id'],
                            'name' => trim($commentUserData['first_name'] . ' ' . $commentUserData['last_name']) ?: 'Unknown',
                            'avatar' => $commentUserData['photo'] ?? null,
                            'initials' => $commentUserData['user_initials'],
                            'has_photo' => $commentUserData['user_has_photo'],
                        ],
                    ];
                })->toArray(),
                'reactions' => $p->reactions->take(3)->map(function ($r) {
                    return [
                        'type' => $r->reaction_type,
                        'user_id' => $r->user_id,
                    ];
                })->toArray(),
            ];
        })->toArray();

        // Get profile stats
        $userId = Auth::id();

        // Profile views (you can implement tracking later)
        $profileViews = 0;

        // Post impressions (sum of all post reactions + comments)
        $postImpressions = \App\Models\Feed\Post::where('user_id', $userId)
            ->where('status', 'active')
            ->whereNull('deleted_at')
            ->sum('reactions_count');

        $postImpressions += \App\Models\Feed\Post::where('user_id', $userId)
            ->where('status', 'active')
            ->whereNull('deleted_at')
            ->sum('comments_count');

        // Get recent products (latest 5)
        $recentProducts = \App\Models\Business\Product::with('user')
            ->whereHas('user', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->whereNull('deleted_at')
            ->latest()
            ->limit(3)
            ->get()
            ->map(function ($product) {
                return (object) [
                    'id' => $product->id,
                    'name' => $product->title,
                    'description' => $product->short_description,
                    'price' => $product->discounted_price ?? $product->original_price,
                    'image_url' => getImageUrl($product->product_image) ?? asset('assets/images/servicePlaceholderImg.png'),
                ];
            });

        // Get recent services (latest 5)
        $recentServices = \App\Models\Business\Service::with('user')
            ->whereHas('user', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->whereNull('deleted_at')
            ->latest()
            ->limit(3)
            ->get()
            ->map(function ($service) {
                return (object) [
                    'id' => $service->id,
                    'name' => $service->title,
                    'description' => $service->short_description,
                    'price' => $service->discounted_price ?? $service->original_price,
                    'image_url' => getImageUrl($service->service_image) ?? asset('assets/images/servicePlaceholderImg.png'),
                ];
            });

        // Get trending industries (top 5 by user count)
        $recentIndustries = \App\Models\Reference\Industry::withCount(['users' => function ($query) {
            $query->whereNull('deleted_at');
        }])
            ->having('users_count', '>', 0)
            ->orderBy('users_count', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($industry) {
                return (object) [
                    'id' => $industry->id,
                    'name' => $industry->name,
                    'description' => 'Connect with professionals in ' . $industry->name,
                    'members_count' => $industry->users_count,
                    'logo_url' => asset('assets/images/servicePlaceholderImg.png'),
                ];
            });

        // Get suggested connections (users not connected to current user)
        // Check if connections table exists, otherwise just get random users
        $suggestedConnections = collect();

        try {
            $hasConnectionsTable = Schema::hasTable('connections');

            if ($hasConnectionsTable) {
                $suggestedConnections = \App\Models\User::where('id', '!=', Auth::id())
                    ->where('status', 'active')
                    ->whereNull('deleted_at')
                    ->whereNotIn('id', function ($query) {
                        $query->select('connected_user_id')
                            ->from('connections')
                            ->where('user_id', Auth::id())
                            ->where('status', 'accepted');
                    })
                    ->whereNotIn('id', function ($query) {
                        $query->select('user_id')
                            ->from('connections')
                            ->where('connected_user_id', Auth::id())
                            ->where('status', 'accepted');
                    })
                    ->inRandomOrder()
                    ->limit(3)
                    ->get();
            } else {
                // If connections table doesn't exist, just get random active users
                $suggestedConnections = \App\Models\User::where('id', '!=', Auth::id())
                    ->where('status', 'active')
                    ->whereNull('deleted_at')
                    ->inRandomOrder()
                    ->limit(3)
                    ->get();
            }
        } catch (\Exception $e) {
            Log::warning('Error fetching suggested connections: ' . $e->getMessage());
            // Return empty collection on error
            $suggestedConnections = collect();
        }

        // pass both the transformed posts array and the original paginator (for links)
        return view('pages.news-feed', [
            'posts' => $posts,
            'pagination' => $postsPaginator,
            'profileViews' => $profileViews,
            'postImpressions' => $postImpressions,
            'recentProducts' => $recentProducts,
            'recentServices' => $recentServices,
            'recentIndustries' => $recentIndustries,
            'suggestedConnections' => $suggestedConnections,
        ]);
    }


    /**
     * Get posts for the feed (paginated).
     */
    public function getFeed(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $userId = Auth::id();

        $posts = Post::with([
            'user:id,first_name,last_name,slug,photo,user_position',
            'user.company:id,user_id,company_name,company_logo',
            'media',
            'reactions' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            },
            'comments' => function ($query) {
                $query->where('status', 'active')
                    ->with(['user:id,first_name,last_name,slug,photo', 'replies.user:id,first_name,last_name,slug,photo'])
                    ->orderBy('created_at', 'asc')
                    ->limit(3);
            },
            'originalPost.user:id,first_name,last_name,slug,photo',
        ])
            ->where('status', 'active')
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $posts
        ]);
    }

    /**
     * Get a single post with all details.
     */
    public function getPost($slug)
    {
        $userId = Auth::id();

        $post = Post::with([
            'user:id,first_name,last_name,slug,photo,user_position',
            'user.company:id,user_id,company_name,company_logo',
            'media',
            'reactions.user:id,first_name,last_name,slug,photo',
            'comments' => function ($query) {
                $query->where('status', 'active')
                    ->whereNull('parent_id')
                    ->with([
                        'user:id,first_name,last_name,slug,photo',
                        'replies' => function ($query) {
                            $query->where('status', 'active')
                                ->with('user:id,first_name,last_name,slug,photo')
                                ->orderBy('created_at', 'asc');
                        },
                        'reactions.user:id,first_name,last_name,slug,photo'
                    ])
                    ->orderBy('created_at', 'asc');
            },
            'originalPost.user:id,first_name,last_name,slug,photo',
            'originalPost.media'
        ])
            ->where('slug', $slug)
            ->where('status', 'active')
            ->whereNull('deleted_at')
            ->firstOrFail();

        // Check if current user has reacted
        $userReaction = $post->reactions()->where('user_id', $userId)->first();

        return response()->json([
            'success' => true,
            'data' => $post,
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
            'media.*' => 'file|mimes:jpeg,jpg,png,gif,mp4,mov,avi|max:10240', // 10MB max
            'comments_enabled' => 'nullable|boolean|in:0,1,true,false', // Accept both formats
        ]);

        try {
            DB::beginTransaction();

            $post = new Post();
            $post->user_id = Auth::id();
            $post->content = $request->content;
            $post->comments_enabled = $request->get('comments_enabled', true);
            $post->status = 'active';

            // Generate slug from content (title) with date and time in d-m-Y format
            $dateStr = now()->format('d-m-Y');
            $timeStr = now()->format('His'); // Hours, minutes, seconds in 24-hour format (e.g., 143052)
            $slugBase = 'post';

            if ($request->content) {
                // Extract first 50 characters from content as title
                $contentText = strip_tags($request->content);
                $title = Str::limit($contentText, 50, '');
                $slugBase = Str::slug($title);

                if (empty($slugBase)) {
                    $slugBase = 'post';
                }
            }

            // Append date and time to slug for uniqueness
            $slug = $slugBase . '-' . $dateStr . '-' . $timeStr;

            // If still exists (very unlikely with time), append microseconds
            if (Post::where('slug', $slug)->exists()) {
                $microseconds = now()->format('u');
                $slug = $slugBase . '-' . $dateStr . '-' . $timeStr . '-' . $microseconds;
            }

            $post->slug = $slug;
            $post->save();

            // Handle media uploads
            if ($request->hasFile('media')) {
                $s3Service = app(\App\Services\S3Service::class);
                $order = 0;
                foreach ($request->file('media') as $file) {
                    $uploadResult = $s3Service->uploadMedia($file, 'posts');
                    $mediaType = $uploadResult['type'];
                    $mediaPath = $uploadResult['path'];
                    $mediaUrl = $uploadResult['url'];

                    $postMedia = new PostMedia();
                    $postMedia->post_id = $post->id;
                    $postMedia->media_type = $mediaType;
                    $postMedia->media_path = $mediaPath;
                    $postMedia->media_url = $mediaUrl;
                    $postMedia->file_name = $file->getClientOriginalName();
                    $postMedia->file_size = $file->getSize();
                    $postMedia->mime_type = $file->getMimeType();
                    $postMedia->order = $order++;
                    $postMedia->save();
                }
            }

            DB::commit();

            $post->load([
                'user:id,first_name,last_name,slug,photo,user_position',
                'user.company:id,user_id,company_name,company_logo',
                'media'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Post created successfully!',
                'data' => $post
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
     * Update a post.
     */
    public function updatePost(Request $request, $id)
    {
        $request->validate([
            'content' => 'nullable|string|max:10000',
            'comments_enabled' => 'nullable|boolean',
        ]);

        $post = Post::where('user_id', Auth::id())
            ->where('id', $id)
            ->where('status', 'active')
            ->firstOrFail();

        $post->content = $request->content ?? $post->content;
        $post->comments_enabled = $request->has('comments_enabled')
            ? $request->comments_enabled
            : $post->comments_enabled;
        $post->save();

        $post->load([
            'user:id,first_name,last_name,slug,photo,user_position',
            'user.company:id,user_id,company_name,company_logo',
            'media'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Post updated successfully!',
            'data' => $post
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
        $post->delete(); // Soft delete

        return response()->json([
            'success' => true,
            'message' => 'Post deleted successfully!'
        ]);
    }

    /**
     * Add or update a reaction to a post or comment.
     */
    public function addReaction(Request $request)
    {
        $request->validate([
            'reactionable_type' => 'required|string|in:App\Models\Feed\Post,App\Models\Feed\PostComment',
            'reactionable_id' => 'required|integer',
            'reaction_type' => 'required|string|in:like,love,haha,wow,sad,angry',
        ]);

        $userId = Auth::id();
        $reactionableType = $request->reactionable_type;
        $reactionableId = $request->reactionable_id;

        // Check if reaction already exists
        $existingReaction = Reaction::where('reactionable_type', $reactionableType)
            ->where('reactionable_id', $reactionableId)
            ->where('user_id', $userId)
            ->first();

        if ($existingReaction) {
            // Update existing reaction
            if ($existingReaction->reaction_type === $request->reaction_type) {
                // Same reaction type, remove it
                $existingReaction->delete();
                return response()->json([
                    'success' => true,
                    'message' => 'Reaction removed',
                    'reaction' => null
                ]);
            } else {
                // Different reaction type, update it
                $existingReaction->reaction_type = $request->reaction_type;
                $existingReaction->save();
                return response()->json([
                    'success' => true,
                    'message' => 'Reaction updated',
                    'reaction' => $existingReaction
                ]);
            }
        } else {
            // Create new reaction
            $reaction = new Reaction();
            $reaction->reactionable_type = $reactionableType;
            $reaction->reactionable_id = $reactionableId;
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
     * Remove a reaction from a post or comment.
     */
    public function removeReaction(Request $request)
    {
        $request->validate([
            'reactionable_type' => 'required|string|in:App\Models\Feed\Post,App\Models\Feed\PostComment',
            'reactionable_id' => 'required|integer',
        ]);

        $userId = Auth::id();

        $reaction = Reaction::where('reactionable_type', $request->reactionable_type)
            ->where('reactionable_id', $request->reactionable_id)
            ->where('user_id', $userId)
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

        $comment = new PostComment();
        $comment->post_id = $postId;
        $comment->user_id = Auth::id();
        $comment->parent_id = $request->parent_id;
        $comment->content = $request->content;
        $comment->status = 'active';
        $comment->save();

        $comment->load([
            'user:id,first_name,last_name,slug,photo',
            'replies.user:id,first_name,last_name,slug,photo'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully!',
            'data' => $comment
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

        $comment->load([
            'user:id,first_name,last_name,slug,photo',
            'replies.user:id,first_name,last_name,slug,photo'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Comment updated successfully!',
            'data' => $comment
        ]);
    }

    /**
     * Delete a comment (soft delete).
     */
    public function deleteComment($commentId)
    {
        $comment = PostComment::where('user_id', Auth::id())
            ->where('id', $commentId)
            ->firstOrFail();

        $comment->status = 'deleted';
        $comment->save();
        $comment->delete(); // Soft delete

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully!'
        ]);
    }

    /**
     * Get comments for a post.
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
                'replies' => function ($query) {
                    $query->where('status', 'active')
                        ->with('user:id,first_name,last_name,slug,photo')
                        ->orderBy('created_at', 'asc');
                },
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
                // Create a new post that references the original
                $sharedPost = new Post();
                $sharedPost->user_id = Auth::id();
                $sharedPost->original_post_id = $postId;
                $sharedPost->content = $request->shared_content;
                $sharedPost->comments_enabled = true;
                $sharedPost->status = 'active';

                // Generate slug for shared post with date and time in d-m-Y format
                $dateStr = now()->format('d-m-Y');
                $timeStr = now()->format('His'); // Hours, minutes, seconds in 24-hour format
                $slugBase = 'shared-post';

                if ($request->shared_content) {
                    // Extract first 50 characters from shared content as title
                    $contentText = strip_tags($request->shared_content);
                    $title = Str::limit($contentText, 50, '');
                    $slugBase = Str::slug($title);

                    if (empty($slugBase)) {
                        $slugBase = 'shared-post';
                    }
                }

                // Append date and time to slug for uniqueness
                $slug = $slugBase . '-' . $dateStr . '-' . $timeStr;

                // If still exists (very unlikely with time), append microseconds
                if (Post::where('slug', $slug)->exists()) {
                    $microseconds = now()->format('u');
                    $slug = $slugBase . '-' . $dateStr . '-' . $timeStr . '-' . $microseconds;
                }

                $sharedPost->slug = $slug;
                $sharedPost->save();
            }

            // Create share record
            $share = new PostShare();
            $share->post_id = $postId;
            $share->user_id = Auth::id();
            $share->shared_post_id = $sharedPost ? $sharedPost->id : null;
            $share->shared_content = $request->shared_content;
            $share->share_type = $shareType;
            $share->save();

            DB::commit();

            $share->load([
                'user:id,first_name,last_name,slug,photo',
                'post.user:id,first_name,last_name,slug,photo'
            ]);

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
            'user.company:id,user_id,company_name,company_logo',
            'media',
            'reactions' => function ($query) {
                $query->where('user_id', Auth::id());
            },
            'comments' => function ($query) {
                $query->where('status', 'active')
                    ->with(['user:id,first_name,last_name,slug,photo'])
                    ->orderBy('created_at', 'asc')
                    ->limit(3);
            },
        ])
            ->where('user_id', $targetUserId)
            ->where('status', 'active')
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $posts
        ]);
    }


    /**
     * Get reaction count for a post.
     */
    public function getReactionCount($postId)
    {
        $post = Post::where('id', $postId)
            ->where('status', 'active')
            ->whereNull('deleted_at')
            ->firstOrFail();

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
        $post = Post::where('id', $postId)
            ->where('status', 'active')
            ->whereNull('deleted_at')
            ->firstOrFail();

        $count = $post->comments()->where('status', 'active')->count();

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }
}
