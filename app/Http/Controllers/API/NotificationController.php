<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Notifications\Notification;
use App\Models\Users\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    /**
     * Get user notifications with filters
     * 
     * Query Parameters:
     * - per_page: Number of items per page (default: 20)
     * - page: Page number (default: 1)
     * - unread_only: Filter only unread notifications (true/false, default: false)
     * - type: Filter by notification type (optional)
     * - sort: Sort order (latest, oldest) (default: latest)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $userId = Auth::id();
            
            // Validate query parameters (lenient for mobile apps)
            $validator = Validator::make($request->all(), [
                'per_page' => 'sometimes|integer|min:1|max:100',
                'page' => 'sometimes|integer|min:1',
                'unread_only' => 'sometimes', // Accept any value, we'll parse it
                'type' => 'sometimes|string',
                'sort' => 'sometimes|in:latest,oldest',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $perPage = (int) $request->get('per_page', 20);
            // Handle string booleans from mobile/query params: "false", "true", "0", "1", etc.
            $unreadOnlyValue = $request->get('unread_only', false);
            $unreadOnly = filter_var($unreadOnlyValue, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            if ($unreadOnly === null) {
                // If filter_var returns null, try string comparison
                $unreadOnly = in_array(strtolower((string)$unreadOnlyValue), ['true', '1', 'yes'], true);
            }
            $type = $request->get('type');
            $sort = $request->get('sort', 'latest');

            // Build query
            $query = Notification::where('user_id', $userId);

            // Filter by unread status
            if ($unreadOnly === true) {
                $query->unread();
            }

            // Filter by type
            if ($type) {
                $query->ofType($type);
            }

            // Sort
            $sortOrder = $sort === 'oldest' ? 'asc' : 'desc';
            $query->orderBy('created_at', $sortOrder);

            // Paginate
            $notifications = $query->paginate($perPage);

            // Get unread count
            $unreadCount = Notification::where('user_id', $userId)->unread()->count();

            // Format notifications with user photos
            // Eager load users to avoid N+1 queries
            $userIds = [];
            foreach ($notifications->getCollection() as $notification) {
                $data = $notification->data ?? [];
                if (is_string($data)) {
                    $data = json_decode($data, true) ?? [];
                }
                
                // Collect all possible user IDs from notification data
                $userKeyMap = ['reactor_id', 'commenter_id', 'sharer_id', 'sender_id', 'replier_id', 'owner_id', 'follower_id', 'viewer_id'];
                foreach ($userKeyMap as $key) {
                    if (isset($data[$key]) && !empty($data[$key])) {
                        $userIds[] = (int) $data[$key];
                    }
                }
            }
            
            // Eager load all users at once
            $users = User::whereIn('id', array_unique($userIds))->get()->keyBy('id');
            
            // Format notifications with user photos - SIMPLIFIED
            $formattedArray = [];
            foreach ($notifications->getCollection() as $notification) {
                $formattedArray[] = $this->formatNotification($notification, $users);
            }

            // For mobile apps, always return the standard format
            // Check if legacy format is explicitly requested (for web backward compatibility)
            $useLegacyFormat = $request->get('legacy_format', false) === true || 
                              $request->get('legacy_format') === 'true' ||
                              $request->get('legacy_format') === '1';
            
            // Formatted array is already prepared above
            
            if ($useLegacyFormat) {
                // Legacy format (for web backward compatibility)
                // Manually build pagination response to ensure custom fields are included
                return response()->json([
                    'status' => true,
                    'message' => 'Notifications fetched successfully.',
                    'notifications' => [
                        'current_page' => $notifications->currentPage(),
                        'data' => $formattedArray,
                        'first_page_url' => $notifications->url(1),
                        'from' => $notifications->firstItem(),
                        'last_page' => $notifications->lastPage(),
                        'last_page_url' => $notifications->url($notifications->lastPage()),
                        'links' => $notifications->linkCollection()->toArray(),
                        'next_page_url' => $notifications->nextPageUrl(),
                        'path' => $notifications->path(),
                        'per_page' => $notifications->perPage(),
                        'prev_page_url' => $notifications->previousPageUrl(),
                        'to' => $notifications->lastItem(),
                        'total' => $notifications->total(),
                    ],
                    'unread_count' => $unreadCount,
                ]);
            }
            
            // Standard format (for mobile apps - default)
            return response()->json([
                'status' => true,
                'message' => 'Notifications fetched successfully.',
                'data' => [
                    'notifications' => $formattedArray,
                    'pagination' => [
                        'current_page' => $notifications->currentPage(),
                        'last_page' => $notifications->lastPage(),
                        'per_page' => $notifications->perPage(),
                        'total' => $notifications->total(),
                        'from' => $notifications->firstItem(),
                        'to' => $notifications->lastItem(),
                    ],
                    'unread_count' => $unreadCount,
                    'filters' => [
                        'unread_only' => $unreadOnly,
                        'type' => $type,
                        'sort' => $sort,
                    ],
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch notifications', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch notifications.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Format notification with user photo
     * 
     * @param Notification $notification
     * @param \Illuminate\Support\Collection|null $users Pre-loaded users collection (optional, for performance)
     * @return array
     */
    private function formatNotification($notification, $users = null)
    {
        // Get data - it's already cast to array by the model
        $data = $notification->data ?? [];
        
        // If data is a string (JSON), decode it
        if (is_string($data)) {
            $data = json_decode($data, true) ?? [];
        }
        
        $userPhoto = null;
        $userName = null;
        $userId = null;

        // Extract user ID from notification data based on type
        // Different notification types store user ID in different keys
        $userKeyMap = [
            'reactor_id',      // post_reaction, message_reaction
            'commenter_id',    // post_comment
            'sharer_id',       // post_share
            'sender_id',       // new_message
            'replier_id',      // comment_reply
            'owner_id',        // new_service, new_product
            'follower_id',     // new_follower
            'viewer_id',       // profile_view
        ];

        foreach ($userKeyMap as $key) {
            if (isset($data[$key]) && !empty($data[$key])) {
                $userId = (int) $data[$key];
                break;
            }
        }

        // Log for debugging if no user ID found
        if (!$userId) {
            Log::debug('No user ID found in notification data', [
                'notification_id' => $notification->id,
                'notification_type' => $notification->type,
                'data' => $data,
                'data_keys' => is_array($data) ? array_keys($data) : 'not_array',
            ]);
        }

        // Get user photo and name if user ID found
        if ($userId) {
            try {
                // Use pre-loaded users if available, otherwise load from database
                if ($users && $users->has($userId)) {
                    $user = $users->get($userId);
                } else {
                    $user = User::find($userId);
                }
                
                if ($user) {
                    $userPhoto = getImageUrl($user->photo);
                    $userName = trim($user->first_name . ' ' . $user->last_name);
                    
                    // Log for debugging
                    Log::debug('User photo loaded for notification', [
                        'notification_id' => $notification->id,
                        'user_id' => $userId,
                        'user_photo' => $userPhoto,
                        'has_photo_field' => !empty($user->photo),
                    ]);
                } else {
                    Log::debug('User not found for notification', [
                        'notification_id' => $notification->id,
                        'user_id' => $userId,
                        'notification_type' => $notification->type,
                    ]);
                }
            } catch (\Exception $e) {
                Log::warning('Failed to load user for notification', [
                    'notification_id' => $notification->id,
                    'user_id' => $userId,
                    'data' => $data,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Build notification array with all fields including user photo
        $notificationArray = [
            'id' => $notification->id,
            'user_id' => $notification->user_id,
            'type' => $notification->type,
            'title' => $notification->title,
            'message' => $notification->message,
            'data' => $data,
            'read_at' => $notification->read_at?->toIso8601String(),
            'created_at' => $notification->created_at?->toIso8601String(),
            'updated_at' => $notification->updated_at?->toIso8601String(),
            // User photo fields - ALWAYS included
            'user_photo' => $userPhoto,
            'user_name' => $userName,
            'trigger_user_id' => $userId,
        ];

        return $notificationArray;
    }

    /**
     * Get a single notification by ID
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $userId = Auth::id();

            $notification = Notification::where('id', $id)
                ->where('user_id', $userId)
                ->first();

            if (!$notification) {
                return response()->json([
                    'status' => false,
                    'message' => 'Notification not found.',
                ], 404);
            }

            // Format notification with user photo
            $formattedNotification = $this->formatNotification($notification, null);

            return response()->json([
                'status' => true,
                'message' => 'Notification fetched successfully.',
                'data' => [
                    'notification' => $formattedNotification,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch notification', [
                'user_id' => Auth::id(),
                'notification_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch notification.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Get unread notifications count
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function unreadCount()
    {
        try {
            $userId = Auth::id();

            $count = Notification::where('user_id', $userId)
                ->unread()
                ->count();

            return response()->json([
                'status' => true,
                'message' => 'Unread count fetched successfully.',
                'data' => [
                    'unread_count' => $count,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch unread count', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch unread count.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Mark a notification as read
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead($id)
    {
        try {
            $userId = Auth::id();

            $notification = Notification::where('id', $id)
                ->where('user_id', $userId)
                ->first();

            if (!$notification) {
                return response()->json([
                    'status' => false,
                    'message' => 'Notification not found.',
                ], 404);
            }

            // Check if already read
            if ($notification->isRead()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Notification is already marked as read.',
                    'data' => [
                        'notification' => $notification,
                    ],
                ]);
            }

            $notification->markAsRead();

            // Get updated unread count
            $unreadCount = Notification::where('user_id', $userId)->unread()->count();

            return response()->json([
                'status' => true,
                'message' => 'Notification marked as read.',
                'data' => [
                    'notification' => $notification->fresh(),
                    'unread_count' => $unreadCount,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to mark notification as read', [
                'user_id' => Auth::id(),
                'notification_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to mark notification as read.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Mark multiple notifications as read
     * 
     * This endpoint marks all unread notifications as read for the authenticated user.
     * No IDs need to be sent - it automatically marks all unread notifications.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function markMultipleAsRead(Request $request)
    {
        // Simply mark all unread notifications as read
        return $this->markAllAsRead();
    }

    /**
     * Mark all notifications as read
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAllAsRead()
    {
        try {
            $userId = Auth::id();

            $updated = Notification::where('user_id', $userId)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);

            return response()->json([
                'status' => true,
                'message' => 'All notifications marked as read.',
                'data' => [
                    'marked_count' => $updated,
                    'unread_count' => 0,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to mark all notifications as read', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to mark all notifications as read.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Delete a notification
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $userId = Auth::id();

            $notification = Notification::where('id', $id)
                ->where('user_id', $userId)
                ->first();

            if (!$notification) {
                return response()->json([
                    'status' => false,
                    'message' => 'Notification not found.',
                ], 404);
            }

            $notification->delete();

            // Get updated unread count
            $unreadCount = Notification::where('user_id', $userId)->unread()->count();

            return response()->json([
                'status' => true,
                'message' => 'Notification deleted successfully.',
                'data' => [
                    'unread_count' => $unreadCount,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to delete notification', [
                'user_id' => Auth::id(),
                'notification_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to delete notification.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Delete multiple notifications
     * 
     * Request Body:
     * - ids: Array of notification IDs (required)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyMultiple(Request $request)
    {
        try {
            $userId = Auth::id();

            $validator = Validator::make($request->all(), [
                'ids' => 'required|array|min:1',
                'ids.*' => 'required|integer|exists:notifications,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $ids = $request->get('ids');

            // Verify all notifications belong to the user
            $notifications = Notification::where('user_id', $userId)
                ->whereIn('id', $ids)
                ->get();

            if ($notifications->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No valid notifications found.',
                ], 404);
            }

            $deleted = Notification::where('user_id', $userId)
                ->whereIn('id', $ids)
                ->delete();

            // Get updated unread count
            $unreadCount = Notification::where('user_id', $userId)->unread()->count();

            return response()->json([
                'status' => true,
                'message' => "{$deleted} notification(s) deleted successfully.",
                'data' => [
                    'deleted_count' => $deleted,
                    'unread_count' => $unreadCount,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to delete multiple notifications', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to delete notifications.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Delete all notifications
     * 
     * Query Parameters:
     * - read_only: Delete only read notifications (true/false, default: false)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyAll(Request $request)
    {
        try {
            $userId = Auth::id();

            $readOnly = filter_var($request->get('read_only', false), FILTER_VALIDATE_BOOLEAN);

            $query = Notification::where('user_id', $userId);

            if ($readOnly === true) {
                $query->read();
            }

            $deleted = $query->delete();

            // Get updated unread count
            $unreadCount = Notification::where('user_id', $userId)->unread()->count();

            return response()->json([
                'status' => true,
                'message' => "{$deleted} notification(s) deleted successfully.",
                'data' => [
                    'deleted_count' => $deleted,
                    'unread_count' => $unreadCount,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to delete all notifications', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to delete notifications.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Get notification types (for filtering)
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTypes()
    {
        try {
            $types = [
                Notification::TYPE_POST_REACTION => 'Post Reaction',
                Notification::TYPE_POST_COMMENT => 'Post Comment',
                Notification::TYPE_COMMENT_REPLY => 'Comment Reply',
                Notification::TYPE_POST_SHARE => 'Post Share',
                Notification::TYPE_NEW_MESSAGE => 'New Message',
                Notification::TYPE_NEW_SERVICE => 'New Service',
                Notification::TYPE_NEW_PRODUCT => 'New Product',
                Notification::TYPE_PROFILE_VIEW => 'Profile View',
                Notification::TYPE_NEW_FOLLOWER => 'New Follower',
                Notification::TYPE_SUBSCRIPTION_EVENT => 'Subscription Event',
                Notification::TYPE_ADMIN_NOTIFICATION => 'Admin Notification',
                // Opportunity notification types
                Notification::TYPE_OPPORTUNITY_NEW_PROPOSAL => 'New Proposal',
                Notification::TYPE_PROPOSAL_SHORTLISTED => 'Proposal Shortlisted',
                Notification::TYPE_PROPOSAL_ACCEPTED => 'Proposal Accepted',
                Notification::TYPE_PROPOSAL_REJECTED => 'Proposal Rejected',
                Notification::TYPE_PROPOSAL_WITHDRAWN => 'Proposal Withdrawn',
                Notification::TYPE_OPPORTUNITY_EXPIRED => 'Opportunity Expired',
                Notification::TYPE_OPPORTUNITY_DEADLINE_REMINDER => 'Deadline Reminder',
            ];

            return response()->json([
                'status' => true,
                'message' => 'Notification types fetched successfully.',
                'data' => [
                    'types' => $types,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch notification types', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch notification types.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }
}
