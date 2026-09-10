<x-guest-layout>
    <h1 class="text-xl font-black text-white mb-1">Konfirmasi Password</h1>
    <p class="text-xs text-white/40 mb-6">Ini area aman. Konfirmasi password kamu sebelum melanjutkan.</p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="mb-6">
            <label class="block text-xs font-semibold text-white/60 mb-1.5">Password</label>
            <input type="password" name="password" required autocomplete="current-password"
                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-white/25 outline-none focus:border-[#7D1A2E] transition-colors @error('password') border-red-500/50 @enderror">
            @error('password')
                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
            class="w-full bg-[#7D1A2E] hover:bg-[#9B2035] text-white font-bold text-sm py-3 rounded-xl transition-colors">
            Konfirmasi
        </button>
    </form>
</x-guest-layout>
