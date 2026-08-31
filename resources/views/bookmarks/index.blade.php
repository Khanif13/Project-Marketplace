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
                        @include('partials.listing-card', ['listing' => $listing])
                    @endforeach
                </div>
                {{ $listings->links() }}
            @else
                <div class="bg-white border border-dashed border-[#ede5e6] rounded-2xl p-16 text-center">
                    <i class="ti ti-bookmark text-5xl text-[#ddd] block mb-4"></i>
                    <p class="text-sm font-semibold text-[#888] mb-1">Belum ada iklan tersimpan</p>
                    <p class="text-xs text-[#aaa] mb-6">Klik ikon bookmark di iklan untuk menyimpannya</p>
                    <a href="{{ route('home') }}"
                        class="inline-flex items-center gap-2 bg-[#7D1A2E] hover:bg-[#9B2035] text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-colors">
                        <i class="ti ti-compass"></i> Jelajahi Iklan
                    </a>
                </div>
            @endif

        </div>
    </div>
@endsection
