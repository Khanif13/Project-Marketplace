<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — Marasa.id</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#f5f0ef] font-sans antialiased">

    <div class="flex min-h-screen">

        {{-- ===== SIDEBAR ===== --}}
        <aside class="w-56 bg-[#0f0608] flex flex-col shrink-0 fixed top-0 left-0 h-screen z-40">

            {{-- Logo --}}
            <div class="px-5 py-5 border-b border-white/5">
                <a href="{{ route('home') }}" class="block">
                    <div class="text-lg font-black text-white tracking-tight">
                        Marasa<span class="text-[#C0392B]">.id</span>
                    </div>
                    <div class="text-[9px] text-white/30 mt-0.5">Admin Panel</div>
                </a>
            </div>

            {{-- Nav --}}
            <nav class="flex-1 px-3 py-4 flex flex-col gap-0.5 overflow-y-auto">
                @php
                    $navItems = [
                        ['route' => 'admin.users.index', 'icon' => 'ti-users', 'label' => 'Pengguna'],
                        ['route' => 'admin.listings.index', 'icon' => 'ti-layout-grid', 'label' => 'Iklan'],
                        ['route' => 'admin.categories.index', 'icon' => 'ti-category', 'label' => 'Kategori'],
                        [
                            'route' => 'admin.verifications.index',
                            'icon' => 'ti-rosette-discount-check',
                            'label' => 'Verifikasi',
                        ],
                        ['route' => 'admin.reports.index', 'icon' => 'ti-flag', 'label' => 'Laporan'],
                    ];
                @endphp

                @foreach ($navItems as $item)
                    @php
                        $isActive =
                            request()->routeIs($item['route']) ||
                            request()->routeIs(str_replace('index', '*', $item['route']));
                    @endphp
                    <a href="{{ route($item['route']) }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all
                          {{ $isActive ? 'bg-[#7D1A2E] text-white font-semibold' : 'text-white/50 hover:text-white hover:bg-white/5' }}">
                        <i class="ti {{ $item['icon'] }} text-base"></i>
                        {{ $item['label'] }}

                        {{-- Badge pending verifikasi --}}
                        @if ($item['route'] === 'admin.verifications.index')
                            @php $pendingVerif = \App\Models\SellerVerification::where('status','pending')->count() @endphp
                            @if ($pendingVerif > 0)
                                <span
                                    class="ml-auto bg-[#C0392B] text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full">
                                    {{ $pendingVerif }}
                                </span>
                            @endif
                        @endif

                        {{-- Badge pending laporan --}}
                        @if ($item['route'] === 'admin.reports.index')
                            @php $pendingReports = \App\Models\Report::where('status','pending')->count() @endphp
                            @if ($pendingReports > 0)
                                <span
                                    class="ml-auto bg-orange-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full">
                                    {{ $pendingReports }}
                                </span>
                            @endif
                        @endif
                    </a>
                @endforeach
            </nav>

            {{-- Footer sidebar --}}
            <div class="px-3 py-4 border-t border-white/5">
                <a href="{{ route('home') }}"
                    class="flex items-center gap-2 px-3 py-2 text-xs text-white/40 hover:text-white transition-colors rounded-lg hover:bg-white/5">
                    <i class="ti ti-arrow-left text-sm"></i> Ke Marasa.id
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-2 px-3 py-2 text-xs text-white/40 hover:text-red-400 transition-colors rounded-lg hover:bg-white/5">
                        <i class="ti ti-logout text-sm"></i> Keluar
                    </button>
                </form>
            </div>
        </aside>

        {{-- ===== MAIN ===== --}}
        <div class="flex-1 ml-56 flex flex-col min-h-screen">

            {{-- Topbar --}}
            <header class="bg-white border-b border-[#ede5e6] h-14 flex items-center px-6 gap-4 sticky top-0 z-30">
                <h1 class="text-sm font-bold text-[#1a0a0e]">@yield('page-title', 'Dashboard')</h1>
                <div class="ml-auto flex items-center gap-3">
                    <span class="text-xs text-[#aaa]">{{ auth()->user()->name }}</span>
                    <div
                        class="w-8 h-8 rounded-full bg-[#7D1A2E]/10 flex items-center justify-center text-[#7D1A2E] font-black text-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>
            </header>

            {{-- Flash --}}
            @if (session('success'))
                <div
                    class="bg-green-50 border-b border-green-200 text-green-700 text-sm px-6 py-3 flex items-center gap-2">
                    <i class="ti ti-circle-check"></i> {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-50 border-b border-red-200 text-red-700 text-sm px-6 py-3 flex items-center gap-2">
                    <i class="ti ti-circle-x"></i> {{ session('error') }}
                </div>
            @endif

            {{-- Content --}}
            <main class="flex-1 p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @stack('scripts')
</body>

</html>
