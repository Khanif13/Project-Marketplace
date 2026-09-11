@extends('layouts.app')

@section('title', 'Iklan Tersimpan')

@section('content')
    <div class="bg-[#f5f0ef] min-h-screen">
        <div class="max-w-7xl mx-auto px-6 py-8">

            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-xl font-black text-[#1a0a0e]">Iklan Tersimpan</h1>
                    <p class="text-sm text-[#aaa] mt-0.5">{{ $listings->total() }} iklan tersimpan</p>
                </div>
            </div>

            @if ($listings->count())
                <div class="grid grid-cols-4 gap-4 mb-6">
                    @foreach ($listings as $listing)
                        <x-listing-card :listing="$listing" />
                    @endforeach
                </div>
                {{ $listings->links() }}
            @else
                <x-empty-state icon="ti-bookmark" title="Belum ada iklan tersimpan"
                    description="Klik ikon bookmark di iklan untuk menyimpannya">
                    <a href="{{ route('home') }}"
                        class="inline-flex items-center gap-2 mt-2 bg-[#7D1A2E] hover:bg-[#9B2035] text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-colors">
                        <i class="ti ti-compass"></i> Jelajahi Iklan
                    </a>
                </x-empty-state>
            @endif

        </div>
    </div>
@endsection
