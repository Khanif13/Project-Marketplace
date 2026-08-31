@extends('admin.layouts.app')

@section('title', 'Laporan Iklan')
@section('page-title', 'Laporan Iklan')

@section('content')

    {{-- Stats --}}
    <div class="grid grid-cols-4 gap-4 mb-6">
        @foreach ([['label' => 'Total', 'value' => $stats['total'], 'color' => 'text-[#7D1A2E]', 'icon' => 'ti-flag'], ['label' => 'Pending', 'value' => $stats['pending'], 'color' => 'text-orange-500', 'icon' => 'ti-clock'], ['label' => 'Reviewed', 'value' => $stats['reviewed'], 'color' => 'text-blue-500', 'icon' => 'ti-eye'], ['label' => 'Resolved', 'value' => $stats['resolved'], 'color' => 'text-green-600', 'icon' => 'ti-circle-check']] as $s)
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
            <select name="status" onchange="this.form.submit()"
                class="border border-[#ede5e6] rounded-lg px-3 h-9 text-sm text-[#555] outline-none focus:border-[#7D1A2E] bg-white">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="reviewed" {{ request('status') === 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
            </select>
            <select name="reason" onchange="this.form.submit()"
                class="border border-[#ede5e6] rounded-lg px-3 h-9 text-sm text-[#555] outline-none focus:border-[#7D1A2E] bg-white">
                <option value="">Semua Alasan</option>
                <option value="spam" {{ request('reason') === 'spam' ? 'selected' : '' }}>Spam</option>
                <option value="fraud" {{ request('reason') === 'fraud' ? 'selected' : '' }}>Penipuan</option>
                <option value="prohibited" {{ request('reason') === 'prohibited' ? 'selected' : '' }}>Konten Terlarang
                </option>
                <option value="other" {{ request('reason') === 'other' ? 'selected' : '' }}>Lainnya</option>
            </select>
            @if (request()->anyFilled(['status', 'reason']))
                <a href="{{ route('admin.reports.index') }}" class="text-xs text-[#7D1A2E] font-semibold">Reset</a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white border border-[#ede5e6] rounded-2xl overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-[#ede5e6] bg-[#faf5f6]">
                    <th class="text-left px-5 py-3 text-xs font-bold text-[#aaa] uppercase tracking-wider">Iklan Dilaporkan
                    </th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-[#aaa] uppercase tracking-wider">Pelapor</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-[#aaa] uppercase tracking-wider">Alasan</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-[#aaa] uppercase tracking-wider">Status</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-[#aaa] uppercase tracking-wider">Tanggal</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#f5f0ef]">
                @forelse($reports as $report)
                    <tr class="hover:bg-[#faf5f6] transition-colors">
                        <td class="px-5 py-3.5">
                            @if ($report->listing)
                                <a href="{{ route('listings.show', $report->listing->slug) }}" target="_blank"
                                    class="text-sm font-medium text-[#1a0a0e] hover:text-[#7D1A2E] line-clamp-1">
                                    {{ $report->listing->title }}
                                </a>
                            @else
                                <span class="text-xs text-[#aaa] italic">Iklan sudah dihapus</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-xs text-[#555]">{{ $report->reporter->name ?? '—' }}</td>
                        <td class="px-5 py-3.5">
                            <span
                                class="text-xs font-semibold bg-orange-50 text-orange-700 border border-orange-200 px-2 py-0.5 rounded-full">
                                {{ $report->reason_label }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5">
                            <span
                                class="text-xs font-semibold px-2 py-0.5 rounded-full
                            {{ $report->status === 'resolved'
                                ? 'bg-green-50 text-green-700 border border-green-200'
                                : ($report->status === 'reviewed'
                                    ? 'bg-blue-50 text-blue-700 border border-blue-200'
                                    : 'bg-yellow-50 text-yellow-700 border border-yellow-200') }}">
                                {{ ucfirst($report->status) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-xs text-[#aaa]">{{ $report->created_at->format('d M Y') }}</td>
                        <td class="px-5 py-3.5">
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open"
                                    class="w-7 h-7 flex items-center justify-center border border-[#ede5e6] rounded-lg text-[#888] hover:border-[#7D1A2E] hover:text-[#7D1A2E] transition-all text-xs">
                                    <i class="ti ti-dots-vertical"></i>
                                </button>
                                <div x-show="open" @click.outside="open = false"
                                    class="absolute right-0 top-8 bg-white border border-[#ede5e6] rounded-xl shadow-lg w-36 z-10 overflow-hidden">
                                    @foreach (['pending' => 'Pending', 'reviewed' => 'Reviewed', 'resolved' => 'Resolved'] as $val => $label)
                                        @if ($report->status !== $val)
                                            <form method="POST" action="{{ route('admin.reports.status', $report->id) }}">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="{{ $val }}">
                                                <button type="submit"
                                                    class="w-full text-left px-4 py-2 text-xs text-[#555] hover:bg-[#f5edef] hover:text-[#7D1A2E] transition-colors">
                                                    Tandai {{ $label }}
                                                </button>
                                            </form>
                                        @endif
                                    @endforeach
                                    @if ($report->listing)
                                        <div class="border-t border-[#ede5e6]">
                                            <form method="POST"
                                                action="{{ route('admin.listings.destroy', $report->listing->id) }}"
                                                onsubmit="return confirm('Hapus iklan ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="w-full text-left px-4 py-2 text-xs text-red-500 hover:bg-red-50 transition-colors">
                                                    Hapus Iklan
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-sm text-[#aaa]">
                            Tidak ada laporan ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if ($reports->hasPages())
            <div class="px-5 py-4 border-t border-[#ede5e6]">{{ $reports->links() }}</div>
        @endif
    </div>

@endsection
