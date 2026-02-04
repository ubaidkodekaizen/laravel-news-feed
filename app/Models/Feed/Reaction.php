<?php

namespace App\Models\Feed;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Users\User;
use App\Models\Feed\Post;
use App\Models\Feed\PostComment;

class Reaction extends Model
{
    use HasFactory;

    // Use feed_reactions table (separate from messages reactions)
    protected $table = 'feed_reactions';

    protected $fillable = [
        'reactionable_id',
        'reactionable_type',
        'user_id',
        'reaction_type',
    ];

    /**
     * Get the parent reactionable model (Post, PostComment, etc.).
     * Note: Message reactions use a separate table (reactions) and model (MessageReaction).
     */
    public function reactionable(): MorphTo
    {
        return $this->morphTo();
    }


    /**
     * Get the user who reacted.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($reaction) {
            $reactionable = $reaction->reactionable;
            
            // Update cached counts based on type
            if ($reactionable instanceof Post) {
                $reactionable->incrementReactionsCount();
            } elseif ($reactionable instanceof PostComment) {
                // Comments don't have reactions_count, but we could add it if needed
                // For now, we'll just update the post's count if it's a top-level comment
                if (!$reactionable->isReply()) {
                    $reactionable->post->incrementReactionsCount();
                }
            }
        });

        static::deleted(function ($reaction) {
            $reactionable = $reaction->reactionable;
            
            if ($reactionable instanceof Post) {
                $reactionable->decrementReactionsCount();
            } elseif ($reactionable instanceof PostComment) {
                if (!$reactionable->isReply()) {
                    $reactionable->post->decrementReactionsCount();
                }
            }
        });
    }
}
