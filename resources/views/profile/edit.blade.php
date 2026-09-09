@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
    <div class="bg-[#f5f0ef] min-h-screen">
        <div class="max-w-2xl mx-auto px-6 py-10">

            <div class="mb-8">
                <h1 class="text-xl font-black text-[#1a0a0e]">Profil Saya</h1>
                <p class="text-sm text-[#aaa] mt-1">Kelola informasi akun kamu</p>
            </div>

            {{-- Info Akun --}}
            <div class="bg-white border border-[#ede5e6] rounded-2xl p-6 mb-5">
                <h2 class="text-sm font-bold text-[#1a0a0e] mb-5 flex items-center gap-2">
                    <span class="w-1 h-4 bg-[#7D1A2E] rounded-full inline-block"></span>
                    Informasi Akun
                </h2>

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PATCH')

                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-[#555] mb-1.5">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                            class="w-full border border-[#ede5e6] rounded-xl px-4 py-2.5 text-sm text-[#1a0a0e] outline-none focus:border-[#7D1A2E] transition-colors @error('name') border-red-400 @enderror">
                        @error('name')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-[#555] mb-1.5">Email</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                            class="w-full border border-[#ede5e6] rounded-xl px-4 py-2.5 text-sm text-[#1a0a0e] outline-none focus:border-[#7D1A2E] transition-colors @error('email') border-red-400 @enderror">
                        @error('email')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-xs font-semibold text-[#555] mb-1.5">Nomor HP</label>
                        <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}"
                            placeholder="08xxxxxxxxxx"
                            class="w-full border border-[#ede5e6] rounded-xl px-4 py-2.5 text-sm text-[#1a0a0e] outline-none focus:border-[#7D1A2E] transition-colors">
                    </div>

                    @if (session('status') === 'profile-updated')
                        <div
                            class="bg-green-50 border border-green-200 text-green-700 text-xs px-4 py-2.5 rounded-lg mb-4 flex items-center gap-2">
                            <i class="ti ti-circle-check"></i> Profil berhasil diperbarui.
                        </div>
                    @endif

                    <button type="submit"
                        class="bg-[#7D1A2E] hover:bg-[#9B2035] text-white font-bold text-sm px-6 py-2.5 rounded-xl transition-colors">
                        Simpan Perubahan
                    </button>
                </form>
            </div>

            {{-- Ganti Password --}}
            <div class="bg-white border border-[#ede5e6] rounded-2xl p-6 mb-5">
                <h2 class="text-sm font-bold text-[#1a0a0e] mb-5 flex items-center gap-2">
                    <span class="w-1 h-4 bg-[#7D1A2E] rounded-full inline-block"></span>
                    Ganti Password
                </h2>

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-[#555] mb-1.5">Password Saat Ini</label>
                        <input type="password" name="current_password"
                            class="w-full border border-[#ede5e6] rounded-xl px-4 py-2.5 text-sm outline-none focus:border-[#7D1A2E] transition-colors @error('current_password', 'updatePassword') border-red-400 @enderror">
                        @error('current_password', 'updatePassword')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-[#555] mb-1.5">Password Baru</label>
                        <input type="password" name="password"
                            class="w-full border border-[#ede5e6] rounded-xl px-4 py-2.5 text-sm outline-none focus:border-[#7D1A2E] transition-colors @error('password', 'updatePassword') border-red-400 @enderror">
                        @error('password', 'updatePassword')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-xs font-semibold text-[#555] mb-1.5">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation"
                            class="w-full border border-[#ede5e6] rounded-xl px-4 py-2.5 text-sm outline-none focus:border-[#7D1A2E] transition-colors">
                    </div>

                    @if (session('status') === 'password-updated')
                        <div
                            class="bg-green-50 border border-green-200 text-green-700 text-xs px-4 py-2.5 rounded-lg mb-4 flex items-center gap-2">
                            <i class="ti ti-circle-check"></i> Password berhasil diperbarui.
                        </div>
                    @endif

                    <button type="submit"
                        class="bg-[#7D1A2E] hover:bg-[#9B2035] text-white font-bold text-sm px-6 py-2.5 rounded-xl transition-colors">
                        Update Password
                    </button>
                </form>
            </div>

            {{-- Info Seller (kalau seller) --}}
            @if (auth()->user()->isSeller())
                <div class="bg-white border border-[#ede5e6] rounded-2xl p-6 mb-5">
                    <h2 class="text-sm font-bold text-[#1a0a0e] mb-4 flex items-center gap-2">
                        <span class="w-1 h-4 bg-[#7D1A2E] rounded-full inline-block"></span>
                        Info Toko
                    </h2>
                    <div class="flex flex-col gap-2 text-sm text-[#555]">
                        <div class="flex items-center gap-2">
                            <i class="ti ti-store text-[#aaa] text-sm w-4"></i>
                            <span>{{ auth()->user()->store_name }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="ti ti-map-pin text-[#aaa] text-sm w-4"></i>
                            <span>{{ auth()->user()->store_address }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="ti ti-brand-whatsapp text-[#aaa] text-sm w-4"></i>
                            <span>{{ auth()->user()->store_wa }}</span>
                        </div>
                    </div>
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center gap-2 mt-4 text-xs text-[#7D1A2E] font-semibold hover:underline">
                        <i class="ti ti-layout-dashboard"></i> Ke Dashboard Penjual
                    </a>
                </div>
            @endif

            {{-- Hapus Akun --}}
            <div class="bg-white border border-red-100 rounded-2xl p-6">
                <h2 class="text-sm font-bold text-red-600 mb-2">Hapus Akun</h2>
                <p class="text-xs text-[#aaa] mb-4">Setelah dihapus, semua data akun tidak bisa dipulihkan.</p>
                <form method="POST" action="{{ route('profile.destroy') }}"
                    onsubmit="return confirm('Yakin ingin menghapus akun? Tindakan ini tidak bisa dibatalkan.')">
                    @csrf
                    @method('DELETE')
                    <input type="password" name="password" placeholder="Masukkan password untuk konfirmasi"
                        class="w-full border border-red-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-red-400 mb-3 transition-colors">
                    @error('password', 'userDeletion')
                        <p class="text-xs text-red-500 mb-3">{{ $message }}</p>
                    @enderror
                    <button type="submit"
                        class="bg-red-500 hover:bg-red-600 text-white font-bold text-sm px-6 py-2.5 rounded-xl transition-colors">
                        Hapus Akun Saya
                    </button>
                </form>
            </div>

        </div>
    </div>
@endsection
