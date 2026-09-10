<x-guest-layout>
    <h1 class="text-xl font-black text-white mb-1">Daftar Akun</h1>
    <p class="text-xs text-white/40 mb-6">Bergabung dengan komunitas UMKM Marasa.id</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-4">
            <label class="block text-xs font-semibold text-white/60 mb-1.5">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name') }}" required autofocus
                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-white/25 outline-none focus:border-[#7D1A2E] transition-colors @error('name') border-red-500/50 @enderror"
                placeholder="Nama kamu">
            @error('name')
                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-xs font-semibold text-white/60 mb-1.5">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-white/25 outline-none focus:border-[#7D1A2E] transition-colors @error('email') border-red-500/50 @enderror"
                placeholder="email@kamu.com">
            @error('email')
                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-xs font-semibold text-white/60 mb-1.5">Password</label>
            <input type="password" name="password" required autocomplete="new-password"
                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-white/25 outline-none focus:border-[#7D1A2E] transition-colors @error('password') border-red-500/50 @enderror"
                placeholder="Minimal 8 karakter">
            @error('password')
                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block text-xs font-semibold text-white/60 mb-1.5">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" required autocomplete="new-password"
                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-white/25 outline-none focus:border-[#7D1A2E] transition-colors"
                placeholder="Ulangi password">
        </div>

        <button type="submit"
            class="w-full bg-[#7D1A2E] hover:bg-[#9B2035] text-white font-bold text-sm py-3 rounded-xl transition-colors mb-4">
            Buat Akun
        </button>

        <p class="text-center text-xs text-white/40">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-[#ff9a8e] hover:text-white transition-colors font-semibold">
                Masuk
            </a>
        </p>
    </form>
</x-guest-layout>
