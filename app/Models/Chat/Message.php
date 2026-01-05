<?php

namespace App\Models\Chat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\Chat\Conversation;
use App\Models\Chat\MessageReaction;


class Message extends Model
{
    use HasFactory;

    // Define the table associated with the model (optional if it's the default 'messages')
    protected $table = 'messages';

    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'conversation_id',
        'sender_id',
        'receiver_id',
        'content',
        'read_at',
        'edited_at',
    ];

    // Define date casting
    protected $casts = [
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'edited_at' => 'datetime',
    ];

    // Define relationships

    // The conversation that this message belongs to
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    // The sender of this message
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // The receiver of this message
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function reactions(): HasMany
    {
        // Use the old reactions table with message_id (separate from feed_reactions)
        return $this->hasMany(MessageReaction::class, 'message_id');
    }
}
