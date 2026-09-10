<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Marasa.id') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-[#0f0608] min-h-screen flex flex-col items-center justify-center px-4 py-12">

    {{-- Logo --}}
    <a href="{{ route('home') }}" class="flex flex-col items-center mb-8">
        <span class="text-3xl font-black text-white tracking-tight">
            Marasa<span class="text-[#C0392B]">.id</span>
        </span>
        <span class="text-[10px] text-white/30 mt-1 tracking-wide">Dari UMKM, oleh kita, untuk Indonesia</span>
    </a>

    {{-- Card --}}
    <div class="w-full max-w-md bg-[#1a0a0e] border border-white/10 rounded-2xl shadow-2xl p-8">
        {{ $slot }}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>

</html>
