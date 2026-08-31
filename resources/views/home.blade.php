@extends('layouts.app')

@section('title', 'Marasa.id')

@section('content')

    {{-- ===================== HERO ===================== --}}
    <section class="bg-[#0f0608]">
        <div class="max-w-7xl mx-auto px-6 py-12 flex items-center justify-between gap-12">

            {{-- Kiri --}}
            <div class="flex-1">
                <div
                    class="inline-flex items-center gap-2 bg-[#7D1A2E]/20 border border-[#7D1A2E]/30 text-[#ff9a8e] text-xs font-semibold px-3 py-1.5 rounded-full mb-5 tracking-wide">
                    <i class="ti ti-rosette-discount-check"></i>
                    Platform UMKM Lokal Terverifikasi
                </div>
                <h1 class="text-4xl font-black text-white leading-tight tracking-tight mb-3">
                    Temukan Produk Lokal<br>
                    <span class="text-[#C0392B]">Terbaik</span> di Sekitarmu
                </h1>
                <p class="text-sm text-white/40 italic mb-8">
                    "Dari UMKM, oleh kita, untuk Indonesia"
                </p>
                <div class="flex items-center gap-3">
                    <a href="{{ route('listings.search') }}"
                        class="inline-flex items-center gap-2 bg-[#7D1A2E] hover:bg-[#9B2035] text-white text-sm font-bold px-6 py-3 rounded-lg transition-colors">
                        <i class="ti ti-compass"></i> Jelajahi Iklan
                    </a>
                    @guest
                        <a href="{{ route('seller.register') }}"
                            class="inline-flex items-center gap-2 bg-white/5 hover:bg-white/10 border border-white/10 hover:border-white/20 text-white/70 hover:text-white text-sm px-6 py-3 rounded-lg transition-all">
                            <i class="ti ti-store"></i> Jadi Penjual
                        </a>
                    @endguest
                </div>
            </div>

            {{-- Kanan: Stats --}}
            <div class="shrink-0 flex flex-col gap-3">
                <div class="flex gap-3">
                    <div class="bg-white/5 border border-white/8 rounded-xl px-6 py-4 text-center min-w-[110px]">
                        <div class="text-2xl font-black text-white">{{ number_format($totalListings) }}</div>
                        <div class="text-[10px] text-white/35 uppercase tracking-wider mt-1">Iklan Aktif</div>
                    </div>
                    <div class="bg-white/5 border border-white/8 rounded-xl px-6 py-4 text-center min-w-[110px]">
                        <div class="text-2xl font-black text-white">{{ number_format($totalSellers) }}</div>
                        <div class="text-[10px] text-white/35 uppercase tracking-wider mt-1">Penjual</div>
                    </div>
                    <div class="bg-white/5 border border-white/8 rounded-xl px-6 py-4 text-center min-w-[110px]">
                        <div class="text-2xl font-black text-white">{{ number_format($totalCategories) }}</div>
                        <div class="text-[10px] text-white/35 uppercase tracking-wider mt-1">Kategori</div>
                    </div>
                </div>

                {{-- Banner Seller --}}
                @guest
                    <div
                        class="bg-gradient-to-r from-[#7D1A2E]/40 to-[#3a0a14]/40 border border-[#7D1A2E]/25 rounded-xl px-5 py-4 flex items-center justify-between gap-6">
                        <div>
                            <p class="text-sm font-bold text-white">Punya produk UMKM?</p>
                            <p class="text-xs text-white/40 mt-0.5">Daftar gratis, hubungi pembeli via WhatsApp</p>
                        </div>
                        <a href="{{ route('seller.register') }}"
                            class="shrink-0 bg-[#7D1A2E] hover:bg-[#9B2035] text-white text-xs font-bold px-4 py-2 rounded-lg transition-colors whitespace-nowrap">
                            Daftar →
                        </a>
                    </div>
                @endguest
            </div>
        </div>
    </section>

    {{-- ===================== BODY ===================== --}}
    <div class="bg-[#f5f0ef] min-h-screen">
        <div class="max-w-7xl mx-auto px-6 py-8">

            {{-- KATEGORI --}}
            <div class="mb-10">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-bold text-[#1a0a0e] flex items-center gap-2">
                        <span class="w-1 h-4 bg-[#7D1A2E] rounded-full inline-block"></span>
                        Kategori
                    </h2>
                </div>
                <div class="grid grid-cols-7 gap-3">
                    @foreach ($categories as $cat)
                        <a href="{{ route('listings.category', $cat->slug) }}"
                            class="bg-white border border-[#ede5e6] hover:border-[#7D1A2E] hover:bg-[#fff5f5] rounded-xl p-3 text-center transition-all group">
                            <i class="ti {{ $cat->icon }} text-[22px] text-[#7D1A2E] block mb-2"></i>
                            <span
                                class="text-[11px] text-[#555] font-500 group-hover:text-[#7D1A2E] transition-colors">{{ $cat->name }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- IKLAN TERBARU --}}
            <div class="mb-10">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-bold text-[#1a0a0e] flex items-center gap-2">
                        <span class="w-1 h-4 bg-[#7D1A2E] rounded-full inline-block"></span>
                        Iklan Terbaru
                    </h2>
                    <a href="{{ route('listings.search', ['sort' => 'latest']) }}"
                        class="text-xs text-[#7D1A2E] font-semibold hover:underline">
                        Lihat semua →
                    </a>
                </div>
                <div class="grid grid-cols-4 gap-4">
                    @forelse($latestListings as $listing)
                        @include('partials.listing-card', ['listing' => $listing])
                    @empty
                        <div class="col-span-4 text-center py-12 text-[#aaa] text-sm">
                            Belum ada iklan tersedia.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- IKLAN POPULER --}}
            @if ($popularListings->count())
                <div class="mb-10">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-sm font-bold text-[#1a0a0e] flex items-center gap-2">
                            <span class="w-1 h-4 bg-[#7D1A2E] rounded-full inline-block"></span>
                            Iklan Populer
                        </h2>
                        <a href="{{ route('listings.search', ['sort' => 'popular']) }}"
                            class="text-xs text-[#7D1A2E] font-semibold hover:underline">
                            Lihat semua →
                        </a>
                    </div>
                    <div class="grid grid-cols-4 gap-4">
                        @foreach ($popularListings as $listing)
                            @include('partials.listing-card', ['listing' => $listing])
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>

@endsection
