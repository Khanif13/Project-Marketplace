<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Belum login
        if (! $request->user()) {
            return redirect()->route('login')
                ->with('error', 'Silakan masuk terlebih dahulu.');
        }

        $user = $request->user();

        // Cek apakah role user ada di daftar role yang diizinkan
        if (! in_array($user->role, $roles)) {
            abort(403, 'Akses tidak diizinkan.');
        }

        // Khusus seller: wajib sudah terverifikasi
        if (in_array('seller', $roles) && $user->role === 'seller') {
            if ($user->seller_status !== 'verified') {
                return redirect()->route('seller.status')
                    ->with('error', 'Akun penjual kamu belum terverifikasi.');
            }
        }

        return $next($request);
    }
}
