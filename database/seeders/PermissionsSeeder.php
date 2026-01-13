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
            ['name' => 'Edit Company', 'slug' => 'users.company.edit', 'group' => 'users', 'description' => 'Edit user company details'],
            ['name' => 'Send Password Reset', 'slug' => 'users.password.reset', 'group' => 'users', 'description' => 'Send password reset link to users'],
            
            // Subscriptions Management
            ['name' => 'View Subscriptions', 'slug' => 'subscriptions.view', 'group' => 'subscriptions', 'description' => 'View subscriptions list'],
            ['name' => 'Filter Subscriptions', 'slug' => 'subscriptions.filter', 'group' => 'subscriptions', 'description' => 'Filter subscriptions by status, type, platform'],
            
            // Products/Services Management
            ['name' => 'View Products/Services', 'slug' => 'products-services.view', 'group' => 'products-services', 'description' => 'View products and services list'],
            ['name' => 'View Product', 'slug' => 'products.view', 'group' => 'products-services', 'description' => 'View product details'],
            ['name' => 'View Service', 'slug' => 'services.view', 'group' => 'products-services', 'description' => 'View service details'],
            ['name' => 'Edit Product', 'slug' => 'products.edit', 'group' => 'products-services', 'description' => 'Edit existing products'],
            ['name' => 'Edit Service', 'slug' => 'services.edit', 'group' => 'products-services', 'description' => 'Edit existing services'],
            ['name' => 'Delete Product', 'slug' => 'products.delete', 'group' => 'products-services', 'description' => 'Delete products'],
            ['name' => 'Delete Service', 'slug' => 'services.delete', 'group' => 'products-services', 'description' => 'Delete services'],
            ['name' => 'Restore Product', 'slug' => 'products.restore', 'group' => 'products-services', 'description' => 'Restore deleted products'],
            ['name' => 'Restore Service', 'slug' => 'services.restore', 'group' => 'products-services', 'description' => 'Restore deleted services'],
            
            // Blogs Management
            ['name' => 'View Blogs', 'slug' => 'blogs.view', 'group' => 'blogs', 'description' => 'View blogs list'],
            ['name' => 'Add Blogs', 'slug' => 'blogs.create', 'group' => 'blogs', 'description' => 'Add new blogs'],
            ['name' => 'Edit Blogs', 'slug' => 'blogs.edit', 'group' => 'blogs', 'description' => 'Edit existing blogs'],
            ['name' => 'Delete Blogs', 'slug' => 'blogs.delete', 'group' => 'blogs', 'description' => 'Delete blogs'],
            ['name' => 'Restore Blogs', 'slug' => 'blogs.restore', 'group' => 'blogs', 'description' => 'Restore deleted blogs'],
            
            // Events Management
            ['name' => 'View Events', 'slug' => 'events.view', 'group' => 'events', 'description' => 'View events list'],
            ['name' => 'Add Events', 'slug' => 'events.create', 'group' => 'events', 'description' => 'Add new events'],
            ['name' => 'Edit Events', 'slug' => 'events.edit', 'group' => 'events', 'description' => 'Edit existing events'],
            ['name' => 'Delete Events', 'slug' => 'events.delete', 'group' => 'events', 'description' => 'Delete events'],
            ['name' => 'Restore Events', 'slug' => 'events.restore', 'group' => 'events', 'description' => 'Restore deleted events'],
            
            // Ads Management
            ['name' => 'View Ads', 'slug' => 'ads.view', 'group' => 'ads', 'description' => 'View ads list'],
            ['name' => 'Add Ads', 'slug' => 'ads.create', 'group' => 'ads', 'description' => 'Add new ads'],
            ['name' => 'Edit Ads', 'slug' => 'ads.edit', 'group' => 'ads', 'description' => 'Edit existing ads'],
            ['name' => 'Delete Ads', 'slug' => 'ads.delete', 'group' => 'ads', 'description' => 'Delete ads'],
            ['name' => 'Restore Ads', 'slug' => 'ads.restore', 'group' => 'ads', 'description' => 'Restore deleted ads'],
            
            // Managers Management (Only for Admin)
            ['name' => 'View Managers', 'slug' => 'managers.view', 'group' => 'managers', 'description' => 'View managers and editors list'],
            ['name' => 'Add Managers', 'slug' => 'managers.create', 'group' => 'managers', 'description' => 'Add new managers/editors'],
            ['name' => 'Edit Managers', 'slug' => 'managers.edit', 'group' => 'managers', 'description' => 'Edit managers/editors'],
            ['name' => 'Delete Managers', 'slug' => 'managers.delete', 'group' => 'managers', 'description' => 'Delete managers/editors'],
            ['name' => 'Restore Managers', 'slug' => 'managers.restore', 'group' => 'managers', 'description' => 'Restore deleted managers/editors'],
            ['name' => 'Manage Permissions', 'slug' => 'managers.permissions', 'group' => 'managers', 'description' => 'Manage permissions for managers/editors'],
            
            // Scheduler Logs Management (Admin only)
            ['name' => 'View Scheduler Logs', 'slug' => 'scheduler-logs.view', 'group' => 'scheduler-logs', 'description' => 'View scheduler logs list'],
            ['name' => 'Delete Scheduler Logs', 'slug' => 'scheduler-logs.delete', 'group' => 'scheduler-logs', 'description' => 'Delete scheduler logs'],
            ['name' => 'Restore Scheduler Logs', 'slug' => 'scheduler-logs.restore', 'group' => 'scheduler-logs', 'description' => 'Restore deleted scheduler logs'],
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
