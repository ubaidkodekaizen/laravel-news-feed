<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chat\BlockedUser;
use App\Models\Users\User;
use Illuminate\Http\JsonResponse;

class BlockController extends Controller
{
    /**
     * Block a user
     */
    public function blockUser(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $blockerId = auth()->id();
        $blockedId = $request->user_id;

        // Prevent self-blocking
        if ($blockerId === $blockedId) {
            return response()->json([
                'error' => 'You cannot block yourself'
            ], 400);
        }

        // Check if already blocked
        if (BlockedUser::isBlocked($blockerId, $blockedId)) {
            return response()->json([
                'message' => 'User is already blocked'
            ], 200);
        }

        // Create block
        BlockedUser::create([
            'blocker_id' => $blockerId,
            'blocked_id' => $blockedId,
        ]);

        \Log::info('User blocked', [
            'blocker_id' => $blockerId,
            'blocked_id' => $blockedId
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User blocked successfully'
        ]);
    }

    /**
     * Unblock a user
     */
    public function unblockUser(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $blockerId = auth()->id();
        $blockedId = $request->user_id;

        // Find and delete the block
        $deleted = BlockedUser::where('blocker_id', $blockerId)
            ->where('blocked_id', $blockedId)
            ->delete();

        if (!$deleted) {
            return response()->json([
                'error' => 'User is not blocked'
            ], 404);
        }

        \Log::info('User unblocked', [
            'blocker_id' => $blockerId,
            'blocked_id' => $blockedId
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User unblocked successfully'
        ]);
    }

    /**
     * Check if a user is blocked
     */
    public function checkBlockStatus(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $currentUserId = auth()->id();
        $targetUserId = $request->user_id;

        $isBlocked = BlockedUser::isBlocked($currentUserId, $targetUserId);
        $isBlockedBy = BlockedUser::isBlocked($targetUserId, $currentUserId);

        return response()->json([
            'is_blocked' => $isBlocked, // Current user blocked target user
            'is_blocked_by' => $isBlockedBy, // Current user is blocked by target user
            'can_message' => !$isBlocked && !$isBlockedBy,
        ]);
    }

    /**
     * Get list of blocked users
     */
    public function getBlockedUsers(): JsonResponse
    {
        $blockedUsers = auth()->user()
            ->blockedUsers()
            ->select('users.id', 'users.first_name', 'users.last_name', 'users.email', 'users.photo', 'users.slug')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'photo' => getImageUrl($user->photo),
                    'slug' => $user->slug,
                    'user_has_photo' => !empty($user->photo),
                    'user_initials' => strtoupper(
                        substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)
                    ),
                ];
            });

        return response()->json($blockedUsers);
    }
}
