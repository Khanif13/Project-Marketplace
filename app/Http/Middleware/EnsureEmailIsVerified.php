<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->hasVerifiedEmail()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Email belum diverifikasi.'], 403);
            }

            return Redirect::route('verification.notice')
                ->with('error', 'Kamu perlu memverifikasi email sebelum melanjutkan.');
        }

        return $next($request);
    }
}
