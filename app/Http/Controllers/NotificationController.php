<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Toggle read/unread status for a notification
     */
    public function toggleRead(Notification $notification)
    {
        // ensure the notification belongs to the authenticated user
        if ($notification->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $notification->read = !$notification->read;
        $notification->save();

        return back()->with('success', 'Notification status updated');
    }

    /**
     * Mark all notifications for the current user as read
     */
    public function markAllRead()
    {
        Notification::where('user_id', Auth::id())->update(['read' => true]);

        return back()->with('success', 'All notifications marked as read');
    }

    /**
     * Delete a notification
     */
    public function destroy(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $notification->delete();

        return back()->with('success', 'Notification deleted');
    }
}
