@extends('layouts.app')

@section('title', 'Dashboard Penjual')

@section('content')
    <div class="bg-[#f5f0ef] min-h-screen">
        <div class="max-w-7xl mx-auto px-6 py-8">

            {{-- Header --}}
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-xl font-black text-[#1a0a0e]">Dashboard Penjual</h1>
                    <p class="text-sm text-[#aaa] mt-0.5">Selamat datang,
                        {{ auth()->user()->store_name ?? auth()->user()->name }}</p>
                </div>
                <a href="{{ route('listings.create') }}"
                    class="inline-flex items-center gap-2 bg-[#7D1A2E] hover:bg-[#9B2035] text-white text-sm font-bold px-5 py-2.5 rounded-xl transition-colors">
                    <i class="ti ti-plus"></i> Pasang Iklan
                </a>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-5 gap-4 mb-8">
                @php
                    $statItems = [
                        [
                            'label' => 'Total Iklan',
                            'value' => $stats['total'],
                            'icon' => 'ti-layout-grid',
                            'color' => 'text-[#7D1A2E]',
                        ],
                        [
                            'label' => 'Aktif',
                            'value' => $stats['active'],
                            'icon' => 'ti-circle-check',
                            'color' => 'text-green-600',
                        ],
                        [
                            'label' => 'Stok Habis',
                            'value' => $stats['empty'],
                            'icon' => 'ti-circle-x',
                            'color' => 'text-orange-500',
                        ],
                        [
                            'label' => 'Nonaktif',
                            'value' => $stats['inactive'],
                            'icon' => 'ti-eye-off',
                            'color' => 'text-gray-400',
                        ],
                        [
                            'label' => 'Total Dilihat',
                            'value' => number_format($stats['views']),
                            'icon' => 'ti-eye',
                            'color' => 'text-blue-500',
                        ],
                    ];
                @endphp
                @foreach ($statItems as $s)
                    <div class="bg-white border border-[#ede5e6] rounded-2xl p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs text-[#aaa]">{{ $s['label'] }}</span>
                            <i class="ti {{ $s['icon'] }} text-base {{ $s['color'] }}"></i>
                        </div>
                        <div class="text-2xl font-black text-[#1a0a0e]">{{ $s['value'] }}</div>
                    </div>
                @endforeach
            </div>

            <div class="grid grid-cols-3 gap-6">

                {{-- Daftar Iklan --}}
                <div class="col-span-2">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-sm font-bold text-[#1a0a0e] flex items-center gap-2">
                            <span class="w-1 h-4 bg-[#7D1A2E] rounded-full inline-block"></span>
                            Iklan Saya
                        </h2>
                    </div>

                    <div class="flex flex-col gap-3">
                        @forelse($listings as $listing)
                            <div
                                class="bg-white border border-[#ede5e6] rounded-2xl p-4 flex gap-4 hover:border-[#7D1A2E]/30 transition-colors">

                                {{-- Thumbnail --}}
                                <div class="w-20 h-20 rounded-xl bg-[#f5edef] overflow-hidden shrink-0">
                                    @if ($listing->firstImage)
                                        <img src="{{ Storage::url($listing->firstImage->path) }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i class="ti ti-photo text-2xl text-[#ddd]"></i>
                                        </div>
                                    @endif
                                </div>

                                {{-- Info --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2">
                                        <h3 class="text-sm font-semibold text-[#1a0a0e] line-clamp-1">{{ $listing->title }}
                                        </h3>
                                        <span
                                            class="shrink-0 text-[10px] font-bold px-2 py-0.5 rounded-full
                                        {{ $listing->status === 'active'
                                            ? 'bg-green-50 text-green-700 border border-green-200'
                                            : ($listing->status === 'empty'
                                                ? 'bg-orange-50 text-orange-700 border border-orange-200'
                                                : 'bg-gray-100 text-gray-500 border border-gray-200') }}">
                                            {{ $listing->status_label }}
                                        </span>
                                    </div>
                                    <p class="text-sm font-black text-[#7D1A2E] mt-1">
                                        Rp {{ number_format($listing->price, 0, ',', '.') }}
                                    </p>
                                    <div class="flex items-center gap-3 mt-2 text-[11px] text-[#aaa]">
                                        <span><i class="ti ti-eye"></i> {{ number_format($listing->view_count) }}x
                                            dilihat</span>
                                        <span><i class="ti ti-clock"></i>
                                            {{ $listing->created_at->diffForHumans() }}</span>
                                        <span><i class="ti ti-tag"></i> {{ $listing->category->name }}</span>
                                    </div>
                                </div>

                                {{-- Actions --}}
                                <div class="flex flex-col gap-2 shrink-0">
                                    <a href="{{ route('listings.edit', $listing->slug) }}"
                                        class="w-8 h-8 flex items-center justify-center border border-[#ede5e6] rounded-lg text-[#888] hover:border-[#7D1A2E] hover:text-[#7D1A2E] transition-all text-sm">
                                        <i class="ti ti-edit"></i>
                                    </a>
                                    <a href="{{ route('listings.show', $listing->slug) }}"
                                        class="w-8 h-8 flex items-center justify-center border border-[#ede5e6] rounded-lg text-[#888] hover:border-[#7D1A2E] hover:text-[#7D1A2E] transition-all text-sm">
                                        <i class="ti ti-eye"></i>
                                    </a>

                                    {{-- Dropdown status --}}
                                    <div x-data="{ open: false }" class="relative">
                                        <button @click="open = !open"
                                            class="w-8 h-8 flex items-center justify-center border border-[#ede5e6] rounded-lg text-[#888] hover:border-[#7D1A2E] hover:text-[#7D1A2E] transition-all text-sm">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                        <div x-show="open" @click.outside="open = false"
                                            class="absolute right-0 top-9 bg-white border border-[#ede5e6] rounded-xl shadow-lg w-40 z-10 overflow-hidden">
                                            @foreach (['active' => 'Aktifkan', 'empty' => 'Tandai Habis', 'inactive' => 'Nonaktifkan'] as $status => $label)
                                                @if ($listing->status !== $status)
                                                    <form method="POST"
                                                        action="{{ route('listings.status', $listing->slug) }}">
                                                        @csrf @method('PATCH')
                                                        <input type="hidden" name="status" value="{{ $status }}">
                                                        <button type="submit"
                                                            class="w-full text-left px-4 py-2 text-xs text-[#555] hover:bg-[#f5edef] hover:text-[#7D1A2E] transition-colors">
                                                            {{ $label }}
                                                        </button>
                                                    </form>
                                                @endif
                                            @endforeach
                                            <div class="border-t border-[#ede5e6]">
                                                <form method="POST"
                                                    action="{{ route('listings.destroy', $listing->slug) }}"
                                                    onsubmit="return confirm('Yakin hapus iklan ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="w-full text-left px-4 py-2 text-xs text-red-500 hover:bg-red-50 transition-colors">
                                                        Hapus Iklan
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="bg-white border border-dashed border-[#ede5e6] rounded-2xl p-10 text-center">
                                <i class="ti ti-tag text-4xl text-[#ddd] block mb-3"></i>
                                <p class="text-sm text-[#aaa] mb-4">Belum ada iklan.</p>
                                <a href="{{ route('listings.create') }}"
                                    class="inline-flex items-center gap-2 bg-[#7D1A2E] text-white text-sm font-semibold px-5 py-2.5 rounded-xl hover:bg-[#9B2035] transition-colors">
                                    <i class="ti ti-plus"></i> Pasang Iklan Pertama
                                </a>
                            </div>
                        @endforelse
                    </div>

                    {{-- Pagination --}}
                    @if ($listings->hasPages())
                        <div class="mt-4">{{ $listings->links() }}</div>
                    @endif
                </div>

                {{-- Sidebar: Notifikasi + Info Toko --}}
                <div class="col-span-1 flex flex-col gap-4">

                    {{-- Info Toko --}}
                    <div class="bg-white border border-[#ede5e6] rounded-2xl p-5">
                        <h3 class="text-xs font-bold text-[#aaa] uppercase tracking-wider mb-4">Info Toko</h3>
                        <div class="flex items-center gap-3 mb-4">
                            @if (auth()->user()->avatar)
                                <img src="{{ Storage::url(auth()->user()->avatar) }}"
                                    class="w-12 h-12 rounded-full object-cover border border-[#ede5e6]">
                            @else
                                <div
                                    class="w-12 h-12 rounded-full bg-[#7D1A2E]/10 flex items-center justify-center text-[#7D1A2E] font-black text-lg">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <p class="text-sm font-bold text-[#1a0a0e]">{{ auth()->user()->store_name }}</p>
                                <div class="flex items-center gap-1 mt-0.5">
                                    <i class="ti ti-rosette-discount-check text-[#7D1A2E] text-xs"></i>
                                    <span class="text-[10px] text-[#7D1A2E] font-semibold">Terverifikasi</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col gap-2 text-xs text-[#888]">
                            <div class="flex items-center gap-2">
                                <i class="ti ti-map-pin text-xs text-[#aaa]"></i>
                                <span>{{ auth()->user()->store_address }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="ti ti-brand-whatsapp text-xs text-[#aaa]"></i>
                                <span>{{ auth()->user()->store_wa }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Notifikasi Terbaru --}}
                    @if ($unreadNotifications->count())
                        <div class="bg-white border border-[#ede5e6] rounded-2xl p-5">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xs font-bold text-[#aaa] uppercase tracking-wider">Notifikasi</h3>
                                <a href="{{ route('notifications.index') }}"
                                    class="text-xs text-[#7D1A2E] font-semibold">Lihat semua</a>
                            </div>
                            <div class="flex flex-col gap-3">
                                @foreach ($unreadNotifications as $notif)
                                    <a href="{{ $notif->url ?? route('notifications.index') }}"
                                        class="flex items-start gap-3 group">
                                        <div
                                            class="w-7 h-7 rounded-full bg-[#7D1A2E]/10 flex items-center justify-center shrink-0 mt-0.5">
                                            <i class="ti ti-bell text-xs text-[#7D1A2E]"></i>
                                        </div>
                                        <div>
                                            <p
                                                class="text-xs text-[#1a0a0e] group-hover:text-[#7D1A2E] transition-colors leading-snug">
                                                {{ $notif->message }}
                                            </p>
                                            <p class="text-[10px] text-[#aaa] mt-0.5">
                                                {{ $notif->created_at->diffForHumans() }}</p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection
