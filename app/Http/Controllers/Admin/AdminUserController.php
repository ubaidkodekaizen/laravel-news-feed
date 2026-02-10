<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Users\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Services\S3Service;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Mail\PasswordReset;
use App\Mail\WelcomeNewUser;

class AdminUserController extends Controller
{
    public function showUsers(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user && $user->role_id == 1;
        
        // Check if user has view permission
        if (!$isAdmin && (!$user || !$user->hasPermission('users.view'))) {
            abort(403, 'Unauthorized action.');
        }
        
        $filter = $request->get('filter', 'all');
        
        $query = User::where('role_id', 4);
        
        // Apply filter
        switch ($filter) {
            case 'web':
                $query->where('added_by', 'web');
                break;
            case 'google':
                $query->where('added_by', 'google');
                break;
            case 'apple':
                $query->where('added_by', 'apple');
                break;
            case 'amcob':
                $query->where('added_by', 'Admin');
                break;
            case 'deleted':
                $query->onlyTrashed();
                break;
            case 'all':
            default:
                $query->whereNull('deleted_at'); // Active users only
                break;
        }
        
        $users = $query->orderByDesc('id')->get();
        
        // Optimize: Get all counts in a single query using conditional aggregation
        $baseQuery = User::where('role_id', 4);
        $counts = [
            'all' => (clone $baseQuery)->whereNull('deleted_at')->count(),
            'web' => (clone $baseQuery)->where('added_by', 'web')->whereNull('deleted_at')->count(),
            'google' => (clone $baseQuery)->where('added_by', 'google')->whereNull('deleted_at')->count(),
            'apple' => (clone $baseQuery)->where('added_by', 'apple')->whereNull('deleted_at')->count(),
            'amcob' => (clone $baseQuery)->where('added_by', 'Admin')->whereNull('deleted_at')->count(),
            'deleted' => (clone $baseQuery)->onlyTrashed()->count(),
        ];
        
        // Note: While we could combine these into a single query with conditional aggregation,
        // keeping separate queries for clarity and maintainability. The performance impact is minimal
        // as these are simple count queries with indexes.
        
        return view('admin.users.users', compact('users', 'counts', 'filter'));
    }

    public function addUser()
    {
        return view('admin.users.add-user');
    }

    public function createUser(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|min:2|max:255',
            'last_name' => 'required|string|min:2|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        // Create the user with email verified automatically
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(), // Automatically verify email
            'role_id' => 4, // Member role
            'status' => 'active',
        ]);

        // Create password reset token for password setup
        $token = Str::random(64);
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => now()]
        );

        // Prepare email data with credentials
        $emailData = [
            'token' => $token,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => $request->password, // Include password for email (admin-created users only)
        ];
        
        try {
            Mail::to($request->email)->queue(new WelcomeNewUser($emailData));
            
            Log::info('Welcome email queued successfully', ['email' => $request->email]);
        } catch (\Exception $e) {
            Log::error('Welcome email failed to queue', [
                'email' => $request->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            // Continue anyway - user is created, just email failed
        }
        
        return redirect()->route('admin.users')->with('success', 'User created successfully!');
    }

    public function showUserById($id)
    {
        $user = User::where('id', $id)->firstOrFail();
        return view('admin.users.user-profile', compact('user'));
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit-user', compact('user'));
    }

    public function updateUserDetails(Request $request)
    {
        $normalizeUrl = function ($url) {
            if (!$url) {
                return null;
            }
            $url = trim($url);
            if (!preg_match('~^https?://~i', $url)) {
                $url = 'https://' . $url;
            }
            return $url;
        };

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $request->id,
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'location' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB max
        ]);

        $capitalize = function ($value) {
            return $value ? ucwords(strtolower($value)) : null;
        };

        $user = User::find($request->id);

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->phone = $request->phone ?? '';
        $user->bio = $request->bio ?? '';
        $user->location = $request->location ?? '';
        $user->website = $normalizeUrl($request->website);

        $slug = Str::slug($request->first_name . ' ' . $request->last_name);
        $originalSlug = $slug;
        $counter = 1;

        while (User::where('slug', $slug)->where('id', '!=', $user->id)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $user->slug = $slug;

        if ($request->hasFile('photo')) {
            $s3Service = app(S3Service::class);
            
            // Delete old photo from S3 if exists
            if ($user->photo) {
                $oldPath = $s3Service->extractPathFromUrl($user->photo);
                if ($oldPath && str_starts_with($oldPath, 'media/')) {
                    $s3Service->deleteMedia($oldPath);
                }
            }
            
            $uploadResult = $s3Service->uploadMedia($request->file('photo'), 'profile');
            $user->photo = $uploadResult['url']; // Store full S3 URL
        }
        $user->status = 'active';
        $user->save();

        return redirect()->back()->with('success', 'User details updated successfully!');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully!');
    }

    public function restoreUser($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();
        return redirect()->back()->with('success', 'User restored successfully!');
    }

    public function adminResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => now()]
        );

        try {
            Mail::to($request->email)->queue(new PasswordReset($token));
            
            Log::info('Admin password reset email queued successfully', ['email' => $request->email]);
        } catch (\Exception $e) {
            Log::error('Admin password reset email failed to queue', [
                'email' => $request->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withErrors(['email' => 'Failed to send email. Please try again later or contact support.']);
        }

        return back()->with('success', 'Password reset link sent to your email.');
    }
}

