<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Chat\Conversation;
use App\Models\Feed\Reaction;
use App\Models\ProfileView;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        'role_id',
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

    /**
     * Get all profile views for this user
     */
    public function profileViews()
    {
        return $this->hasMany(ProfileView::class, 'viewed_user_id');
    }

    /**
     * Get profile views count
     */
    public function getProfileViewsCountAttribute()
    {
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('profile_views')) {
                return 0;
            }
            return $this->profileViews()->count();
        } catch (\Exception $e) {
            return 0;
        }
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

    /**
     * Get the role that belongs to the user.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the user-specific permissions (direct permissions assigned to this user).
     */
    public function userPermissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'user_permissions')
                    ->withTimestamps();
    }

    /**
     * Check if the user has a specific permission.
     * Admin users (role_id = 1) have all permissions by default.
     * For other users, checks both user-specific permissions and role permissions.
     * Role 4 (Member) users don't have admin permissions, so this will return false for them.
     */
    public function hasPermission(string $permissionSlug): bool
    {
        // Admin users (role_id = 1) have all permissions
        if ($this->role_id == 1) {
            return true;
        }
        
        // Role 4 (Member) users don't have admin permissions
        // They should never access admin routes, but if hasPermission is called, return false
        if ($this->role_id == 4) {
            return false;
        }
        
        // If role_id is null or invalid, return false
        if (!$this->role_id || !in_array($this->role_id, [1, 2, 3])) {
            return false;
        }
        
        // Load userPermissions if not already loaded
        if (!$this->relationLoaded('userPermissions')) {
            $this->load('userPermissions');
        }
        
        // Check user-specific permissions first (takes precedence)
        // Use where() to check if any permission has the matching slug
        if ($this->userPermissions && $this->userPermissions->where('slug', $permissionSlug)->isNotEmpty()) {
            return true;
        }
        
        // If no user-specific permission, check role permissions
        if (!$this->relationLoaded('role')) {
            $this->load('role');
        }
        
        if (!$this->role) {
            return false;
        }

        return $this->role->hasPermission($permissionSlug);
    }

    /**
     * Check if the user has a specific role.
     */
    public function hasRole(string $roleSlug): bool
    {
        if (!$this->role) {
            return false;
        }

        return $this->role->slug === $roleSlug;
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
