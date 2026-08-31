@extends('admin.layouts.app')

@section('title', 'Manajemen Iklan')
@section('page-title', 'Manajemen Iklan')

@section('content')

    {{-- Stats --}}
    <div class="grid grid-cols-4 gap-4 mb-6">
        @foreach ([['label' => 'Total Iklan', 'value' => $stats['total'], 'color' => 'text-[#7D1A2E]', 'icon' => 'ti-layout-grid'], ['label' => 'Aktif', 'value' => $stats['active'], 'color' => 'text-green-600', 'icon' => 'ti-circle-check'], ['label' => 'Stok Habis', 'value' => $stats['empty'], 'color' => 'text-orange-500', 'icon' => 'ti-circle-minus'], ['label' => 'Nonaktif', 'value' => $stats['inactive'], 'color' => 'text-gray-400', 'icon' => 'ti-eye-off']] as $s)
            <div class="bg-white border border-[#ede5e6] rounded-2xl p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs text-[#aaa]">{{ $s['label'] }}</span>
                    <i class="ti {{ $s['icon'] }} {{ $s['color'] }} text-base"></i>
                </div>
                <div class="text-2xl font-black text-[#1a0a0e]">{{ $s['value'] }}</div>
            </div>
        @endforeach
    </div>

    {{-- Filter --}}
    <div class="bg-white border border-[#ede5e6] rounded-2xl p-4 mb-5">
        <form method="GET" class="flex items-center gap-3">
            <div class="flex-1 flex border border-[#ede5e6] rounded-lg overflow-hidden h-9 focus-within:border-[#7D1A2E]">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul iklan..."
                    class="flex-1 px-4 text-sm outline-none bg-transparent">
                <button type="submit" class="bg-[#7D1A2E] px-4 text-white text-sm">
                    <i class="ti ti-search"></i>
                </button>
            </div>
            <select name="status" onchange="this.form.submit()"
                class="border border-[#ede5e6] rounded-lg px-3 h-9 text-sm text-[#555] outline-none bg-white">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="empty" {{ request('status') === 'empty' ? 'selected' : '' }}>Stok Habis</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
            </select>
            @if (request()->anyFilled(['q', 'status']))
                <a href="{{ route('admin.listings.index') }}" class="text-xs text-[#7D1A2E] font-semibold">Reset</a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white border border-[#ede5e6] rounded-2xl overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-[#ede5e6] bg-[#faf5f6]">
                    <th class="text-left px-5 py-3 text-xs font-bold text-[#aaa] uppercase tracking-wider">Iklan</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-[#aaa] uppercase tracking-wider">Penjual</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-[#aaa] uppercase tracking-wider">Harga</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-[#aaa] uppercase tracking-wider">Status</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-[#aaa] uppercase tracking-wider">Dilihat</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-[#aaa] uppercase tracking-wider">Tanggal</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#f5f0ef]">
                @forelse($listings as $listing)
                    <tr class="hover:bg-[#faf5f6] transition-colors">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-[#f5edef] overflow-hidden shrink-0">
                                    @if ($listing->firstImage)
                                        <img src="{{ Storage::url($listing->firstImage->path) }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i class="ti ti-photo text-sm text-[#ddd]"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <a href="{{ route('listings.show', $listing->slug) }}" target="_blank"
                                        class="text-sm font-medium text-[#1a0a0e] hover:text-[#7D1A2E] line-clamp-1">
                                        {{ $listing->title }}
                                    </a>
                                    <p class="text-xs text-[#aaa]">{{ $listing->category->name }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 text-xs text-[#555]">{{ $listing->user->name }}</td>
                        <td class="px-5 py-3.5 text-sm font-bold text-[#7D1A2E]">
                            Rp {{ number_format($listing->price, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-3.5">
                            <span
                                class="text-xs font-semibold px-2 py-0.5 rounded-full
                            {{ $listing->status === 'active'
                                ? 'bg-green-50 text-green-700 border border-green-200'
                                : ($listing->status === 'empty'
                                    ? 'bg-orange-50 text-orange-700 border border-orange-200'
                                    : 'bg-gray-100 text-gray-500 border border-gray-200') }}">
                                {{ $listing->status_label }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-xs text-[#555]">{{ number_format($listing->view_count) }}x</td>
                        <td class="px-5 py-3.5 text-xs text-[#aaa]">{{ $listing->created_at->format('d M Y') }}</td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-2 justify-end">
                                <a href="{{ route('listings.show', $listing->slug) }}" target="_blank"
                                    class="w-7 h-7 flex items-center justify-center border border-[#ede5e6] rounded-lg text-[#888] hover:border-[#7D1A2E] hover:text-[#7D1A2E] transition-all text-xs">
                                    <i class="ti ti-eye"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.listings.destroy', $listing->id) }}"
                                    onsubmit="return confirm('Hapus iklan ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="w-7 h-7 flex items-center justify-center border border-[#ede5e6] rounded-lg text-[#888] hover:border-red-400 hover:text-red-400 transition-all text-xs">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center text-sm text-[#aaa]">
                            Tidak ada iklan ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if ($listings->hasPages())
            <div class="px-5 py-4 border-t border-[#ede5e6]">{{ $listings->links() }}</div>
        @endif
    </div>

@endsection
