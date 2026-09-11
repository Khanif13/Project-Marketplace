@props(['label', 'value', 'icon', 'color' => 'text-[#7D1A2E]'])

<div class="bg-white border border-[#ede5e6] rounded-2xl p-4">
    <div class="flex items-center justify-between mb-2">
        <span class="text-xs text-[#aaa]">{{ $label }}</span>
        <i class="ti {{ $icon }} text-base {{ $color }}"></i>
    </div>
    <div class="text-2xl font-black text-[#1a0a0e]">{{ $value }}</div>
</div>
