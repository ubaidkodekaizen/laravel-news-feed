<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Reaction;


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
    ];

    // Define relationships

    // The conversation that this message belongs to
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    // The sender of this message
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // The receiver of this message
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function reactions()
    {
        return $this->hasMany(Reaction::class);
    }
}
