<x-guest-layout>
    <div class="w-14 h-14 bg-[#7D1A2E]/20 rounded-full flex items-center justify-center mx-auto mb-5">
        <i class="ti ti-mail text-2xl text-[#7D1A2E]"></i>
    </div>

    <h1 class="text-xl font-black text-white text-center mb-2">Verifikasi Email</h1>
    <p class="text-xs text-white/40 text-center mb-6 leading-relaxed">
        Kami sudah mengirim link verifikasi ke
        <span class="text-white/70 font-semibold">{{ auth()->user()->email }}</span>.
        Cek inbox atau folder spam kamu.
    </p>

    @if (session('status') === 'verification-link-sent')
        <div
            class="bg-green-900/30 border border-green-500/20 text-green-400 text-xs px-4 py-3 rounded-xl mb-4 flex items-center gap-2">
            <i class="ti ti-circle-check"></i> Link verifikasi berhasil dikirim ulang.
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit"
            class="w-full bg-[#7D1A2E] hover:bg-[#9B2035] text-white font-bold text-sm py-3 rounded-xl transition-colors mb-3">
            Kirim Ulang Email Verifikasi
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="w-full text-xs text-white/30 hover:text-white/60 transition-colors py-2">
            Keluar dari akun ini
        </button>
    </form>
</x-guest-layout>
