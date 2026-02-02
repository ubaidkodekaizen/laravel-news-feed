<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Feed\Post;
use App\Models\Feed\PostMedia;
use App\Models\Feed\PostComment;
use App\Models\Feed\Reaction;
use App\Models\Feed\PostShare;
use App\Services\S3Service;
use App\Services\NotificationService;
use App\Traits\FormatsUserData;
use App\Traits\HasUserPhotoData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FeedController extends Controller
{
    use FormatsUserData;
    use HasUserPhotoData;
    /**
     * Get posts for the feed (paginated).
     */
    public function getFeed(Request $request)
    {
        $request->validate([
            'per_page' => 'nullable|integer|min:5|max:50',
            'sort' => 'nullable|string|in:latest,popular,oldest',
        ]);

        $perPage = $request->get('per_page', 15);
        $sort = $request->get('sort', 'latest');
        $userId = Auth::id();

        $query = Post::withCount(['reactions', 'comments', 'shares'])
            ->with([
                'user:id,first_name,last_name,slug,photo,user_position',
                'user.company:id,user_id,company_name,company_logo',
                'media',
                'reactions' => function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                },
                // Remove comments from feed - should be fetched separately via GET /feed/posts/{postId}/comments
                // This reduces response size significantly (10 posts × 100 comments × 10 replies = huge payload)
                'originalPost.user:id,first_name,last_name,slug,photo,user_position',
                'originalPost.media', // Load original post media to include thumbnails
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
     * Get a single post with all details.
     */
    public function getPost($slug)
    {
        $userId = Auth::id();

        $post = Post::withCount(['reactions', 'comments', 'shares'])
            ->with([
                'user:id,first_name,last_name,slug,photo,user_position',
                'user.company:id,user_id,company_name,company_logo',
                'media',
                'reactions' => function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                },
                // Remove comments from getPost - should be fetched separately via GET /feed/posts/{postId}/comments
                // This reduces response size significantly
                'originalPost.user:id,first_name,last_name,slug,photo,user_position',
                'originalPost.media'
            ])
            ->where('slug', $slug)
            ->where('status', 'active')
            ->whereNull('deleted_at')
            ->firstOrFail();

        // Check visibility
        if ($post->visibility === 'private' && $post->user_id !== $userId) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to view this post'
            ], 403);
        }

        // Check if current user has reacted
        $userReaction = $post->reactions()->where('user_id', $userId)->first();

        return response()->json([
            'success' => true,
            'data' => $this->transformPost($post, $userId)
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
            'media.*' => 'file|mimes:jpeg,jpg,png,gif,webp,mp4,mov,avi,mkv,webm|max:10240', // 10MB max
            'comments_enabled' => 'nullable|boolean',
            'visibility' => 'nullable|string|in:public,private,connections',
        ]);

        // Validate that either content or media is provided
        if (!$request->content && !$request->hasFile('media')) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide content or media for your post.'
            ], 422);
        }

        // Validate total media size (10MB limit)
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
                $s3Service = app(S3Service::class);
                $order = 0;
                foreach ($request->file('media') as $file) {
                    $uploadResult = $s3Service->uploadMedia($file, 'posts');

                    $postMedia = new PostMedia();
                    $postMedia->post_id = $post->id;
                    $postMedia->media_type = $uploadResult['type'];
                    $postMedia->media_path = $uploadResult['path'];
                    $postMedia->media_url = $uploadResult['url']; // Full S3 URL
                    $postMedia->file_name = $uploadResult['file_name'];
                    $postMedia->file_size = $uploadResult['file_size'];
                    $postMedia->mime_type = $uploadResult['mime_type'];
                    
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

            $post->loadCount(['reactions', 'comments', 'shares'])
                ->load([
                    'user:id,first_name,last_name,slug,photo,user_position',
                    'user.company:id,user_id,company_name,company_logo',
                    'media',
                    'reactions' => function ($query) {
                        $query->where('user_id', Auth::id());
                    },
                    'comments' => function ($query) {
                        $query->where('status', 'active')
                            ->whereNull('parent_id')
                            ->with(['user:id,first_name,last_name,slug,photo'])
                            ->orderBy('created_at', 'asc')
                            ->limit(2);
                    }
                ]);

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
     * Update a post.
     */
    public function updatePost(Request $request, $slug)
    {
        $request->validate([
            'content' => 'nullable|string|max:10000',
            'comments_enabled' => 'nullable|boolean',
            'visibility' => 'nullable|string|in:public,private,connections',
            'media' => 'nullable|array|max:10',
            'media.*' => 'file|mimes:jpeg,jpg,png,gif,webp,mp4,mov,avi,mkv,webm|max:10240', // 10MB max
            'remove_media_ids' => 'nullable|array',
            'remove_media_ids.*' => 'integer|exists:post_media,id',
        ]);

        $post = Post::where('user_id', Auth::id())
            ->where('slug', $slug)
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

        // Update content and settings
        $post->content = $request->content ?? $post->content;
        $post->comments_enabled = $request->has('comments_enabled')
            ? $request->comments_enabled
            : $post->comments_enabled;
        $post->visibility = $request->get('visibility', $post->visibility);
        $post->save();

        // Handle media removal
        if ($request->has('remove_media_ids') && is_array($request->remove_media_ids)) {
            $mediaToRemove = PostMedia::where('post_id', $post->id)
                ->whereIn('id', $request->remove_media_ids)
                ->get();

            foreach ($mediaToRemove as $media) {
                // Delete from S3 if applicable
                if ($media->media_path && str_starts_with($media->media_path, 'media/')) {
                    $s3Service = app(S3Service::class);
                    try {
                        $s3Service->deleteMedia($media->media_path);
                        if ($media->thumbnail_path && str_starts_with($media->thumbnail_path, 'media/')) {
                            $s3Service->deleteMedia($media->thumbnail_path);
                        }
                    } catch (\Exception $e) {
                        Log::warning("Failed to delete media from S3: " . $e->getMessage());
                    }
                }
                $media->delete();
            }
        }

        // Handle new media uploads
        if ($request->hasFile('media')) {
            $s3Service = app(S3Service::class);
            $existingMediaCount = $post->media()->count();
            $maxMedia = 10;

            foreach ($request->file('media') as $file) {
                if ($existingMediaCount >= $maxMedia) {
                    break; // Don't exceed max media limit
                }

                $uploadResult = $s3Service->uploadMedia($file, 'posts');

                $postMedia = new PostMedia();
                $postMedia->post_id = $post->id;
                $postMedia->media_type = $uploadResult['type'];
                $postMedia->media_path = $uploadResult['path'];
                $postMedia->media_url = $uploadResult['url'];
                $postMedia->file_name = $uploadResult['file_name'];
                $postMedia->file_size = $uploadResult['file_size'];
                $postMedia->mime_type = $uploadResult['mime_type'];

                // Save thumbnail for videos (generated by FFmpeg if available)
                if ($uploadResult['type'] === 'video' && isset($uploadResult['thumbnail_path'])) {
                    $postMedia->thumbnail_path = $uploadResult['thumbnail_path'];
                }

                if (isset($uploadResult['duration'])) {
                    $postMedia->duration = $uploadResult['duration'];
                }

                $postMedia->order = $existingMediaCount;
                $postMedia->save();
                $existingMediaCount++;
            }
        }

        $post->loadCount(['reactions', 'comments', 'shares'])
            ->load([
                'user:id,first_name,last_name,slug,photo,user_position',
                'user.company:id,user_id,company_name,company_logo',
                'media',
                'reactions' => function ($query) {
                    $query->where('user_id', Auth::id());
                },
                'originalPost.user:id,first_name,last_name,slug,photo,user_position',
                'originalPost.media'
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Post updated successfully!',
            'data' => $this->transformPost($post, Auth::id())
        ]);
    }

    /**
     * Delete a post (soft delete).
     */
    public function deletePost($slug)
    {
        $post = Post::where('user_id', Auth::id())
            ->where('slug', $slug)
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
            'reactionable_type' => 'required|string|in:Post,PostComment,App\Models\Feed\Post,App\Models\Feed\PostComment',
            'reactionable_id' => 'required|integer',
            'reaction_type' => 'required|string|in:appreciate,cheers,support,insight,curious,smile', // Original reaction types
        ]);

        $userId = Auth::id();

        // Normalize reactionable_type to full namespace
        $reactionableType = $request->reactionable_type;
        if ($reactionableType === 'Post') {
            $reactionableType = 'App\Models\Feed\Post';
        } elseif ($reactionableType === 'PostComment') {
            $reactionableType = 'App\Models\Feed\PostComment';
        }

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
            $reaction->reactionable_type = $reactionableType; // Already normalized above
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
    }

    /**
     * Remove a reaction from a post or comment.
     */
    public function removeReaction(Request $request)
    {
        $request->validate([
            'reactionable_type' => 'required|string|in:Post,PostComment,App\Models\Feed\Post,App\Models\Feed\PostComment',
            'reactionable_id' => 'required|integer',
        ]);

        $userId = Auth::id();

        // Normalize reactionable_type to full namespace
        $reactionableType = $request->reactionable_type;
        if ($reactionableType === 'Post') {
            $reactionableType = 'App\Models\Feed\Post';
        } elseif ($reactionableType === 'PostComment') {
            $reactionableType = 'App\Models\Feed\PostComment';
        }

        $reactionableId = $request->reactionable_id;

        $reaction = Reaction::where('reactionable_type', $reactionableType)
            ->where('reactionable_id', $reactionableId)
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
        if ($request->parent_id) {
            try {
                $parentComment = PostComment::with('user')->find($request->parent_id);
                if ($parentComment && $parentComment->user_id !== Auth::id()) {
                    $replier = Auth::user();
                    $this->notificationService->sendCommentReplyNotification(
                        $parentComment->user_id,
                        $replier,
                        $post,
                        $comment,
                        $parentComment
                    );
                }
            } catch (\Exception $e) {
                Log::error('Failed to send comment reply notification', [
                    'error' => $e->getMessage()
                ]);
                // Don't fail the request if notification fails
            }
        }

        $comment->load([
            'user:id,first_name,last_name,slug,photo',
            'replies.user:id,first_name,last_name,slug,photo'
        ]);

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

        if (!$comment->user) {
            return response()->json([
                'success' => false,
                'message' => 'Comment user not found'
            ], 404);
        }

        $userData = $this->formatUserData($comment->user);

        return response()->json([
            'success' => true,
            'message' => 'Comment updated successfully!',
            'data' => [
                'id' => $comment->id,
                'content' => $comment->content,
                'created_at' => $comment->created_at,
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

        $userId = Auth::id();

        $comments = PostComment::where('post_id', $postId)
            ->where('status', 'active')
            ->whereNull('parent_id')
            ->with([
                'user:id,first_name,last_name,slug,photo',
                'replies' => function ($query) use ($userId) {
                    $query->where('status', 'active')
                        ->with([
                            'user:id,first_name,last_name,slug,photo',
                            'reactions' => function ($r) use ($userId) {
                                $r->where('user_id', $userId)->where('reaction_type', 'appreciate');
                            }
                        ])
                        ->withCount(['reactions as user_has_reacted' => function ($r) use ($userId) {
                            $r->where('user_id', $userId)->where('reaction_type', 'appreciate');
                        }])
                        ->orderBy('created_at', 'asc');
                },
                'reactions' => function ($r) use ($userId) {
                    $r->where('user_id', $userId)->where('reaction_type', 'appreciate');
                }
            ])
            ->withCount([
                'reactions as user_has_reacted' => function ($r) use ($userId) {
                    $r->where('user_id', $userId)->where('reaction_type', 'appreciate');
                }
            ])
            ->orderBy('created_at', 'asc')
            ->paginate($perPage);

        // Transform comments data
        $transformedComments = $comments->getCollection()->map(function ($comment) {
            if (!$comment->user) {
                return null; // Skip comments with deleted users
            }
            $commentUserData = $this->formatUserData($comment->user);

            $repliesData = $comment->replies->map(function ($reply) {
                if (!$reply->user) {
                    return null; // Skip replies with deleted users
                }
                $replyUserData = $this->formatUserData($reply->user);
                return [
                    'id' => $reply->id,
                    'content' => $reply->content,
                    'created_at' => $reply->created_at,
                    'user_has_reacted' => isset($reply->user_has_reacted) && $reply->user_has_reacted > 0,
                    'user' => [
                        'id' => $replyUserData['id'],
                        'name' => trim($replyUserData['first_name'] . ' ' . $replyUserData['last_name']),
                        'avatar' => $replyUserData['photo'],
                        'initials' => $replyUserData['user_initials'],
                        'has_photo' => $replyUserData['user_has_photo'],
                        'slug' => $reply->user->slug ?? '',
                    ],
                ];
            })->filter()->values(); // Filter out null replies

            return [
                'id' => $comment->id,
                'content' => $comment->content,
                'created_at' => $comment->created_at,
                'user_has_reacted' => isset($comment->user_has_reacted) && $comment->user_has_reacted > 0,
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
        })->filter(); // Filter out null comments

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

        $userId = Auth::id();

        $posts = Post::withCount(['reactions', 'comments', 'shares'])
            ->with([
                'user:id,first_name,last_name,slug,photo,user_position',
                'user.company:id,user_id,company_name,company_logo',
                'media',
                'reactions' => function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                },
                'comments' => function ($query) use ($userId) {
                    $query->where('status', 'active')
                        ->whereNull('parent_id')
                        ->with([
                            'user:id,first_name,last_name,slug,photo',
                            'reactions' => function ($r) use ($userId) {
                                $r->where('user_id', $userId)->where('reaction_type', 'appreciate');
                            }
                        ])
                        ->withCount([
                            'reactions as user_has_reacted' => function ($r) use ($userId) {
                                $r->where('user_id', $userId)->where('reaction_type', 'appreciate');
                            }
                        ])
                        ->orderBy('created_at', 'asc')
                        ->limit(3);
                },
            ])
            ->where('user_id', $targetUserId)
            ->where('status', 'active')
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $transformedPosts = $posts->getCollection()->map(function ($post) use ($userId) {
            return $this->transformPost($post, $userId);
        });

        return response()->json([
            'success' => true,
            'data' => $transformedPosts,
            'current_page' => $posts->currentPage(),
            'last_page' => $posts->lastPage(),
            'has_more' => $posts->hasMorePages(),
        ]);
    }

    /**
     * Get all available reaction types with their metadata
     * This provides a single source of truth for reaction types across the application
     */
    public function getReactionTypes()
    {
        try {
            $reactionTypes = [
                [
                    'type' => 'appreciate',
                    'emoji' => '👍',
                    'label' => 'Appreciate',
                    'description' => 'Show appreciation',
                    'color' => '#0a66c2', // LinkedIn blue
                ],
                [
                    'type' => 'cheers',
                    'emoji' => '🎉',
                    'label' => 'Cheers',
                    'description' => 'Celebrate an achievement',
                    'color' => '#6dae4f', // Green
                ],
                [
                    'type' => 'support',
                    'emoji' => '🤝',
                    'label' => 'Support',
                    'description' => 'Show support',
                    'color' => '#df704d', // Orange
                ],
                [
                    'type' => 'insight',
                    'emoji' => '💡',
                    'label' => 'Insight',
                    'description' => 'Acknowledge a great idea',
                    'color' => '#f5c344', // Yellow
                ],
                [
                    'type' => 'curious',
                    'emoji' => '🤔',
                    'label' => 'Curious',
                    'description' => 'Express interest',
                    'color' => '#8a3ffc', // Purple
                ],
                [
                    'type' => 'smile',
                    'emoji' => '😊',
                    'label' => 'Smile',
                    'description' => 'Share positivity',
                    'color' => '#df704d', // Orange
                ],
            ];

            return response()->json([
                'success' => true,
                'data' => $reactionTypes,
                'count' => count($reactionTypes),
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching reaction types: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load reaction types.'
            ], 500);
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
                if (!$reaction->user) {
                    return null; // Skip reactions with deleted users
                }
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
            })->filter(); // Filter out null reactions

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
                if (!$share->user) {
                    return null; // Skip shares with deleted users
                }
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
            })->filter(); // Filter out null shares

        return response()->json([
            'success' => true,
            'count' => $shares->count(),
            'shares' => $shares
        ]);
    }

    /**
     * Get reaction count for a post.
     */
    public function getReactionCount($postId)
    {
        $post = Post::findOrFail($postId);

        $reactions = $post->reactions()
            ->with('user:id,first_name,last_name,photo,user_position,city,state')
            ->get()
            ->map(function ($reaction) {
                if (!$reaction->user) {
                    return null; // Skip reactions with deleted users
                }
                $userData = $this->formatUserData($reaction->user);
                return [
                    'id' => $reaction->id,
                    'type' => $reaction->reaction_type,
                    'created_at' => $reaction->created_at,
                    'user_id' => $reaction->user_id,
                    'user_name' => trim($userData['first_name'] . ' ' . $userData['last_name']),
                    'user' => [
                        'id' => $userData['id'],
                        'name' => trim($userData['first_name'] . ' ' . $userData['last_name']),
                        'avatar' => $userData['photo'],
                        'initials' => $userData['user_initials'],
                        'has_photo' => $userData['user_has_photo'],
                        'position' => $reaction->user->user_position ?? '',
                        'city' => $userData['city'] ?? null,
                        'state' => $userData['state'] ?? null,
                    ]
                ];
            })->filter(); // Filter out null reactions

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
        $count = $post->comments()->where('status', 'active')->whereNull('deleted_at')->count();

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }

    /**
     * Transform post data for consistent output.
     */
    private function transformPost($post, $userId = null)
    {
        // Handle case where user might be null (shouldn't happen, but safety check)
        if (!$post->user) {
            throw new \Exception('Post user not found');
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
            'media' => $post->relationLoaded('media') && $post->media
                ? $post->media->map(function($m) use ($s3Service) {
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
                })->toArray()
                : [],
            'reactions' => $this->getReactionsArray($post),
            'user_reaction' => $userReaction ? [
                'type' => $userReaction->reaction_type,
                'created_at' => $userReaction->created_at,
            ] : null,
        ];

        // ✅ FIX: include slug for original post user
        if (
            $post->original_post_id &&
            $post->relationLoaded('originalPost') &&
            $post->originalPost &&
            $post->originalPost->user
        ) {
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
                    'slug' => $post->originalPost->user->slug ?? '', // ✅ added
                ],
                'media' => ($post->originalPost->relationLoaded('media') && $post->originalPost->media)
                    ? $post->originalPost->media->map(function($m) use ($s3Service) {
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
                    })->toArray()
                    : [],
            ];
        }

        return $transformed;
    }


    /**
     * Get reactions as array format (aggregated by type)
     */
    private function getReactionsArray($post)
    {
        // Get reaction counts grouped by type
        $reactionCounts = Reaction::where('reactionable_type', 'App\Models\Feed\Post')
            ->where('reactionable_id', $post->id)
            ->select('reaction_type', DB::raw('count(*) as count'))
            ->groupBy('reaction_type')
            ->pluck('count', 'reaction_type')
            ->toArray();

        // Return as array with all original reaction types
        $reactionTypes = ['appreciate', 'cheers', 'support', 'insight', 'curious', 'smile'];
        $result = [];

        foreach ($reactionTypes as $type) {
            $result[] = [
                'type' => $type,
                'count' => isset($reactionCounts[$type]) ? (int) $reactionCounts[$type] : 0
            ];
        }

        return $result;
    }
}
