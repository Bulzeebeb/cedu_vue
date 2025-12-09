<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    /**
     * Get all notifications for a user or admin
     */
    public function index(Request $request)
    {
        // Get user_id or admin_id from query params
        $userId = $request->query('user_id');
        $adminId = $request->query('admin_id');

        if (!$userId && !$adminId) {
            Log::warning('Notification fetch attempt without user_id or admin_id');
            return response()->json([]);
        }

        $query = Notification::orderBy('created_at', 'desc');

        if ($userId) {
            $query->where('user_id', $userId);
        } elseif ($adminId) {
            $query->where('admin_id', $adminId);
        }

        $notifications = $query->get();

        return response()->json($notifications);
    }

    /**
     * Create a new notification
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|integer|exists:user_clients,id',
            'admin_id' => 'nullable|integer|exists:admins,id',
            'booking_id' => 'required|integer|exists:bookings,id',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'nullable|string|max:50',
            'read' => 'nullable|boolean'
        ]);

        // Ensure either user_id or admin_id is provided
        if (!$validated['user_id'] && !$validated['admin_id']) {
            return response()->json(['error' => 'Either user_id or admin_id must be provided'], 422);
        }

        try {
            $notification = Notification::create([
                'user_id' => $validated['user_id'] ?? null,
                'admin_id' => $validated['admin_id'] ?? null,
                'booking_id' => $validated['booking_id'],
                'title' => $validated['title'],
                'message' => $validated['message'],
                'type' => $validated['type'] ?? 'booking_status',
                'read' => $validated['read'] ?? false
            ]);

            Log::info('Notification created successfully', [
                'notification_id' => $notification->id,
                'user_id' => $notification->user_id,
                'admin_id' => $notification->admin_id,
                'booking_id' => $notification->booking_id,
                'title' => $notification->title
            ]);

            return response()->json($notification, 201);
        } catch (\Exception $e) {
            Log::error('Notification creation failed', [
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);

        // Check if user owns this notification (for userclient guard)
        if ($notification->user_id && $notification->user_id !== Auth::guard('userclient')->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Check if admin owns this notification (for admin guard)
        if ($notification->admin_id && $notification->admin_id !== Auth::guard('admin')->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $notification->update(['read' => true]);

        return response()->json($notification);
    }

    /**
     * Mark all notifications as read for the user or admin
     */
    public function markAllAsRead(Request $request)
    {
        $userId = $request->input('user_id');
        $adminId = $request->input('admin_id');

        if (!$userId && !$adminId) {
            // Try to get from authenticated user
            $userId = Auth::guard('userclient')->id();
            $adminId = Auth::guard('admin')->id();
        }

        if (!$userId && !$adminId) {
            return response()->json(['error' => 'User or Admin not found'], 422);
        }

        $query = Notification::where('read', false);

        if ($userId) {
            $query->where('user_id', $userId);
        } elseif ($adminId) {
            $query->where('admin_id', $adminId);
        }

        $query->update(['read' => true]);

        return response()->json(['message' => 'All notifications marked as read']);
    }

    /**
     * Delete a notification
     */
    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        
        if ($notification->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $notification->delete();

        return response()->json(['message' => 'Notification deleted']);
    }
}
