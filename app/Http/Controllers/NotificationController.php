<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->paginate(10);

        $guard = null;
        if (Auth::guard('admin')->check()) {
            $guard = 'admin';
        } elseif (Auth::guard('assistant')->check()) {
            $guard = 'assistant';
        } elseif (Auth::guard('super_admin')->check()) {
            $guard = 'super_admin';
        } elseif (Auth::guard('voter')->check()) {
            $guard = 'voter';
        }

        if (!$guard) {
            abort(403, 'Unauthorized');
        }

        return view($guard . '.notifications.index', compact('notifications'));
    }

    public function markAsRead(DatabaseNotification $notification)
    {
        $notification->markAsRead();
        return back()->with('success', 'Notification marked as read.');
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', 'All notifications marked as read.');
    }

    public function destroy(DatabaseNotification $notification)
    {
        $notification->delete();
        return back()->with('success', 'Notification deleted.');
    }

    public function deleteAllRead()
    {
        Auth::user()->readNotifications()->delete();
        return back()->with('success', 'All read notifications deleted.');
    }
}