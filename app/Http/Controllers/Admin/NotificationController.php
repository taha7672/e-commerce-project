<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function fetchNotification()
    {
        $notifications = Notification::with(['user', 'order'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $unreadNotifications = Notification::with(['user', 'order'])
            ->orderBy('created_at', 'desc')
            ->where('status', 0)
            ->paginate(10);
        return view('admin.notifications.index', compact('notifications', 'unreadNotifications'));

    }

    public function readNotifications()
    {
        Notification::where('status', '0')->update(['status' => '1']);
        return redirect()->route('admin.showNotifications')->with('success', __('messages.notifications_read'));
    }

}
