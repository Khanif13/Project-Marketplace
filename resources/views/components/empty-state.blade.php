@props(['icon' => 'ti-inbox', 'title' => 'Tidak ada data', 'description' => null])

<div class="bg-white border border-dashed border-[#ede5e6] rounded-2xl p-16 text-center">
    <i class="ti {{ $icon }} text-5xl text-[#ddd] block mb-4"></i>
    <p class="text-sm font-semibold text-[#888] mb-1">{{ $title }}</p>
    @if ($description)
        <p class="text-xs text-[#aaa] mb-4">{{ $description }}</p>
    @endif
    {{ $slot }}
</div>
