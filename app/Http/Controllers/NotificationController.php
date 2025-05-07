<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markAsRead(Request $request)
    {
        if ($request->has('notification_id')) {
            $notification = auth()->user()->notifications()->findOrFail($request->notification_id);
            $notification->markAsRead();
        }

        return back();
    }

    public function allNotifications()
    {

        $notifications = auth()->user()->notifications()->paginate(10);
        return view('notifications.all', compact('notifications'));
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }
}
