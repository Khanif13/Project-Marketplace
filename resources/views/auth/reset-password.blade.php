<x-guest-layout>
    <h1 class="text-xl font-black text-white mb-1">Reset Password</h1>
    <p class="text-xs text-white/40 mb-6">Masukkan password baru kamu.</p>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="mb-4">
            <label class="block text-xs font-semibold text-white/60 mb-1.5">Email</label>
            <input type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus
                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-white/25 outline-none focus:border-[#7D1A2E] transition-colors @error('email') border-red-500/50 @enderror">
            @error('email')
                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-xs font-semibold text-white/60 mb-1.5">Password Baru</label>
            <input type="password" name="password" required autocomplete="new-password"
                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-white/25 outline-none focus:border-[#7D1A2E] transition-colors @error('password') border-red-500/50 @enderror"
                placeholder="Minimal 8 karakter">
            @error('password')
                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block text-xs font-semibold text-white/60 mb-1.5">Konfirmasi Password Baru</label>
            <input type="password" name="password_confirmation" required autocomplete="new-password"
                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-white/25 outline-none focus:border-[#7D1A2E] transition-colors"
                placeholder="Ulangi password baru">
        </div>

        <button type="submit"
            class="w-full bg-[#7D1A2E] hover:bg-[#9B2035] text-white font-bold text-sm py-3 rounded-xl transition-colors">
            Reset Password
        </button>
    </form>
</x-guest-layout>
