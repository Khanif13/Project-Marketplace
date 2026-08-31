@extends('layouts.app')

@section('title', 'Verifikasi Email')

@section('content')
    <div class="bg-[#f5f0ef] min-h-[70vh] flex items-center justify-center px-6">
        <div class="bg-white border border-[#ede5e6] rounded-2xl p-8 w-full max-w-md text-center">

            <div class="w-16 h-16 bg-[#7D1A2E]/10 rounded-full flex items-center justify-center mx-auto mb-5">
                <i class="ti ti-mail text-2xl text-[#7D1A2E]"></i>
            </div>

            <h1 class="text-lg font-bold text-[#1a0a0e] mb-2">Verifikasi Email Kamu</h1>
            <p class="text-sm text-[#888] mb-6 leading-relaxed">
                Kami sudah mengirim link verifikasi ke
                <span class="font-semibold text-[#1a0a0e]">{{ auth()->user()->email }}</span>.
                Cek inbox atau folder spam kamu.
            </p>

            @if (session('success'))
                <div
                    class="bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-lg mb-5 flex items-center gap-2">
                    <i class="ti ti-circle-check"></i>
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit"
                    class="w-full bg-[#7D1A2E] hover:bg-[#9B2035] text-white text-sm font-semibold py-3 rounded-lg transition-colors mb-3">
                    Kirim Ulang Email Verifikasi
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-xs text-[#aaa] hover:text-[#7D1A2E] transition-colors">
                    Keluar dari akun ini
                </button>
            </form>
        </div>
    </div>
@endsection
