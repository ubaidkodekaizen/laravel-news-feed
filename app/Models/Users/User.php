<?php

namespace App\Models\Users;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Feed\Post;
use App\Models\Feed\PostComment;
use App\Models\Feed\PostShare;
use App\Models\Feed\Reaction;
// Notification model removed - not part of newsfeed boilerplate
use App\Models\Reports\Report;
use App\Models\System\Role;
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
        'photo',
        'bio',
        'location',
        'website',
        'status',
        'email_verified_at',
        'role_id',
    ];


    public function reactions()
    {
        return $this->hasMany(Reaction::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function postReactions()
    {
        return $this->hasMany(Reaction::class)->where('reactionable_type', Post::class);
    }

    public function postComments()
    {
        return $this->hasMany(PostComment::class);
    }

    public function commentReactions()
    {
        return $this->hasMany(Reaction::class)->where('reactionable_type', PostComment::class);
    }

    public function postShares()
    {
        return $this->hasMany(PostShare::class);
    }

    // Notifications relationship removed - not part of newsfeed boilerplate

    /**
     * Get all reports made by this user
     */
    public function reports()
    {
        return $this->hasMany(Report::class, 'reporter_id');
    }

    /**
     * Get the role that belongs to the user.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Check if the user has a specific role.
     */
    public function isAdmin(): bool
    {
        return $this->role_id == 1;
    }

    /**
     * Check if the user is a regular member.
     */
    public function isMember(): bool
    {
        return $this->role_id == 4;
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
