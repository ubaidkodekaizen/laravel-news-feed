<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Feed\Post;
use App\Models\Feed\PostMedia;
use App\Models\Feed\PostComment;
use App\Models\Feed\Reaction;
use App\Models\Feed\PostShare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FeedController extends Controller
{
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
        ])->latest()->paginate(10);

        // transform to the shape used in your Blade partials
        $posts = $postsPaginator->getCollection()->map(function ($p) {
            // derive user name & avatar with sensible fallbacks
            $user = $p->user;
            $name = $user->name ?? trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?? 'Unknown User';
            $avatar = $user->avatar_url ?? $user->avatar ?? 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png';
            $position = $user->position ?? ($user->job_title ?? '');

            return [
                'id' => $p->id,
                'content' => $p->content,
                'created_at' => $p->created_at,
                'likes_count' => $p->reactions_count ?? $p->reactions()->count(),
                'comments_count' => $p->comments_count ?? $p->comments()->count(),
                'user' => [
                    'id' => $user->id ?? null,
                    'name' => $name,
                    'position' => $position,
                    'avatar' => $avatar,
                ],
                'media' => $p->media->map(function ($m) {
                    return [
                        'media_type' => $m->media_type,
                        'media_url' => $m->media_url,
                        'mime_type' => $m->mime_type,
                        'file_name' => $m->file_name,
                    ];
                })->toArray(),
                'comments' => $p->comments->take(2)->map(function ($c) {
                    $commentUser = $c->user;
                    $commentName = $commentUser->name ?? trim(($commentUser->first_name ?? '') . ' ' . ($commentUser->last_name ?? '')) ?? 'Unknown';
                    $commentAvatar = $commentUser->avatar_url ?? $commentUser->avatar ?? 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png';
                    return [
                        'id' => $c->id,
                        'content' => $c->content,
                        'created_at' => $c->created_at,
                        'user' => [
                            'id' => $commentUser->id ?? null,
                            'name' => $commentName,
                            'avatar' => $commentAvatar,
                        ],
                    ];
                })->toArray(),
                'reactions' => $p->reactions->take(3)->map(function ($r) {
                    // very small representation for your current UI (emoji/type logic can be improved)
                    return [
                        'type' => $r->reaction_type,
                        'user_id' => $r->user_id,
                    ];
                })->toArray(),
            ];
        })->toArray();

        // pass both the transformed posts array and the original paginator (for links)
        return view('user.news-feed', [
            'posts' => $posts,
            'pagination' => $postsPaginator,
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
    public function getPost($id)
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
            ->where('id', $id)
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
            'comments_enabled' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            $post = new Post();
            $post->user_id = Auth::id();
            $post->content = $request->content;
            $post->comments_enabled = $request->get('comments_enabled', true);
            $post->status = 'active';
            $post->save();

            // Handle media uploads
            if ($request->hasFile('media')) {
                $order = 0;
                foreach ($request->file('media') as $file) {
                    $mediaType = str_starts_with($file->getMimeType(), 'image/') ? 'image' : 'video';
                    $mediaPath = $file->store('posts/media', 'public');
                    $mediaUrl = Storage::url($mediaPath);

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
}
