<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Users\User;
use App\Models\System\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get roles
        $adminRole = Role::where('slug', 'admin')->first();
        $memberRole = Role::where('slug', 'member')->first();

        // Create Admin User
        User::updateOrCreate(
            ['email' => 'admin@newsfeed.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'slug' => 'admin-user',
                'email' => 'admin@newsfeed.com',
                'password' => Hash::make('12345678'),
                'role_id' => $adminRole ? $adminRole->id : 1,
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        // Create Regular User
        User::updateOrCreate(
            ['email' => 'user@newsfeed.com'],
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'slug' => 'john-doe',
                'email' => 'user@newsfeed.com',
                'password' => Hash::make('12345678'),
                'role_id' => $memberRole ? $memberRole->id : 4,
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        // Create additional test users
        for ($i = 1; $i <= 5; $i++) {
            User::updateOrCreate(
                ['email' => "user{$i}@newsfeed.com"],
                [
                    'first_name' => "User{$i}",
                    'last_name' => 'Test',
                    'slug' => "user{$i}-test",
                    'email' => "user{$i}@newsfeed.com",
                    'password' => Hash::make('12345678'),
                    'role_id' => $memberRole ? $memberRole->id : 4,
                    'status' => 'active',
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
