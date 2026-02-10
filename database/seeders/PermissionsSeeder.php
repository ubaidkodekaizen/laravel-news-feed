<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Users Management
            ['name' => 'View Users', 'slug' => 'users.view', 'group' => 'users', 'description' => 'View users list'],
            ['name' => 'Add Users', 'slug' => 'users.create', 'group' => 'users', 'description' => 'Add new users'],
            ['name' => 'Edit Users', 'slug' => 'users.edit', 'group' => 'users', 'description' => 'Edit existing users'],
            ['name' => 'Delete Users', 'slug' => 'users.delete', 'group' => 'users', 'description' => 'Delete users'],
            ['name' => 'Restore Users', 'slug' => 'users.restore', 'group' => 'users', 'description' => 'Restore deleted users'],
            ['name' => 'View User Profile', 'slug' => 'users.profile', 'group' => 'users', 'description' => 'View user profile details'],
            ['name' => 'Send Password Reset', 'slug' => 'users.password.reset', 'group' => 'users', 'description' => 'Send password reset link to users'],
            
            // Feed Management
            ['name' => 'View Feed', 'slug' => 'feed.view', 'group' => 'feed', 'description' => 'View feed posts list and details'],
            ['name' => 'Delete Feed Posts', 'slug' => 'feed.delete', 'group' => 'feed', 'description' => 'Delete feed posts and comments'],
            ['name' => 'Restore Feed Posts', 'slug' => 'feed.restore', 'group' => 'feed', 'description' => 'Restore deleted feed posts and comments'],
        ];

        // Remove dashboard permission if it exists (all roles 1, 2, 3 have dashboard access automatically)
        $dashboardPermission = DB::table('permissions')->where('slug', 'dashboard.view')->first();
        if ($dashboardPermission) {
            // Remove from role_permissions
            DB::table('role_permissions')->where('permission_id', $dashboardPermission->id)->delete();
            // Remove from user_permissions
            DB::table('user_permissions')->where('permission_id', $dashboardPermission->id)->delete();
            // Remove the permission itself
            DB::table('permissions')->where('slug', 'dashboard.view')->delete();
        }

        foreach ($permissions as $permission) {
            // Check if permission already exists
            $existingPermission = DB::table('permissions')->where('slug', $permission['slug'])->first();
            
            if ($existingPermission) {
                // Update existing permission
                DB::table('permissions')
                    ->where('slug', $permission['slug'])
                    ->update([
                        'name' => $permission['name'],
                        'group' => $permission['group'],
                        'description' => $permission['description'],
                        'updated_at' => now(),
                    ]);
            } else {
                // Insert new permission
                DB::table('permissions')->insert([
                    'name' => $permission['name'],
                    'slug' => $permission['slug'],
                    'group' => $permission['group'],
                    'description' => $permission['description'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Assign all permissions to Admin role (role_id = 1)
        $adminRole = DB::table('roles')->where('slug', 'admin')->first();
        if ($adminRole) {
            // Get all permission IDs
            $allPermissionIds = DB::table('permissions')->pluck('id')->toArray();
            
            // Get existing role_permissions for Admin role
            $existingRolePermissions = DB::table('role_permissions')
                ->where('role_id', $adminRole->id)
                ->pluck('permission_id')
                ->toArray();
            
            // Assign all permissions to Admin role (only if not already assigned)
            $permissionData = [];
            foreach ($allPermissionIds as $permissionId) {
                // Only insert if not already exists
                if (!in_array($permissionId, $existingRolePermissions)) {
                    $permissionData[] = [
                        'role_id' => $adminRole->id,
                        'permission_id' => $permissionId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
            
            // Insert only new permissions (avoid duplicates)
            if (!empty($permissionData)) {
                // Use insertOrIgnore to prevent duplicate key errors
                foreach ($permissionData as $data) {
                    DB::table('role_permissions')->insertOrIgnore($data);
                }
            }
        }
    }
}
