<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\User;
use App\Models\Feed\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    /**
     * Report a user
     */
    public function reportUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'reason' => 'required|string|in:spam,harassment,inappropriate_content,fake_account,other',
            'description' => 'nullable|string|max:1000',
        ]);

        $reporterId = Auth::id();
        $reportedUserId = $request->user_id;

        if ($reporterId === $reportedUserId) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot report yourself',
            ], 400);
        }

        $existing = Report::where('reporter_id', $reporterId)
            ->where('reportable_type', User::class)
            ->where('reportable_id', $reportedUserId)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'You have already reported this user',
            ], 409);
        }

        $report = Report::create([
            'reporter_id' => $reporterId,
            'reportable_type' => User::class,
            'reportable_id' => $reportedUserId,
            'reason' => $request->reason,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        Log::info('User reported', [
            'reporter_id' => $reporterId,
            'reported_user_id' => $reportedUserId,
            'reason' => $request->reason,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User reported successfully. Our team will review this report.',
            'report' => [
                'id' => $report->id,
                'reason' => $report->reason,
                'status' => $report->status,
                'created_at' => $report->created_at,
            ],
        ]);
    }

    /**
     * Report a post
     */
    public function reportPost(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'reason' => 'required|string|in:spam,harassment,inappropriate_content,violence,hate_speech,other',
            'description' => 'nullable|string|max:1000',
        ]);

        $reporterId = Auth::id();
        $postId = $request->post_id;

        $post = Post::where('id', $postId)
            ->where('status', 'active')
            ->whereNull('deleted_at')
            ->first();

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found or has been deleted',
            ], 404);
        }

        $existing = Report::where('reporter_id', $reporterId)
            ->where('reportable_type', Post::class)
            ->where('reportable_id', $postId)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'You have already reported this post',
            ], 409);
        }

        $report = Report::create([
            'reporter_id' => $reporterId,
            'reportable_type' => Post::class,
            'reportable_id' => $postId,
            'reason' => $request->reason,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        Log::info('Post reported', [
            'reporter_id' => $reporterId,
            'post_id' => $postId,
            'reason' => $request->reason,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Post reported successfully. Our team will review this report.',
            'report' => [
                'id' => $report->id,
                'reason' => $report->reason,
                'status' => $report->status,
                'created_at' => $report->created_at,
            ],
        ]);
    }
}
