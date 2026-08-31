<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    // Halaman notice "cek email kamu"
    public function notice()
    {
        if (auth()->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('home'));
        }

        return view('auth.verify-email');
    }

    // Proses klik link verifikasi dari email
    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return redirect()->route('home')
            ->with('success', 'Email berhasil diverifikasi! Selamat datang di Marasa.id');
    }

    // Kirim ulang email verifikasi
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('home');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Link verifikasi berhasil dikirim ulang. Cek inbox atau folder spam kamu.');
    }
}
