@extends('layouts.app')

@section('title', 'Akses Ditolak')

@section('content')
    <div class="bg-[#f5f0ef] min-h-[60vh] flex items-center justify-center px-6">
        <div class="text-center">
            <div class="text-[80px] font-black text-[#7D1A2E]/10 leading-none mb-4">403</div>
            <h1 class="text-xl font-bold text-[#1a0a0e] mb-2">Akses Tidak Diizinkan</h1>
            <p class="text-sm text-[#888] mb-8">Kamu tidak punya izin untuk mengakses halaman ini.</p>
            <a href="{{ route('home') }}"
                class="inline-flex items-center gap-2 bg-[#7D1A2E] hover:bg-[#9B2035] text-white text-sm font-semibold px-6 py-3 rounded-lg transition-colors">
                <i class="ti ti-home"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
@endsection
