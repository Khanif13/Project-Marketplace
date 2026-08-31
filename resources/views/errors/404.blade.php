@extends('layouts.app')

@section('title', 'Halaman Tidak Ditemukan')

@section('content')
    <div class="bg-[#f5f0ef] min-h-[60vh] flex items-center justify-center px-6">
        <div class="text-center">
            <div class="text-[80px] font-black text-[#7D1A2E]/10 leading-none mb-4">404</div>
            <h1 class="text-xl font-bold text-[#1a0a0e] mb-2">Halaman Tidak Ditemukan</h1>
            <p class="text-sm text-[#888] mb-8">Halaman yang kamu cari tidak ada atau sudah dihapus.</p>
            <div class="flex items-center justify-center gap-3">
                <a href="{{ route('home') }}"
                    class="inline-flex items-center gap-2 bg-[#7D1A2E] hover:bg-[#9B2035] text-white text-sm font-semibold px-6 py-3 rounded-lg transition-colors">
                    <i class="ti ti-home"></i> Beranda
                </a>
                <button onclick="history.back()"
                    class="inline-flex items-center gap-2 bg-white border border-[#ede5e6] hover:border-[#7D1A2E] text-[#555] text-sm px-6 py-3 rounded-lg transition-colors">
                    <i class="ti ti-arrow-left"></i> Kembali
                </button>
            </div>
        </div>
    </div>
@endsection
