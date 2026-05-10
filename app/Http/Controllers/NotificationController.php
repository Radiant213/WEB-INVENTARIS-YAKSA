<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $query = Notification::latest();
        
        if (Auth::check() && Auth::user()->role === 'user') {
            $query->whereNotIn('type', ['approval_request', 'user_created']);
        }

        $notifications = $query->take(15)->get();

        $unreadQuery = Notification::whereNull('read_at');
        if (Auth::check() && Auth::user()->role === 'user') {
            $unreadQuery->whereNotIn('type', ['approval_request', 'user_created']);
        }
        $unreadCount = $unreadQuery->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    // Mark satu notif sebagai dibaca & redirect ke link
    public function markRead(Notification $notification)
    {
        $notification->update(['read_at' => now()]);

        if ($notification->link) {
            return redirect($notification->link);
        }

        return back();
    }

    // Mark semua sebagai dibaca
    public function markAllRead()
    {
        $query = Notification::whereNull('read_at');
        if (Auth::check() && Auth::user()->role === 'user') {
            $query->whereNotIn('type', ['approval_request', 'user_created']);
        }
        $query->update(['read_at' => now()]);
        
        return response()->json(['success' => true]);
    }
}
