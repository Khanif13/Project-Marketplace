<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\SellerVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminVerificationController extends Controller
{
    public function index(Request $request)
    {
        $query = SellerVerification::with('user')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $verifications = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => SellerVerification::count(),
            'pending' => SellerVerification::where('status', 'pending')->count(),
            'approved' => SellerVerification::where('status', 'approved')->count(),
            'rejected' => SellerVerification::where('status', 'rejected')->count(),
        ];

        return view('admin.verifications.index', compact('verifications', 'stats'));
    }

    public function approve(SellerVerification $verification)
    {
        // Update verifikasi
        $verification->update([
            'status' => 'approved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        // Update role & status user
        $verification->user->update([
            'role' => 'seller',
            'seller_status' => 'verified',
        ]);

        // Kirim notifikasi ke user
        Notification::create([
            'user_id' => $verification->user_id,
            'type' => 'seller_approved',
            'message' => 'Selamat! Akun penjual kamu sudah diverifikasi. Mulai pasang iklan sekarang.',
            'url' => route('listings.create'),
        ]);

        return back()->with('success', "Akun {$verification->user->name} berhasil diverifikasi.");
    }

    public function reject(Request $request, SellerVerification $verification)
    {
        $request->validate([
            'notes' => 'required|string|max:500',
        ]);

        $verification->update([
            'status' => 'rejected',
            'notes' => $request->notes,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        $verification->user->update([
            'seller_status' => 'rejected',
        ]);

        // Kirim notifikasi ke user
        Notification::create([
            'user_id' => $verification->user_id,
            'type' => 'seller_rejected',
            'message' => 'Maaf, pendaftaran penjual kamu tidak disetujui. Cek halaman status untuk detailnya.',
            'url' => route('seller.status'),
        ]);

        return back()->with('success', "Pendaftaran {$verification->user->name} ditolak.");
    }
}
