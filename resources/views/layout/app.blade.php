<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Marasa.id') — Dari UMKM, oleh kita, untuk Indonesia</title>

    {{-- Tabler Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- Vite (Tailwind) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="bg-[#0f0608] text-white antialiased">

{{-- ===================== NAVBAR ===================== --}}
<nav class="bg-[#130609] border-b border-white/5 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6 h-16 flex items-center gap-5">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex flex-col leading-none shrink-0">
            <span class="text-[22px] font-black tracking-tight text-white">
                Marasa<span class="text-[#C0392B]">.id</span>
            </span>
            <span class="text-[9px] text-white/30 tracking-wide mt-0.5">Dari UMKM, oleh kita, untuk Indonesia</span>
        </a>

        {{-- Search --}}
        <form action="{{ route('listings.search') }}" method="GET" class="flex-1 max-w-lg">
            <div class="flex bg-white/5 border border-white/10 rounded-lg overflow-hidden h-10 focus-within:border-[#7D1A2E] transition-colors">
                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Cari produk, toko, atau kategori..."
                    class="flex-1 bg-transparent px-4 text-sm text-white placeholder-white/30 outline-none"
                >
                <button type="submit" class="bg-[#7D1A2E] hover:bg-[#9B2035] px-5 text-sm font-semibold text-white transition-colors shrink-0">
                    <i class="ti ti-search"></i>
                </button>
            </div>
        </form>

        {{-- Nav Actions --}}
        <div class="ml-auto flex items-center gap-2">

            @auth
                {{-- Notifikasi --}}
                <div class="relative">
                    <a href="{{ route('notifications.index') }}"
                       class="w-9 h-9 flex items-center justify-center rounded-lg bg-white/5 border border-white/10 hover:border-[#7D1A2E] hover:bg-[#7D1A2E]/10 text-white/60 hover:text-white transition-all text-base">
                        <i class="ti ti-bell"></i>
                    </a>
                    @php $unread = auth()->user()->notifications()->unread()->count() @endphp
                    @if($unread > 0)
                        <span class="absolute -top-1 -right-1 bg-[#C0392B] text-white text-[9px] font-bold w-4 h-4 rounded-full flex items-center justify-center">
                            {{ $unread > 9 ? '9+' : $unread }}
                        </span>
                    @endif
                </div>

                {{-- Bookmark --}}
                <a href="{{ route('bookmarks.index') }}"
                   class="w-9 h-9 flex items-center justify-center rounded-lg bg-white/5 border border-white/10 hover:border-[#7D1A2E] hover:bg-[#7D1A2E]/10 text-white/60 hover:text-white transition-all text-base">
                    <i class="ti ti-bookmark"></i>
                </a>

                {{-- User Dropdown --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                            class="flex items-center gap-2 bg-white/5 border border-white/10 hover:border-[#7D1A2E] rounded-lg px-3 h-9 text-sm text-white/70 hover:text-white transition-all">
                        @if(auth()->user()->avatar)
                            <img src="{{ Storage::url(auth()->user()->avatar) }}" class="w-5 h-5 rounded-full object-cover">
                        @else
                            <span class="w-5 h-5 rounded-full bg-[#7D1A2E] flex items-center justify-center text-[10px] font-bold text-white">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                        @endif
                        <span class="max-w-[80px] truncate text-xs font-medium">{{ auth()->user()->name }}</span>
                        <i class="ti ti-chevron-down text-xs"></i>
                    </button>

                    <div x-show="open" @click.outside="open = false" x-transition
                         class="absolute right-0 top-11 w-52 bg-[#1a0a0d] border border-white/10 rounded-xl shadow-2xl overflow-hidden z-50">
                        <div class="px-4 py-3 border-b border-white/5">
                            <p class="text-xs font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                            <p class="text-[11px] text-white/40 truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <div class="py-1">
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-white/60 hover:text-white hover:bg-white/5 transition-colors">
                                <i class="ti ti-user text-sm"></i> Profil Saya
                            </a>
                            <a href="{{ route('bookmarks.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-white/60 hover:text-white hover:bg-white/5 transition-colors">
                                <i class="ti ti-bookmark text-sm"></i> Tersimpan
                            </a>
                            @if(auth()->user()->isSeller())
                                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-white/60 hover:text-white hover:bg-white/5 transition-colors">
                                    <i class="ti ti-layout-dashboard text-sm"></i> Dashboard Penjual
                                </a>
                            @elseif(auth()->user()->isPendingSeller())
                                <a href="{{ route('seller.status') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-yellow-400/80 hover:text-yellow-300 hover:bg-white/5 transition-colors">
                                    <i class="ti ti-clock text-sm"></i> Status Verifikasi
                                </a>
                            @else
                                <a href="{{ route('seller.register') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-[#ff7a7a] hover:text-white hover:bg-white/5 transition-colors">
                                    <i class="ti ti-store text-sm"></i> Jadi Penjual
                                </a>
                            @endif
                            @if(auth()->user()->isAdmin())
                                <div class="border-t border-white/5 mt-1 pt-1">
                                    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-purple-400 hover:text-purple-300 hover:bg-white/5 transition-colors">
                                        <i class="ti ti-shield text-sm"></i> Admin Panel
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div class="border-t border-white/5 py-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-white/40 hover:text-red-400 hover:bg-white/5 transition-colors">
                                    <i class="ti ti-logout text-sm"></i> Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Pasang Iklan (seller only) --}}
                @if(auth()->user()->isSeller())
                    <a href="{{ route('listings.create') }}"
                       class="flex items-center gap-2 bg-[#7D1A2E] hover:bg-[#9B2035] text-white text-sm font-700 px-4 h-9 rounded-lg transition-colors shrink-0">
                        <i class="ti ti-plus font-bold"></i>
                        <span>Pasang Iklan</span>
                    </a>
                @endif

            @else
                <a href="{{ route('login') }}"
                   class="px-4 h-9 flex items-center text-sm text-white/60 hover:text-white border border-white/10 hover:border-white/30 rounded-lg transition-all">
                    Masuk
                </a>
                <a href="{{ route('register') }}"
                   class="px-4 h-9 flex items-center text-sm font-semibold text-white bg-[#7D1A2E] hover:bg-[#9B2035] rounded-lg transition-colors">
                    Daftar
                </a>
            @endauth
        </div>
    </div>
</nav>

{{-- ===================== CATEGORY BAR ===================== --}}
<div class="bg-[#0f0608] border-b border-white/5">
    <div class="max-w-7xl mx-auto px-6 flex gap-0 overflow-x-auto scrollbar-hide">
        @php
            $categories = \App\Models\Category::whereNull('parent_id')->orderBy('sort_order')->get();
            $activeCategory = request()->route('category');
        @endphp
        <a href="{{ route('home') }}"
           class="flex items-center gap-2 px-4 py-3 text-xs whitespace-nowrap border-b-2 transition-all
                  {{ !request()->routeIs('listings.category') ? 'border-[#C0392B] text-white font-semibold' : 'border-transparent text-white/40 hover:text-white/70' }}">
            <i class="ti ti-home text-sm"></i> Semua
        </a>
        @foreach($categories as $cat)
            <a href="{{ route('listings.category', $cat->slug) }}"
               class="flex items-center gap-2 px-4 py-3 text-xs whitespace-nowrap border-b-2 transition-all
                      {{ isset($activeCategory) && $activeCategory->id === $cat->id ? 'border-[#C0392B] text-white font-semibold' : 'border-transparent text-white/40 hover:text-white/70' }}">
                <i class="ti {{ $cat->icon }} text-sm"></i>
                {{ $cat->name }}
            </a>
        @endforeach
    </div>
</div>

{{-- ===================== FLASH MESSAGES ===================== --}}
@if(session('success'))
    <div class="bg-green-900/40 border border-green-500/20 text-green-300 text-sm px-6 py-3 flex items-center gap-2">
        <i class="ti ti-circle-check"></i> {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="bg-red-900/40 border border-red-500/20 text-red-300 text-sm px-6 py-3 flex items-center gap-2">
        <i class="ti ti-circle-x"></i> {{ session('error') }}
    </div>
@endif

{{-- ===================== MAIN CONTENT ===================== --}}
<main>
    @yield('content')
</main>

{{-- ===================== FOOTER ===================== --}}
<footer class="bg-[#0a0305] border-t border-white/5 mt-16">
    <div class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-4 gap-8">
        <div class="col-span-1">
            <div class="text-xl font-black text-white tracking-tight mb-2">
                Marasa<span class="text-[#C0392B]">.id</span>
            </div>
            <p class="text-xs text-white/30 italic mb-4">Dari UMKM, oleh kita, untuk Indonesia</p>
            <p class="text-xs text-white/25 leading-relaxed">
                Platform iklan jual beli produk lokal, menghubungkan UMKM dengan pembeli di seluruh Indonesia.
            </p>
        </div>
        <div>
            <h4 class="text-xs font-semibold text-white/60 uppercase tracking-wider mb-4">Jelajahi</h4>
            <ul class="space-y-2">
                <li><a href="{{ route('home') }}" class="text-sm text-white/35 hover:text-white transition-colors">Semua Iklan</a></li>
                @foreach($categories->take(4) as $cat)
                    <li><a href="{{ route('listings.category', $cat->slug) }}" class="text-sm text-white/35 hover:text-white transition-colors">{{ $cat->name }}</a></li>
                @endforeach
            </ul>
        </div>
        <div>
            <h4 class="text-xs font-semibold text-white/60 uppercase tracking-wider mb-4">Penjual</h4>
            <ul class="space-y-2">
                <li><a href="{{ route('seller.register') }}" class="text-sm text-white/35 hover:text-white transition-colors">Daftar Jadi Penjual</a></li>
                <li><a href="{{ route('listings.create') }}" class="text-sm text-white/35 hover:text-white transition-colors">Pasang Iklan</a></li>
                @auth
                    @if(auth()->user()->isSeller())
                        <li><a href="{{ route('dashboard') }}" class="text-sm text-white/35 hover:text-white transition-colors">Dashboard</a></li>
                    @endif
                @endauth
            </ul>
        </div>
        <div>
            <h4 class="text-xs font-semibold text-white/60 uppercase tracking-wider mb-4">Bantuan</h4>
            <ul class="space-y-2">
                <li><a href="#" class="text-sm text-white/35 hover:text-white transition-colors">Cara Beli</a></li>
                <li><a href="#" class="text-sm text-white/35 hover:text-white transition-colors">Cara Jual</a></li>
                <li><a href="#" class="text-sm text-white/35 hover:text-white transition-colors">Kebijakan</a></li>
            </ul>
        </div>
    </div>
    <div class="border-t border-white/5 px-6 py-4 max-w-7xl mx-auto flex items-center justify-between">
        <p class="text-xs text-white/20">© {{ date('Y') }} Marasa.id — All rights reserved</p>
        <p class="text-xs text-white/15">Made with ❤️ untuk UMKM Indonesia</p>
    </div>
</footer>

{{-- Alpine.js untuk dropdown --}}
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

@stack('scripts')
</body>
</html>