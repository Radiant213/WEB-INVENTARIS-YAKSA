<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // API: Ambil notifikasi terbaru (JSON)
    public function index()
    {
        $notifications = Notification::latest()
            ->take(15)
            ->get();

        $unreadCount = Notification::whereNull('read_at')->count();

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
        Notification::whereNull('read_at')->update(['read_at' => now()]);
        return response()->json(['success' => true]);
    }
}
