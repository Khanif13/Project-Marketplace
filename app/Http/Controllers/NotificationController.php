<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // -------------------------------------------------------
    // Halaman semua notifikasi
    // -------------------------------------------------------
    public function index()
    {
        $notifications = Auth::user()
            ->notifications()
            ->latest()
            ->paginate(20);

        // Auto mark all as read saat buka halaman
        Auth::user()
            ->notifications()
            ->unread()
            ->update(['read_at' => now()]);

        return view('notifications.index', compact('notifications'));
    }

    // -------------------------------------------------------
    // Mark satu notifikasi sebagai sudah dibaca
    // -------------------------------------------------------
    public function markRead(Notification $notification)
    {
        abort_if($notification->user_id !== Auth::id(), 403);

        $notification->markAsRead();

        // Redirect ke URL notifikasi jika ada
        if ($notification->url) {
            return redirect($notification->url);
        }

        return back();
    }

    // -------------------------------------------------------
    // Mark semua sebagai sudah dibaca
    // -------------------------------------------------------
    public function readAll()
    {
        Auth::user()
            ->notifications()
            ->unread()
            ->update(['read_at' => now()]);

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }
}
