<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfileView extends Model
{
    protected $fillable = [
        'viewed_user_id',
        'viewer_id',
        'ip_address',
        'user_agent',
    ];

    /**
     * Get the user whose profile was viewed
     */
    public function viewedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'viewed_user_id');
    }

    /**
     * Get the user who viewed the profile (null if anonymous)
     */
    public function viewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'viewer_id');
    }
}
