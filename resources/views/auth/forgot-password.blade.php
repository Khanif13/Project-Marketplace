<x-guest-layout>
    <h1 class="text-xl font-black text-white mb-1">Lupa Password</h1>
    <p class="text-xs text-white/40 mb-6">Masukkan email kamu dan kami akan kirimkan link reset password.</p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-6">
            <label class="block text-xs font-semibold text-white/60 mb-1.5">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-white/25 outline-none focus:border-[#7D1A2E] transition-colors @error('email') border-red-500/50 @enderror"
                placeholder="email@kamu.com">
            @error('email')
                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
            class="w-full bg-[#7D1A2E] hover:bg-[#9B2035] text-white font-bold text-sm py-3 rounded-xl transition-colors mb-4">
            Kirim Link Reset Password
        </button>

        <p class="text-center text-xs text-white/40">
            Ingat password?
            <a href="{{ route('login') }}" class="text-[#ff9a8e] hover:text-white transition-colors font-semibold">
                Kembali masuk
            </a>
        </p>
    </form>
</x-guest-layout>
