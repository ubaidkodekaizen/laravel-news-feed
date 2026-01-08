<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Chat\Conversation;
use App\Models\Feed\Reaction;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{

    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;


    protected $fillable = [
        'first_name',
        'last_name',
        'slug',
        'email',
        'password',
        'phone',
        'linkedin_url',
        'x_url',
        'instagram_url',
        'facebook_url',
        'address',
        'country',
        'state',
        'city',
        'county',
        'zip_code',
        'industry_to_connect',
        'sub_category_to_connect',
        'community_interest',
        'status',
        'added_by',
        'paid',
        'phone_public',
        'email_public',
        'user_position',
        'gender',
        'age_group',
        'ethnicity',
        'nationality',
        'languages',
        'marital_status',
        'is_amcob',
        'duration',
        'email_verified_at',
    ];

    public function company()
    {
        return $this->hasOne(\App\Models\Business\Company::class)->whereHas('user', function ($query) {
            $query->whereNull('deleted_at');
        });
    }

    public function products()
    {
        return $this->hasMany(\App\Models\Business\Product::class)->whereHas('user', function ($query) {
            $query->whereNull('deleted_at');
        });
    }

    public function services()
    {
        return $this->hasMany(\App\Models\Business\Service::class)->whereHas('user', function ($query) {
            $query->whereNull('deleted_at');
        });
    }


    public function subscriptions()
    {
        return $this->hasMany(\App\Models\Business\Subscription::class)->whereHas('user', function ($query) {
            $query->whereNull('deleted_at');
        });
    }

    public function userEducations()
    {
        return $this->hasMany(UserEducation::class)->whereHas('user', function ($query) {
            $query->whereNull('deleted_at');
        });
    }

    public function conversations()
    {
        // Conversations use user_one_id and user_two_id, so we need to check both
        // This relationship is handled manually in the controller for eager loading
        return $this->hasMany(Conversation::class, 'user_one_id');
    }

    public function userIcp()
    {
        return $this->hasOne(UserIcp::class);
    }

    public function reactions()
    {
        return $this->hasMany(Reaction::class);
    }

    public function posts()
    {
        return $this->hasMany(\App\Models\Feed\Post::class);
    }

    public function postReactions()
    {
        return $this->hasMany(\App\Models\Feed\Reaction::class)->where('reactionable_type', \App\Models\Feed\Post::class);
    }

    public function postComments()
    {
        return $this->hasMany(\App\Models\Feed\PostComment::class);
    }

    public function commentReactions()
    {
        return $this->hasMany(\App\Models\Feed\Reaction::class)->where('reactionable_type', \App\Models\Feed\PostComment::class);
    }

    public function postShares()
    {
        return $this->hasMany(\App\Models\Feed\PostShare::class);
    }


    public function blockedUsers()
    {
        return $this->belongsToMany(User::class, 'blocked_users', 'blocker_id', 'blocked_id')
            ->withTimestamps();
    }

    public function blockedByUsers()
    {
        return $this->belongsToMany(User::class, 'blocked_users', 'blocked_id', 'blocker_id')
            ->withTimestamps();
    }

    /**
     * Check if this user has blocked another user
     */
    public function hasBlocked($userId): bool
    {
        return $this->blockedUsers()->where('blocked_id', $userId)->exists();
    }

    /**
     * Check if this user is blocked by another user
     */
    public function isBlockedBy($userId): bool
    {
        return $this->blockedByUsers()->where('blocker_id', $userId)->exists();
    }


    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
