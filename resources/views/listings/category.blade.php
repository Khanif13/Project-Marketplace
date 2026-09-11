@extends('layouts.app')

@section('title', $category->name)

@section('content')
    <div class="bg-[#f5f0ef] min-h-screen">
        <div class="max-w-7xl mx-auto px-6 py-8">

            <div class="flex items-center gap-3 mb-8">
                <div class="w-10 h-10 bg-[#7D1A2E]/10 rounded-xl flex items-center justify-center">
                    <i class="ti {{ $category->icon ?? 'ti-folder' }} text-lg text-[#7D1A2E]"></i>
                </div>
                <div>
                    <h1 class="text-lg font-black text-[#1a0a0e]">{{ $category->name }}</h1>
                    <p class="text-xs text-[#aaa]">{{ $listings->total() }} iklan ditemukan</p>
                </div>
            </div>

            @if ($category->children->count())
                <div class="flex gap-2 flex-wrap mb-6">
                    @foreach ($category->children as $child)
                        <a href="{{ route('listings.category', $child->slug) }}"
                            class="bg-white border border-[#ede5e6] hover:border-[#7D1A2E] hover:text-[#7D1A2E] text-[#555] text-xs font-medium px-4 py-2 rounded-full transition-all">
                            {{ $child->name }}
                        </a>
                    @endforeach
                </div>
            @endif

            @if ($listings->count())
                <div class="grid grid-cols-4 gap-4 mb-6">
                    @foreach ($listings as $listing)
                        <x-listing-card :listing="$listing" />
                    @endforeach
                </div>
                {{ $listings->links() }}
            @else
                <x-empty-state icon="ti-tag" title="Belum ada iklan di kategori ini." />
            @endif

        </div>
    </div>
@endsection
