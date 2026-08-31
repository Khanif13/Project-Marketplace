@extends('admin.layouts.app')

@section('title', 'Manajemen Pengguna')
@section('page-title', 'Manajemen Pengguna')

@section('content')

    {{-- Stats --}}
    <div class="grid grid-cols-4 gap-4 mb-6">
        @foreach ([['label' => 'Total Pengguna', 'value' => $stats['total'], 'icon' => 'ti-users', 'color' => 'text-[#7D1A2E]'], ['label' => 'Pembeli', 'value' => $stats['buyers'], 'icon' => 'ti-user', 'color' => 'text-blue-500'], ['label' => 'Penjual Aktif', 'value' => $stats['sellers'], 'icon' => 'ti-store', 'color' => 'text-green-600'], ['label' => 'Pending Seller', 'value' => $stats['pending'], 'icon' => 'ti-clock', 'color' => 'text-orange-500']] as $s)
            <div class="bg-white border border-[#ede5e6] rounded-2xl p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs text-[#aaa]">{{ $s['label'] }}</span>
                    <i class="ti {{ $s['icon'] }} {{ $s['color'] }} text-base"></i>
                </div>
                <div class="text-2xl font-black text-[#1a0a0e]">{{ $s['value'] }}</div>
            </div>
        @endforeach
    </div>

    {{-- Filter & Search --}}
    <div class="bg-white border border-[#ede5e6] rounded-2xl p-5 mb-5">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex items-center gap-3">
            <div
                class="flex-1 flex border border-[#ede5e6] rounded-lg overflow-hidden h-9 focus-within:border-[#7D1A2E] transition-colors">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau email..."
                    class="flex-1 px-4 text-sm text-[#333] outline-none bg-transparent">
                <button type="submit" class="bg-[#7D1A2E] px-4 text-white text-sm">
                    <i class="ti ti-search"></i>
                </button>
            </div>
            <select name="role" onchange="this.form.submit()"
                class="border border-[#ede5e6] rounded-lg px-3 h-9 text-sm text-[#555] outline-none focus:border-[#7D1A2E] bg-white">
                <option value="">Semua Role</option>
                <option value="buyer" {{ request('role') === 'buyer' ? 'selected' : '' }}>Pembeli</option>
                <option value="seller" {{ request('role') === 'seller' ? 'selected' : '' }}>Penjual</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            <select name="seller_status" onchange="this.form.submit()"
                class="border border-[#ede5e6] rounded-lg px-3 h-9 text-sm text-[#555] outline-none focus:border-[#7D1A2E] bg-white">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('seller_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="verified" {{ request('seller_status') === 'verified' ? 'selected' : '' }}>Verified</option>
                <option value="rejected" {{ request('seller_status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            @if (request()->anyFilled(['q', 'role', 'seller_status']))
                <a href="{{ route('admin.users.index') }}"
                    class="text-xs text-[#7D1A2E] font-semibold whitespace-nowrap">Reset</a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white border border-[#ede5e6] rounded-2xl overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-[#ede5e6] bg-[#faf5f6]">
                    <th class="text-left px-5 py-3 text-xs font-bold text-[#aaa] uppercase tracking-wider">Pengguna</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-[#aaa] uppercase tracking-wider">Role</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-[#aaa] uppercase tracking-wider">Status Seller
                    </th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-[#aaa] uppercase tracking-wider">Iklan</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-[#aaa] uppercase tracking-wider">Bergabung</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#f5f0ef]">
                @forelse($users as $user)
                    <tr class="hover:bg-[#faf5f6] transition-colors">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-[#7D1A2E]/10 flex items-center justify-center text-[#7D1A2E] font-bold text-xs shrink-0">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-[#1a0a0e]">{{ $user->name }}</p>
                                    <p class="text-xs text-[#aaa]">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3.5">
                            <span
                                class="text-xs font-semibold px-2 py-0.5 rounded-full
                            {{ $user->role === 'admin'
                                ? 'bg-purple-50 text-purple-700 border border-purple-200'
                                : ($user->role === 'seller'
                                    ? 'bg-green-50 text-green-700 border border-green-200'
                                    : 'bg-blue-50 text-blue-700 border border-blue-200') }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5">
                            @if ($user->seller_status !== 'none')
                                <span
                                    class="text-xs font-semibold px-2 py-0.5 rounded-full
                                {{ $user->seller_status === 'verified'
                                    ? 'bg-green-50 text-green-700 border border-green-200'
                                    : ($user->seller_status === 'pending'
                                        ? 'bg-yellow-50 text-yellow-700 border border-yellow-200'
                                        : 'bg-red-50 text-red-700 border border-red-200') }}">
                                    {{ ucfirst($user->seller_status) }}
                                </span>
                            @else
                                <span class="text-xs text-[#ccc]">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-[#555]">{{ $user->listings_count }}</td>
                        <td class="px-5 py-3.5 text-xs text-[#aaa]">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-2 justify-end">
                                <a href="{{ route('admin.users.show', $user->id) }}"
                                    class="w-7 h-7 flex items-center justify-center border border-[#ede5e6] rounded-lg text-[#888] hover:border-[#7D1A2E] hover:text-[#7D1A2E] transition-all text-xs">
                                    <i class="ti ti-eye"></i>
                                </a>
                                @if (!$user->isAdmin())
                                    <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}"
                                        onsubmit="return confirm('Hapus akun {{ $user->name }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="w-7 h-7 flex items-center justify-center border border-[#ede5e6] rounded-lg text-[#888] hover:border-red-400 hover:text-red-400 transition-all text-xs">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-sm text-[#aaa]">
                            Tidak ada pengguna ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if ($users->hasPages())
            <div class="px-5 py-4 border-t border-[#ede5e6]">{{ $users->links() }}</div>
        @endif
    </div>

@endsection
