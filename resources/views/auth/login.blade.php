<x-guest-layout>
    <h1 class="text-xl font-black text-white mb-1">Masuk</h1>
    <p class="text-xs text-white/40 mb-6">Selamat datang kembali di Marasa.id</p>

    @if (session('status'))
        <div class="mb-4 text-sm text-green-400 bg-green-500/10 border border-green-500/20 rounded-xl px-4 py-2.5">
            {{ session('status') }}
        </div>
    @endifgit

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-4">
            <label class="block text-xs font-semibold text-white/60 mb-1.5">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-white/25 outline-none focus:border-[#7D1A2E] transition-colors @error('email') border-red-500/50 @enderror">
            @error('email')
                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-xs font-semibold text-white/60 mb-1.5">Password</label>
            <input type="password" name="password" required autocomplete="current-password"
                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-white/25 outline-none focus:border-[#7D1A2E] transition-colors @error('password') border-red-500/50 @enderror">
            @error('password')
                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between mb-6">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="remember" class="accent-[#7D1A2E]">
                <span class="text-xs text-white/40">Ingat saya</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                    class="text-xs text-[#ff9a8e] hover:text-white transition-colors">
                    Lupa password?
                </a>
            @endif
        </div>

        <button type="submit"
            class="w-full bg-[#7D1A2E] hover:bg-[#9B2035] text-white font-bold text-sm py-3 rounded-xl transition-colors mb-4">
            Masuk
        </button>

        <p class="text-center text-xs text-white/40">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-[#ff9a8e] hover:text-white transition-colors font-semibold">
                Daftar sekarang
            </a>
        </p>
    </form>
</x-guest-layout>
