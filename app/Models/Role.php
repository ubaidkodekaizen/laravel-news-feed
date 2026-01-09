<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    /**
     * Get the permissions for the role.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permissions')
                    ->withTimestamps();
    }

    /**
     * Get the users with this role.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Check if the role has a specific permission.
     * Admin role (id = 1) has all permissions by default.
     */
    public function hasPermission(string $permissionSlug): bool
    {
        // Admin role (id = 1) has all permissions
        if ($this->id == 1) {
            return true;
        }
        
        return $this->permissions()->where('slug', $permissionSlug)->exists();
    }
}
