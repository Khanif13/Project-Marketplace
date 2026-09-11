@props(['title', 'link' => null, 'linkText' => 'Lihat semua →'])

<div class="flex items-center justify-between mb-5">
    <h2 class="text-sm font-bold text-[#1a0a0e] flex items-center gap-2">
        <span class="w-1 h-4 bg-[#7D1A2E] rounded-full inline-block"></span>
        {{ $title }}
    </h2>
    @if ($link)
        <a href="{{ $link }}" class="text-xs text-[#7D1A2E] font-semibold hover:underline">
            {{ $linkText }}
        </a>
    @endif
</div>
