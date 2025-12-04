<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    /**
     * Get all notifications for a user
     */
    public function index(Request $request)
    {
        // Get user_id from query param or authenticated user
        $userId = $request->query('user_id') ?? Auth::id();
        
        if (!$userId) {
            Log::warning('Notification fetch attempt without user_id');
            return response()->json([]);
        }

        $notifications = Notification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($notifications);
    }

    /**
     * Create a new notification
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'booking_id' => 'required|integer|exists:bookings,id',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'nullable|string|max:50',
            'read' => 'nullable|boolean'
        ]);

        try {
            $notification = Notification::create([
                'user_id' => $validated['user_id'],
                'booking_id' => $validated['booking_id'],
                'title' => $validated['title'],
                'message' => $validated['message'],
                'type' => $validated['type'] ?? 'booking_status',
                'read' => $validated['read'] ?? false
            ]);

            Log::info('Notification created successfully', [
                'notification_id' => $notification->id,
                'user_id' => $notification->user_id,
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
        
        // Check if user owns this notification or is authenticated
        if ($notification->user_id !== Auth::id() && Auth::id() !== null) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $notification->update(['read' => true]);

        return response()->json($notification);
    }

    /**
     * Mark all notifications as read for the user
     */
    public function markAllAsRead(Request $request)
    {
        $userId = $request->query('user_id') ?? Auth::id();
        
        if (!$userId) {
            return response()->json(['error' => 'User not found'], 422);
        }

        Notification::where('user_id', $userId)
            ->where('read', false)
            ->update(['read' => true]);

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
