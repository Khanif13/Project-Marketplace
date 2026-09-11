@extends('layouts.app')

@section('title', 'Marasa.id')

@section('content')

    {{-- HERO --}}
    <section class="bg-[#0f0608] relative overflow-hidden">
        <div class="absolute inset-0 opacity-[0.03]"
            style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 32px 32px;">
        </div>
        <div
            class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[300px] bg-[#7D1A2E]/20 rounded-full blur-[80px] pointer-events-none">
        </div>

        <div class="max-w-7xl mx-auto px-6 py-20 relative">
            <div class="max-w-2xl mx-auto text-center">
                <div
                    class="inline-flex items-center gap-2 bg-[#7D1A2E]/20 border border-[#7D1A2E]/30 text-[#ff9a8e] text-xs font-semibold px-4 py-1.5 rounded-full mb-6 tracking-wide">
                    <i class="ti ti-rosette-discount-check"></i>
                    Platform UMKM Lokal Terverifikasi
                </div>
                <h1 class="text-5xl font-black text-white leading-tight tracking-tight mb-4">
                    Jual & Beli Produk<br>
                    <span class="text-[#C0392B]">Lokal</span> Lebih Mudah
                </h1>
                <p class="text-white/40 text-sm italic mb-3">"Dari UMKM, oleh kita, untuk Indonesia"</p>
                <p class="text-white/50 text-sm mb-10 leading-relaxed">
                    Temukan produk UMKM terbaik di sekitarmu.<br>
                    Hubungi penjual langsung via WhatsApp — gratis, mudah, cepat.
                </p>
                <div class="flex items-center justify-center gap-3 mb-10">
                    <a href="{{ route('listings.search') }}"
                        class="inline-flex items-center gap-2 bg-[#7D1A2E] hover:bg-[#9B2035] text-white text-sm font-bold px-8 py-3.5 rounded-xl transition-colors">
                        <i class="ti ti-compass"></i> Jelajahi Iklan
                    </a>
                    @guest
                        <a href="{{ route('seller.register') }}"
                            class="inline-flex items-center gap-2 bg-white/5 hover:bg-white/10 border border-white/10 hover:border-white/20 text-white/70 hover:text-white text-sm px-8 py-3.5 rounded-xl transition-all">
                            <i class="ti ti-store"></i> Jadi Penjual
                        </a>
                    @endguest
                </div>
                <form action="{{ route('listings.search') }}" method="GET"
                    class="flex bg-white/8 border border-white/15 rounded-2xl overflow-hidden h-14 max-w-xl mx-auto transition-colors">
                    <i class="ti ti-search text-white/40 text-base self-center ml-5"></i>
                    <input type="text" name="q" value="{{ request('q') }}"
                        placeholder="Cari produk, kategori, atau toko..."
                        class="flex-1 bg-transparent px-4 text-sm text-white placeholder-white/30 outline-none border-none ring-0">
                    <button type="submit"
                        class="bg-[#7D1A2E] hover:bg-[#9B2035] px-7 text-sm font-bold text-white transition-colors shrink-0 m-1.5 rounded-xl">
                        Cari
                    </button>
                </form>
            </div>
        </div>
    </section>

    {{-- BODY --}}
    <div class="bg-[#f5f0ef] min-h-screen">
        <div class="max-w-7xl mx-auto px-6 py-10">

            {{-- KATEGORI --}}
            @if ($categories->count())
                <div class="mb-10">
                    <x-section-title title="Kategori" />
                    <div class="grid grid-cols-7 gap-3">
                        @foreach ($categories as $cat)
                            <a href="{{ route('listings.category', $cat->slug) }}"
                                class="bg-white border border-[#ede5e6] hover:border-[#7D1A2E] hover:bg-[#fff5f5] rounded-xl p-3 text-center transition-all group">
                                <i class="ti {{ $cat->icon }} text-[22px] text-[#7D1A2E] block mb-2"></i>
                                <span
                                    class="text-[11px] text-[#555] font-medium group-hover:text-[#7D1A2E] transition-colors leading-tight">
                                    {{ $cat->name }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- IKLAN TERBARU --}}
            <div class="mb-10">
                <x-section-title title="Iklan Terbaru" :link="route('listings.search', ['sort' => 'latest'])" />
                <div class="grid grid-cols-4 gap-4">
                    @forelse($latestListings as $listing)
                        <x-listing-card :listing="$listing" />
                    @empty
                        <div class="col-span-4">
                            <x-empty-state icon="ti-tag" title="Belum ada iklan tersedia.">
                                @auth
                                    @if (auth()->user()->isSeller())
                                        <a href="{{ route('listings.create') }}"
                                            class="inline-flex items-center gap-2 mt-4 bg-[#7D1A2E] text-white text-xs font-semibold px-5 py-2.5 rounded-xl hover:bg-[#9B2035] transition-colors">
                                            <i class="ti ti-plus"></i> Pasang Iklan Pertama
                                        </a>
                                    @endif
                                @endauth
                            </x-empty-state>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- BANNER SELLER --}}
            @guest
                <div
                    class="bg-[#0f0608] rounded-2xl p-8 mb-10 flex items-center justify-between gap-8 relative overflow-hidden">
                    <div class="absolute inset-0 opacity-[0.04]"
                        style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 24px 24px;">
                    </div>
                    <div class="relative">
                        <p class="text-white font-black text-xl mb-2">Punya produk UMKM?</p>
                        <p class="text-white/40 text-sm">Daftar jadi penjual, pasang iklan gratis, dan langsung terhubung ke
                            pembeli via WhatsApp.</p>
                    </div>
                    <a href="{{ route('seller.register') }}"
                        class="shrink-0 relative bg-[#7D1A2E] hover:bg-[#9B2035] text-white text-sm font-bold px-8 py-3.5 rounded-xl transition-colors whitespace-nowrap">
                        Mulai Jual Sekarang →
                    </a>
                </div>
            @endguest

            {{-- IKLAN POPULER --}}
            @if ($popularListings->count())
                <div class="mb-10">
                    <x-section-title title="Iklan Populer" :link="route('listings.search', ['sort' => 'popular'])" />
                    <div class="grid grid-cols-4 gap-4">
                        @foreach ($popularListings as $listing)
                            <x-listing-card :listing="$listing" />
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>

@endsection
