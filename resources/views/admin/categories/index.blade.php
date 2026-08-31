@extends('admin.layouts.app')

@section('title', 'Manajemen Kategori')
@section('page-title', 'Manajemen Kategori')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-[#aaa]">{{ $categories->count() }} kategori utama</p>
        <a href="{{ route('admin.categories.create') }}"
            class="inline-flex items-center gap-2 bg-[#7D1A2E] hover:bg-[#9B2035] text-white text-sm font-bold px-4 py-2.5 rounded-xl transition-colors">
            <i class="ti ti-plus"></i> Tambah Kategori
        </a>
    </div>

    <div class="flex flex-col gap-4">
        @forelse($categories as $cat)
            <div class="bg-white border border-[#ede5e6] rounded-2xl overflow-hidden">

                {{-- Parent --}}
                <div class="flex items-center gap-4 px-5 py-4 border-b border-[#f5f0ef]">
                    <div class="w-9 h-9 rounded-xl bg-[#7D1A2E]/10 flex items-center justify-center">
                        <i class="ti {{ $cat->icon ?? 'ti-folder' }} text-base text-[#7D1A2E]"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-bold text-[#1a0a0e]">{{ $cat->name }}</p>
                        <p class="text-xs text-[#aaa]">{{ $cat->listings_count }} iklan · {{ $cat->children->count() }}
                            sub-kategori</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.categories.edit', $cat->id) }}"
                            class="w-7 h-7 flex items-center justify-center border border-[#ede5e6] rounded-lg text-[#888] hover:border-[#7D1A2E] hover:text-[#7D1A2E] transition-all text-xs">
                            <i class="ti ti-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.categories.destroy', $cat->id) }}"
                            onsubmit="return confirm('Hapus kategori {{ $cat->name }}?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="w-7 h-7 flex items-center justify-center border border-[#ede5e6] rounded-lg text-[#888] hover:border-red-400 hover:text-red-400 transition-all text-xs">
                                <i class="ti ti-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Children --}}
                @if ($cat->children->count())
                    <div class="divide-y divide-[#f5f0ef]">
                        @foreach ($cat->children as $child)
                            <div class="flex items-center gap-4 px-5 py-3 pl-14">
                                <div class="flex-1">
                                    <p class="text-sm text-[#555]">{{ $child->name }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.categories.edit', $child->id) }}"
                                        class="w-6 h-6 flex items-center justify-center border border-[#ede5e6] rounded-lg text-[#888] hover:border-[#7D1A2E] hover:text-[#7D1A2E] transition-all text-xs">
                                        <i class="ti ti-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.categories.destroy', $child->id) }}"
                                        onsubmit="return confirm('Hapus sub-kategori ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="w-6 h-6 flex items-center justify-center border border-[#ede5e6] rounded-lg text-[#888] hover:border-red-400 hover:text-red-400 transition-all text-xs">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-white border border-dashed border-[#ede5e6] rounded-2xl p-12 text-center">
                <i class="ti ti-category text-5xl text-[#ddd] block mb-4"></i>
                <p class="text-sm text-[#aaa]">Belum ada kategori.</p>
            </div>
        @endforelse
    </div>

@endsection
