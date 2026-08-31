<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Statistik ringkas
        $stats = [
            'total' => $user->listings()->count(),
            'active' => $user->listings()->where('status', 'active')->count(),
            'empty' => $user->listings()->where('status', 'empty')->count(),
            'inactive' => $user->listings()->where('status', 'inactive')->count(),
            'views' => $user->listings()->sum('view_count'),
        ];

        // Iklan seller dengan paginasi
        $listings = $user->listings()
            ->with('firstImage', 'category')
            ->latest()
            ->paginate(10);

        // Notifikasi belum dibaca
        $unreadNotifications = $user->notifications()
            ->unread()
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'listings', 'unreadNotifications'));
    }
}
