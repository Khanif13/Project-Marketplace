<?php

namespace App\Http\Controllers;

use App\Models\SellerVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerController extends Controller
{
    // -------------------------------------------------------
    // Form pendaftaran seller
    // -------------------------------------------------------
    public function registerForm()
    {
        $user = Auth::user();

        // Sudah seller — redirect ke dashboard
        if ($user->isSeller()) {
            return redirect()->route('dashboard');
        }

        // Sudah pending — redirect ke halaman status
        if ($user->isPendingSeller()) {
            return redirect()->route('seller.status');
        }

        return view('seller.register');
    }

    // -------------------------------------------------------
    // Submit pendaftaran seller
    // -------------------------------------------------------
    public function submitRegister(Request $request)
    {
        $user = Auth::user();

        if ($user->isSeller() || $user->isPendingSeller()) {
            return redirect()->route('seller.status');
        }

        $validated = $request->validate([
            'store_name' => 'required|string|max:100',
            'store_address' => 'required|string|max:255',
            'store_wa' => 'required|string|max:20|regex:/^[0-9+\-\s]+$/',
            'ktp_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'store_wa.regex' => 'Format nomor WhatsApp tidak valid.',
        ]);

        // Upload foto KTP jika ada
        $ktpPath = null;
        if ($request->hasFile('ktp_photo')) {
            $ktpPath = $request->file('ktp_photo')->store('verifications', 'public');
        }

        // Update data user
        $user->update([
            'store_name' => $validated['store_name'],
            'store_address' => $validated['store_address'],
            'store_wa' => $validated['store_wa'],
            'seller_status' => 'pending',
        ]);

        // Buat record verifikasi
        SellerVerification::create([
            'user_id' => $user->id,
            'ktp_photo' => $ktpPath,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('seller.status')
            ->with('success', 'Pendaftaran berhasil! Kami akan memverifikasi akun kamu dalam 1x24 jam.');
    }

    // -------------------------------------------------------
    // Halaman status verifikasi seller
    // -------------------------------------------------------
    public function status()
    {
        $user = Auth::user();
        $verification = $user->sellerVerification;

        // Kalau belum pernah daftar sama sekali
        if (! $verification && ! $user->isPendingSeller() && ! $user->isSeller()) {
            return redirect()->route('seller.register');
        }

        return view('seller.status', compact('user', 'verification'));
    }
}
