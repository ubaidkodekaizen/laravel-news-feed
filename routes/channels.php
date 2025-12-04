<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Conversation;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


 
Broadcast::channel('private-chat.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId; // Make sure the user is authorized
});


Broadcast::channel('user-activity.{conversationId}', function ($user, $conversationId) {
    // Check if the user is part of the conversation by checking user_one_id or user_two_id
    $conversation = Conversation::find($conversationId);
    if ($conversation) {
        return $conversation->user_one_id === $user->id || $conversation->user_two_id === $user->id;
    }
    return false; // If conversation doesn't exist, return false
});

// Presence channel for tracking online users
Broadcast::channel('presence-online', function ($user) {
    // Ensure the user is authenticated
    if ($user) {
        // Return user data to be available in the presence channel
        return [
            'id' => $user->id,
            'name' => $user->first_name . ' ' . $user->last_name,
            'email' => $user->email,
        ];
    }
});