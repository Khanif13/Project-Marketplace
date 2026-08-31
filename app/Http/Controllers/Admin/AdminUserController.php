<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withCount('listings')->latest();

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->q}%")
                    ->orWhere('email', 'like', "%{$request->q}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('seller_status')) {
            $query->where('seller_status', $request->seller_status);
        }

        $users = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => User::count(),
            'buyers' => User::where('role', 'buyer')->count(),
            'sellers' => User::where('role', 'seller')->where('seller_status', 'verified')->count(),
            'pending' => User::where('seller_status', 'pending')->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    public function show(User $user)
    {
        $user->load(['listings' => fn ($q) => $q->latest()->take(5), 'sellerVerification']);

        return view('admin.users.show', compact('user'));
    }

    public function destroy(User $user)
    {
        // Jangan hapus admin
        abort_if($user->isAdmin(), 403, 'Tidak bisa menghapus akun admin.');

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', "Akun {$user->name} berhasil dihapus.");
    }
}
