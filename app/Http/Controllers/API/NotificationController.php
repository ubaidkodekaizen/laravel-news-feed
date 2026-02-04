<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Notification;
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
            
            // Validate query parameters
            $validator = Validator::make($request->all(), [
                'per_page' => 'sometimes|integer|min:1|max:100',
                'page' => 'sometimes|integer|min:1',
                'unread_only' => 'sometimes|boolean',
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

            $perPage = $request->get('per_page', 20);
            $unreadOnly = filter_var($request->get('unread_only', false), FILTER_VALIDATE_BOOLEAN);
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

            // Check if new format is requested (defaults to legacy for backward compatibility)
            // Legacy format: { status, message, notifications: Paginator, unread_count }
            // New format: { status, message, data: { notifications: [], pagination: {}, unread_count, filters } }
            $useNewFormat = $request->get('new_format', false) || 
                          $request->has('type') || 
                          $request->has('sort') ||
                          $request->routeIs('api.notifications.index');
            
            if ($useNewFormat) {
                // New format (with enhanced data structure)
                return response()->json([
                    'status' => true,
                    'message' => 'Notifications fetched successfully.',
                    'data' => [
                        'notifications' => $notifications->items(),
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
            }
            
            // Legacy format (for backward compatibility with existing frontend)
            return response()->json([
                'status' => true,
                'message' => 'Notifications fetched successfully.',
                'notifications' => $notifications,
                'unread_count' => $unreadCount,
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

            return response()->json([
                'status' => true,
                'message' => 'Notification fetched successfully.',
                'data' => [
                    'notification' => $notification,
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
     * Request Body:
     * - ids: Array of notification IDs (optional, if not provided, marks all as read)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function markMultipleAsRead(Request $request)
    {
        try {
            $userId = Auth::id();

            $validator = Validator::make($request->all(), [
                'ids' => 'sometimes|array',
                'ids.*' => 'required|integer|exists:notifications,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $ids = $request->get('ids', []);

            if (empty($ids)) {
                // Mark all as read if no IDs provided
                return $this->markAllAsRead();
            }

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

            // Mark as read
            $updated = Notification::where('user_id', $userId)
                ->whereIn('id', $ids)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);

            // Get updated unread count
            $unreadCount = Notification::where('user_id', $userId)->unread()->count();

            return response()->json([
                'status' => true,
                'message' => "{$updated} notification(s) marked as read.",
                'data' => [
                    'marked_count' => $updated,
                    'unread_count' => $unreadCount,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to mark multiple notifications as read', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to mark notifications as read.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
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
