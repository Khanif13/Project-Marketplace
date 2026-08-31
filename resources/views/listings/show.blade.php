@extends('layouts.app')

@section('title', $listing->title)

@section('content')
    <div class="bg-[#f5f0ef] min-h-screen">
        <div class="max-w-7xl mx-auto px-6 py-8">

            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-xs text-[#aaa] mb-6">
                <a href="{{ route('home') }}" class="hover:text-[#7D1A2E]">Beranda</a>
                <i class="ti ti-chevron-right text-[10px]"></i>
                <a href="{{ route('listings.category', $listing->category->slug) }}" class="hover:text-[#7D1A2E]">
                    {{ $listing->category->name }}
                </a>
                <i class="ti ti-chevron-right text-[10px]"></i>
                <span class="text-[#555] truncate max-w-[200px]">{{ $listing->title }}</span>
            </nav>

            <div class="grid grid-cols-3 gap-8">

                {{-- ===================== KIRI: Foto + Info ===================== --}}
                <div class="col-span-2 flex flex-col gap-6">

                    {{-- Foto Carousel --}}
                    <div class="bg-white rounded-2xl border border-[#ede5e6] overflow-hidden" x-data="{ active: 0 }">
                        {{-- Main image --}}
                        <div class="relative h-[400px] bg-[#f5edef]">
                            @if ($listing->images->count())
                                @foreach ($listing->images as $i => $img)
                                    <img src="{{ Storage::url($img->path) }}" alt="{{ $listing->title }}"
                                        x-show="active === {{ $i }}"
                                        class="w-full h-full object-contain absolute inset-0">
                                @endforeach

                                {{-- Arrows --}}
                                @if ($listing->images->count() > 1)
                                    <button @click="active = active > 0 ? active - 1 : {{ $listing->images->count() - 1 }}"
                                        class="absolute left-3 top-1/2 -translate-y-1/2 w-9 h-9 bg-white/90 border border-[#ede5e6] rounded-full flex items-center justify-center text-[#555] hover:border-[#7D1A2E] hover:text-[#7D1A2E] transition-all">
                                        <i class="ti ti-chevron-left"></i>
                                    </button>
                                    <button @click="active = active < {{ $listing->images->count() - 1 }} ? active + 1 : 0"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 bg-white/90 border border-[#ede5e6] rounded-full flex items-center justify-center text-[#555] hover:border-[#7D1A2E] hover:text-[#7D1A2E] transition-all">
                                        <i class="ti ti-chevron-right"></i>
                                    </button>
                                @endif

                                {{-- Badge status --}}
                                @if ($listing->status === 'empty')
                                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                                        <span class="bg-white text-gray-700 font-bold text-sm px-4 py-2 rounded-lg">Stok
                                            Habis</span>
                                    </div>
                                @endif
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="ti ti-photo text-6xl text-[#ddd]"></i>
                                </div>
                            @endif
                        </div>

                        {{-- Thumbnail strip --}}
                        @if ($listing->images->count() > 1)
                            <div class="flex gap-2 p-3 border-t border-[#ede5e6] overflow-x-auto">
                                @foreach ($listing->images as $i => $img)
                                    <button @click="active = {{ $i }}"
                                        class="shrink-0 w-16 h-16 rounded-lg overflow-hidden border-2 transition-all"
                                        :class="active === {{ $i }} ? 'border-[#7D1A2E]' :
                                            'border-transparent opacity-60 hover:opacity-100'">
                                        <img src="{{ Storage::url($img->path) }}" class="w-full h-full object-cover">
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Deskripsi --}}
                    <div class="bg-white rounded-2xl border border-[#ede5e6] p-6">
                        <h2 class="text-sm font-bold text-[#1a0a0e] mb-4 flex items-center gap-2">
                            <span class="w-1 h-4 bg-[#7D1A2E] rounded-full inline-block"></span>
                            Deskripsi
                        </h2>
                        <p class="text-sm text-[#444] leading-relaxed whitespace-pre-line">{{ $listing->description }}</p>
                    </div>

                    {{-- Detail Info --}}
                    <div class="bg-white rounded-2xl border border-[#ede5e6] p-6">
                        <h2 class="text-sm font-bold text-[#1a0a0e] mb-4 flex items-center gap-2">
                            <span class="w-1 h-4 bg-[#7D1A2E] rounded-full inline-block"></span>
                            Detail Barang
                        </h2>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-[#faf5f6] rounded-xl p-3">
                                <p class="text-[10px] text-[#aaa] uppercase tracking-wider mb-1">Kondisi</p>
                                <p class="text-sm font-semibold text-[#1a0a0e]">{{ $listing->condition_label }}</p>
                            </div>
                            <div class="bg-[#faf5f6] rounded-xl p-3">
                                <p class="text-[10px] text-[#aaa] uppercase tracking-wider mb-1">Kategori</p>
                                <p class="text-sm font-semibold text-[#1a0a0e]">{{ $listing->category->name }}</p>
                            </div>
                            <div class="bg-[#faf5f6] rounded-xl p-3">
                                <p class="text-[10px] text-[#aaa] uppercase tracking-wider mb-1">Lokasi</p>
                                <p class="text-sm font-semibold text-[#1a0a0e]">{{ $listing->address }}</p>
                            </div>
                            <div class="bg-[#faf5f6] rounded-xl p-3">
                                <p class="text-[10px] text-[#aaa] uppercase tracking-wider mb-1">Stok</p>
                                <p class="text-sm font-semibold text-[#1a0a0e]">
                                    @if ($listing->stock !== null)
                                        {{ $listing->stock }} unit
                                    @else
                                        Tersedia
                                    @endif
                                </p>
                            </div>
                            <div class="bg-[#faf5f6] rounded-xl p-3">
                                <p class="text-[10px] text-[#aaa] uppercase tracking-wider mb-1">Dilihat</p>
                                <p class="text-sm font-semibold text-[#1a0a0e]">{{ number_format($listing->view_count) }}x
                                </p>
                            </div>
                            <div class="bg-[#faf5f6] rounded-xl p-3">
                                <p class="text-[10px] text-[#aaa] uppercase tracking-wider mb-1">Diposting</p>
                                <p class="text-sm font-semibold text-[#1a0a0e]">{{ $listing->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Iklan Serupa --}}
                    @if ($relatedListings->count())
                        <div>
                            <h2 class="text-sm font-bold text-[#1a0a0e] mb-4 flex items-center gap-2">
                                <span class="w-1 h-4 bg-[#7D1A2E] rounded-full inline-block"></span>
                                Iklan Serupa
                            </h2>
                            <div class="grid grid-cols-4 gap-3">
                                @foreach ($relatedListings as $related)
                                    @include('partials.listing-card', ['listing' => $related])
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>

                {{-- ===================== KANAN: Sidebar ===================== --}}
                <div class="col-span-1 flex flex-col gap-4">

                    {{-- Harga + CTA --}}
                    <div class="bg-white rounded-2xl border border-[#ede5e6] p-5 sticky top-24">

                        <div class="mb-4">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-2xl font-black text-[#7D1A2E]">
                                    Rp {{ number_format($listing->price, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex items-center gap-2 flex-wrap">
                                @if ($listing->is_negotiable)
                                    <span
                                        class="text-[10px] font-bold bg-yellow-50 text-yellow-700 border border-yellow-200 px-2 py-0.5 rounded">
                                        Harga Bisa Nego
                                    </span>
                                @endif
                                <span
                                    class="text-[10px] font-bold px-2 py-0.5 rounded
                                {{ $listing->condition === 'new' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-orange-50 text-orange-700 border border-orange-200' }}">
                                    {{ $listing->condition_label }}
                                </span>
                                <span
                                    class="text-[10px] font-bold px-2 py-0.5 rounded
                                {{ $listing->status === 'active' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-gray-100 text-gray-500 border border-gray-200' }}">
                                    {{ $listing->status_label }}
                                </span>
                            </div>
                        </div>

                        {{-- Tombol WA --}}
                        @if ($listing->status === 'active')
                            <a href="{{ $listing->getWhatsappUrl() }}" target="_blank"
                                class="w-full flex items-center justify-center gap-2 bg-[#25D366] hover:bg-[#1ebe5d] text-white font-bold text-sm py-3 rounded-xl transition-colors mb-3">
                                <i class="ti ti-brand-whatsapp text-lg"></i>
                                Hubungi via WhatsApp
                            </a>
                        @else
                            <button disabled
                                class="w-full flex items-center justify-center gap-2 bg-gray-100 text-gray-400 font-bold text-sm py-3 rounded-xl cursor-not-allowed mb-3">
                                <i class="ti ti-brand-whatsapp text-lg"></i>
                                Stok Tidak Tersedia
                            </button>
                        @endif

                        {{-- Bookmark --}}
                        @auth
                            <form method="POST" action="{{ route('bookmarks.toggle', $listing->id) }}">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center justify-center gap-2 border text-sm font-semibold py-3 rounded-xl transition-all
                                           {{ $isBookmarked
                                               ? 'bg-[#7D1A2E]/5 border-[#7D1A2E] text-[#7D1A2E]'
                                               : 'bg-white border-[#ede5e6] text-[#888] hover:border-[#7D1A2E] hover:text-[#7D1A2E]' }}">
                                    <i class="ti {{ $isBookmarked ? 'ti-bookmark-filled' : 'ti-bookmark' }}"></i>
                                    {{ $isBookmarked ? 'Tersimpan' : 'Simpan Iklan' }}
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}"
                                class="w-full flex items-center justify-center gap-2 border border-[#ede5e6] text-[#888] text-sm font-semibold py-3 rounded-xl hover:border-[#7D1A2E] hover:text-[#7D1A2E] transition-all">
                                <i class="ti ti-bookmark"></i> Simpan Iklan
                            </a>
                        @endauth

                        {{-- Share --}}
                        <button
                            onclick="navigator.clipboard.writeText(window.location.href).then(() => alert('Link disalin!'))"
                            class="w-full flex items-center justify-center gap-2 border border-[#ede5e6] text-[#aaa] text-sm py-2.5 rounded-xl hover:border-[#7D1A2E] hover:text-[#7D1A2E] transition-all mt-2">
                            <i class="ti ti-link"></i> Salin Link
                        </button>
                    </div>

                    {{-- Profil Seller --}}
                    <div class="bg-white rounded-2xl border border-[#ede5e6] p-5">
                        <h3 class="text-xs font-bold text-[#aaa] uppercase tracking-wider mb-4">Penjual</h3>
                        <div class="flex items-center gap-3 mb-4">
                            @if ($listing->user->avatar)
                                <img src="{{ Storage::url($listing->user->avatar) }}"
                                    class="w-11 h-11 rounded-full object-cover border border-[#ede5e6]">
                            @else
                                <div
                                    class="w-11 h-11 rounded-full bg-[#7D1A2E]/10 flex items-center justify-center text-[#7D1A2E] font-black text-base">
                                    {{ strtoupper(substr($listing->user->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <p class="text-sm font-bold text-[#1a0a0e]">
                                    {{ $listing->user->store_name ?? $listing->user->name }}
                                </p>
                                <div class="flex items-center gap-1 mt-0.5">
                                    <i class="ti ti-rosette-discount-check text-[#7D1A2E] text-xs"></i>
                                    <span class="text-[10px] text-[#7D1A2E] font-semibold">Penjual Terverifikasi</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-1.5 text-xs text-[#aaa]">
                            <i class="ti ti-map-pin text-xs"></i>
                            <span>{{ $listing->user->store_address ?? $listing->address }}</span>
                        </div>
                    </div>

                    {{-- Iklan Lain dari Seller --}}
                    @if ($sellerListings->count())
                        <div class="bg-white rounded-2xl border border-[#ede5e6] p-5">
                            <h3 class="text-xs font-bold text-[#aaa] uppercase tracking-wider mb-4">
                                Iklan Lain dari Penjual Ini
                            </h3>
                            <div class="flex flex-col gap-3">
                                @foreach ($sellerListings as $other)
                                    <a href="{{ route('listings.show', $other->slug) }}" class="flex gap-3 group">
                                        <div class="w-14 h-14 rounded-lg bg-[#f5edef] overflow-hidden shrink-0">
                                            @if ($other->firstImage)
                                                <img src="{{ Storage::url($other->firstImage->path) }}"
                                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center">
                                                    <i class="ti ti-photo text-xl text-[#ddd]"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p
                                                class="text-xs font-medium text-[#1a0a0e] line-clamp-2 group-hover:text-[#7D1A2E] transition-colors leading-snug">
                                                {{ $other->title }}
                                            </p>
                                            <p class="text-xs font-bold text-[#7D1A2E] mt-1">
                                                Rp {{ number_format($other->price, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Laporkan Iklan --}}
                    @auth
                        @if (!$listing->isOwnedBy(Auth::user()))
                            <div x-data="{ open: false }">
                                <button @click="open = !open"
                                    class="w-full flex items-center justify-center gap-2 text-xs text-[#ccc] hover:text-red-400 transition-colors py-2">
                                    <i class="ti ti-flag text-xs"></i> Laporkan Iklan
                                </button>
                                <div x-show="open" x-transition
                                    class="bg-white border border-[#ede5e6] rounded-2xl p-5 mt-2">
                                    <p class="text-xs font-bold text-[#1a0a0e] mb-3">Alasan Laporan</p>
                                    <form method="POST" action="{{ route('reports.store', $listing->id) }}">
                                        @csrf
                                        <select name="reason" required
                                            class="w-full text-sm border border-[#ede5e6] rounded-lg px-3 py-2 text-[#555] mb-3 outline-none focus:border-[#7D1A2E]">
                                            <option value="">Pilih alasan...</option>
                                            <option value="spam">Spam</option>
                                            <option value="fraud">Penipuan</option>
                                            <option value="prohibited">Konten Terlarang</option>
                                            <option value="other">Lainnya</option>
                                        </select>
                                        <textarea name="description" rows="3" placeholder="Keterangan tambahan (opsional)"
                                            class="w-full text-sm border border-[#ede5e6] rounded-lg px-3 py-2 text-[#555] mb-3 outline-none focus:border-[#7D1A2E] resize-none"></textarea>
                                        <button type="submit"
                                            class="w-full bg-red-500 hover:bg-red-600 text-white text-sm font-semibold py-2 rounded-lg transition-colors">
                                            Kirim Laporan
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    @endauth

                </div>
            </div>
        </div>
    </div>
@endsection
