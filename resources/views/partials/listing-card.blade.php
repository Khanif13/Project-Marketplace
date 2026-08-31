@php
    $firstImage = $listing->firstImage;
    $isBookmarked = auth()->check() && auth()->user()->bookmarkedListings->contains($listing->id);
@endphp

<div class="bg-white rounded-xl overflow-hidden border border-[#ede5e6] hover:border-[#7D1A2E] hover:shadow-lg hover:shadow-[#7D1A2E]/8 transition-all group cursor-pointer"
    onclick="window.location='{{ route('listings.show', $listing->slug) }}'">

    {{-- Gambar --}}
    <div class="h-[148px] bg-[#f5edef] relative overflow-hidden">
        @if ($firstImage)
            <img src="{{ Storage::url($firstImage->path) }}" alt="{{ $listing->title }}"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
        @else
            <div class="w-full h-full flex items-center justify-center">
                <i class="ti ti-photo text-4xl text-[#ddd]"></i>
            </div>
        @endif

        {{-- Badge kondisi --}}
        <span
            class="absolute top-2 left-2 text-[10px] font-bold px-2 py-0.5 rounded
                     {{ $listing->condition === 'new' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
            {{ $listing->condition === 'new' ? 'Baru' : 'Bekas' }}
        </span>

        {{-- Badge stok kosong --}}
        @if ($listing->status === 'empty')
            <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                <span class="bg-white/90 text-gray-700 text-xs font-bold px-3 py-1 rounded">Stok Habis</span>
            </div>
        @endif

        {{-- Bookmark button --}}
        @auth
            <form method="POST" action="{{ route('bookmarks.toggle', $listing->id) }}" class="absolute top-2 right-2"
                onclick="event.stopPropagation()">
                @csrf
                <button type="submit"
                    class="w-8 h-8 flex items-center justify-center bg-white border border-[#ede5e6] rounded-lg shadow-sm hover:border-[#7D1A2E] hover:text-[#7D1A2E] transition-all text-sm
                               {{ $isBookmarked ? 'text-[#7D1A2E]' : 'text-gray-400' }}">
                    <i class="ti {{ $isBookmarked ? 'ti-bookmark-filled' : 'ti-bookmark' }}"></i>
                </button>
            </form>
        @endauth
    </div>

    {{-- Body --}}
    <div class="p-3">
        <h3 class="text-[13px] font-500 text-[#1a0a0e] leading-snug mb-1.5 line-clamp-2">
            {{ $listing->title }}
        </h3>

        <div class="flex items-center gap-1.5 mb-2 flex-wrap">
            <span class="text-[15px] font-black text-[#7D1A2E]">
                Rp {{ number_format($listing->price, 0, ',', '.') }}
            </span>
            @if ($listing->is_negotiable)
                <span
                    class="text-[9px] font-bold bg-yellow-50 text-yellow-700 border border-yellow-200 px-1.5 py-0.5 rounded">
                    Nego
                </span>
            @endif
            @if ($listing->stock !== null)
                <span class="text-[9px] font-semibold bg-[#f5edef] text-[#7D1A2E] px-1.5 py-0.5 rounded">
                    Stok: {{ $listing->stock }}
                </span>
            @endif
        </div>

        <div class="flex items-center gap-1 text-[11px] text-[#bbb]">
            <i class="ti ti-map-pin text-xs"></i>
            <span class="truncate">{{ Str::limit($listing->address, 20) }}</span>
            <span class="w-1 h-1 bg-[#e0d0d2] rounded-full mx-0.5"></span>
            <span class="shrink-0">{{ $listing->created_at->diffForHumans() }}</span>
        </div>
    </div>
</div>
