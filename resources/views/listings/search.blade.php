@extends('layouts.app')

@section('title', request('q') ? 'Hasil pencarian: ' . request('q') : 'Semua Iklan')

@section('content')
<div class="bg-[#f5f0ef] min-h-screen">
    <div class="max-w-7xl mx-auto px-6 py-8">

        <div class="flex gap-6">

            {{-- ===================== SIDEBAR FILTER ===================== --}}
            <aside class="w-60 shrink-0">
                <form method="GET" action="{{ route('listings.search') }}" id="filterForm">

                    <div class="bg-white border border-[#ede5e6] rounded-2xl overflow-hidden">

                        {{-- Header --}}
                        <div class="px-5 py-4 border-b border-[#ede5e6] flex items-center justify-between">
                            <span class="text-sm font-bold text-[#1a0a0e]">Filter</span>
                            <a href="{{ route('listings.search') }}"
                               class="text-xs text-[#7D1A2E] font-semibold">Reset</a>
                        </div>

                        {{-- Kategori --}}
                        <div class="px-5 py-4 border-b border-[#ede5e6]">
                            <p class="text-xs font-bold text-[#aaa] uppercase tracking-wider mb-3">Kategori</p>
                            <div class="flex flex-col gap-1.5">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="category" value=""
                                           {{ ! request('category') ? 'checked' : '' }}
                                           class="accent-[#7D1A2E]"
                                           onchange="document.getElementById('filterForm').submit()">
                                    <span class="text-sm text-[#555]">Semua Kategori</span>
                                </label>
                                @foreach($categories as $cat)
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="category" value="{{ $cat->slug }}"
                                               {{ request('category') === $cat->slug ? 'checked' : '' }}
                                               class="accent-[#7D1A2E]"
                                               onchange="document.getElementById('filterForm').submit()">
                                        <span class="text-sm text-[#555]">{{ $cat->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Kondisi --}}
                        <div class="px-5 py-4 border-b border-[#ede5e6]">
                            <p class="text-xs font-bold text-[#aaa] uppercase tracking-wider mb-3">Kondisi</p>
                            <div class="flex flex-col gap-1.5">
                                @foreach(['' => 'Semua', 'new' => 'Baru', 'used' => 'Bekas'] as $val => $label)
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="condition" value="{{ $val }}"
                                               {{ request('condition', '') === $val ? 'checked' : '' }}
                                               class="accent-[#7D1A2E]"
                                               onchange="document.getElementById('filterForm').submit()">
                                        <span class="text-sm text-[#555]">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Rentang Harga --}}
                        <div class="px-5 py-4 border-b border-[#ede5e6]">
                            <p class="text-xs font-bold text-[#aaa] uppercase tracking-wider mb-3">Rentang Harga</p>
                            <div class="flex flex-col gap-2">
                                <input type="number" name="price_min"
                                       value="{{ request('price_min') }}"
                                       placeholder="Harga minimum"
                                       class="w-full border border-[#ede5e6] rounded-lg px-3 py-2 text-xs text-[#555] outline-none focus:border-[#7D1A2E]">
                                <input type="number" name="price_max"
                                       value="{{ request('price_max') }}"
                                       placeholder="Harga maksimum"
                                       class="w-full border border-[#ede5e6] rounded-lg px-3 py-2 text-xs text-[#555] outline-none focus:border-[#7D1A2E]">
                                <button type="submit"
                                        class="w-full bg-[#7D1A2E] hover:bg-[#9B2035] text-white text-xs font-semibold py-2 rounded-lg transition-colors">
                                    Terapkan
                                </button>
                            </div>
                        </div>

                        {{-- Nego --}}
                        <div class="px-5 py-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="negotiable" value="1"
                                       {{ request('negotiable') ? 'checked' : '' }}
                                       class="accent-[#7D1A2E]"
                                       onchange="document.getElementById('filterForm').submit()">
                                <span class="text-sm text-[#555]">Harga Nego</span>
                            </label>
                        </div>

                        {{-- Preserve q & sort --}}
                        @if(request('q'))
                            <input type="hidden" name="q" value="{{ request('q') }}">
                        @endif
                        @if(request('sort'))
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                        @endif
                    </div>
                </form>
            </aside>

            {{-- ===================== MAIN CONTENT ===================== --}}
            <div class="flex-1 min-w-0">

                {{-- Toolbar --}}
                <div class="flex items-center justify-between mb-5">
                    <div>
                        @if(request('q'))
                            <h1 class="text-base font-bold text-[#1a0a0e]">
                                Hasil untuk <span class="text-[#7D1A2E]">"{{ request('q') }}"</span>
                            </h1>
                        @else
                            <h1 class="text-base font-bold text-[#1a0a0e]">Semua Iklan</h1>
                        @endif
                        <p class="text-xs text-[#aaa] mt-0.5">
                            {{ $listings->total() }} iklan ditemukan
                        </p>
                    </div>

                    {{-- Sort --}}
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-[#aaa]">Urutkan:</span>
                        <select onchange="window.location.href=this.value"
                                class="border border-[#ede5e6] rounded-lg px-3 py-2 text-xs text-[#555] outline-none focus:border-[#7D1A2E] bg-white">
                            @php
                                $baseQuery = request()->except('sort');
                                $sorts = ['latest' => 'Terbaru', 'popular' => 'Populer', 'price_asc' => 'Harga Terendah', 'price_desc' => 'Harga Tertinggi'];
                            @endphp
                            @foreach($sorts as $val => $label)
                                <option value="{{ route('listings.search', array_merge($baseQuery, ['sort' => $val])) }}"
                                        {{ request('sort', 'latest') === $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Grid Iklan --}}
                @if($listings->count())
                    <div class="grid grid-cols-4 gap-4 mb-6">
                        @foreach($listings as $listing)
                            @include('partials.listing-card', ['listing' => $listing])
                        @endforeach
                    </div>
                    {{ $listings->links() }}
                @else
                    <div class="bg-white border border-dashed border-[#ede5e6] rounded-2xl p-16 text-center">
                        <i class="ti ti-search-off text-5xl text-[#ddd] block mb-4"></i>
                        <p class="text-sm font-semibold text-[#888] mb-1">Tidak ada iklan ditemukan</p>
                        <p class="text-xs text-[#aaa]">Coba kata kunci lain atau ubah filter pencarian</p>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection