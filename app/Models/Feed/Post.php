<?php

namespace App\Models\Feed;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\Feed\PostMedia;
use App\Models\Feed\Reaction;
use App\Models\Feed\PostComment;
use App\Models\Feed\PostShare;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'original_post_id',
        'content',
        'slug',
        'comments_enabled',
        'visibility',
        'status',
        'reactions_count',
        'comments_count',
        'shares_count',
    ];

    protected $casts = [
        'comments_enabled' => 'boolean',
        'reactions_count' => 'integer',
        'comments_count' => 'integer',
        'shares_count' => 'integer',
    ];

    /**
     * Get the user who created the post.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the original post if this is a shared post.
     */
    public function originalPost(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'original_post_id');
    }

    /**
     * Get all posts that share this post.
     */
    public function sharedPosts(): HasMany
    {
        return $this->hasMany(Post::class, 'original_post_id');
    }

    /**
     * Get all media for this post.
     */
    public function media(): HasMany
    {
        return $this->hasMany(PostMedia::class);
    }

    /**
     * Get all reactions for this post.
     */
    public function reactions(): MorphMany
    {
        return $this->morphMany(Reaction::class, 'reactionable');
    }

    /**
     * Get all comments for this post.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(PostComment::class)->whereNull('parent_id');
    }

    /**
     * Get all comments including replies for this post.
     */
    public function allComments(): HasMany
    {
        return $this->hasMany(PostComment::class);
    }

    /**
     * Get all shares for this post.
     */
    public function shares(): HasMany
    {
        return $this->hasMany(PostShare::class);
    }

    /**
     * Increment reactions count.
     */
    public function incrementReactionsCount(): void
    {
        $this->increment('reactions_count');
    }

    /**
     * Decrement reactions count.
     */
    public function decrementReactionsCount(): void
    {
        $this->decrement('reactions_count');
    }

    /**
     * Increment comments count.
     */
    public function incrementCommentsCount(): void
    {
        $this->increment('comments_count');
    }

    /**
     * Decrement comments count.
     */
    public function decrementCommentsCount(): void
    {
        $this->decrement('comments_count');
    }

    /**
     * Increment shares count.
     */
    public function incrementSharesCount(): void
    {
        $this->increment('shares_count');
    }

    /**
     * Decrement shares count.
     */
    public function decrementSharesCount(): void
    {
        $this->decrement('shares_count');
    }
}

