@extends('admin.layouts.app')

@section('title', 'Verifikasi Penjual')
@section('page-title', 'Verifikasi Penjual')

@section('content')

    {{-- Stats --}}
    <div class="grid grid-cols-4 gap-4 mb-6">
        @foreach ([['label' => 'Total', 'value' => $stats['total'], 'color' => 'text-[#7D1A2E]', 'icon' => 'ti-list'], ['label' => 'Pending', 'value' => $stats['pending'], 'color' => 'text-yellow-500', 'icon' => 'ti-clock'], ['label' => 'Approved', 'value' => $stats['approved'], 'color' => 'text-green-600', 'icon' => 'ti-circle-check'], ['label' => 'Rejected', 'value' => $stats['rejected'], 'color' => 'text-red-500', 'icon' => 'ti-circle-x']] as $s)
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
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            @if (request('status'))
                <a href="{{ route('admin.verifications.index') }}" class="text-xs text-[#7D1A2E] font-semibold">Reset</a>
            @endif
        </form>
    </div>

    {{-- List --}}
    <div class="flex flex-col gap-3">
        @forelse($verifications as $v)
            <div class="bg-white border border-[#ede5e6] rounded-2xl p-5" x-data="{ rejectOpen: false }">
                <div class="flex items-start justify-between gap-4">

                    {{-- User Info --}}
                    <div class="flex items-start gap-3 flex-1">
                        <div
                            class="w-10 h-10 rounded-full bg-[#7D1A2E]/10 flex items-center justify-center text-[#7D1A2E] font-bold shrink-0">
                            {{ strtoupper(substr($v->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-[#1a0a0e]">{{ $v->user->name }}</p>
                            <p class="text-xs text-[#aaa]">{{ $v->user->email }}</p>
                            <div class="flex flex-col gap-1 mt-2 text-xs text-[#666]">
                                <span><i class="ti ti-store text-xs mr-1 text-[#aaa]"></i>{{ $v->user->store_name }}</span>
                                <span><i
                                        class="ti ti-map-pin text-xs mr-1 text-[#aaa]"></i>{{ $v->user->store_address }}</span>
                                <span><i
                                        class="ti ti-brand-whatsapp text-xs mr-1 text-[#aaa]"></i>{{ $v->user->store_wa }}</span>
                            </div>
                            @if ($v->ktp_photo)
                                <a href="{{ Storage::url($v->ktp_photo) }}" target="_blank"
                                    class="inline-flex items-center gap-1 text-xs text-[#7D1A2E] font-semibold mt-2 hover:underline">
                                    <i class="ti ti-id-badge"></i> Lihat Foto KTP
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- Status + Actions --}}
                    <div class="flex flex-col items-end gap-3 shrink-0">
                        <span
                            class="text-xs font-bold px-3 py-1 rounded-full
                        {{ $v->status === 'approved'
                            ? 'bg-green-50 text-green-700 border border-green-200'
                            : ($v->status === 'pending'
                                ? 'bg-yellow-50 text-yellow-700 border border-yellow-200'
                                : 'bg-red-50 text-red-700 border border-red-200') }}">
                            {{ $v->status_label }}
                        </span>
                        <p class="text-[10px] text-[#aaa]">{{ $v->created_at->format('d M Y, H:i') }}</p>

                        @if ($v->status === 'pending')
                            <div class="flex gap-2">
                                {{-- Approve --}}
                                <form method="POST" action="{{ route('admin.verifications.approve', $v->id) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                        class="inline-flex items-center gap-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold px-4 py-2 rounded-lg transition-colors">
                                        <i class="ti ti-check"></i> Setujui
                                    </button>
                                </form>

                                {{-- Reject --}}
                                <button @click="rejectOpen = !rejectOpen"
                                    class="inline-flex items-center gap-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold px-4 py-2 rounded-lg transition-colors">
                                    <i class="ti ti-x"></i> Tolak
                                </button>
                            </div>

                            {{-- Form Reject --}}
                            <div x-show="rejectOpen" x-transition class="w-full mt-2">
                                <form method="POST" action="{{ route('admin.verifications.reject', $v->id) }}"
                                    class="flex flex-col gap-2">
                                    @csrf @method('PATCH')
                                    <textarea name="notes" rows="3" required placeholder="Alasan penolakan (wajib diisi)..."
                                        class="w-full border border-[#ede5e6] rounded-xl px-4 py-2.5 text-xs text-[#555] outline-none focus:border-red-400 resize-none"></textarea>
                                    <button type="submit"
                                        class="w-full bg-red-500 hover:bg-red-600 text-white text-xs font-semibold py-2 rounded-lg transition-colors">
                                        Konfirmasi Tolak
                                    </button>
                                </form>
                            </div>
                        @endif

                        @if ($v->notes)
                            <div class="bg-red-50 border border-red-100 rounded-lg px-3 py-2 text-xs text-[#666] max-w-xs">
                                <span class="font-semibold text-red-600">Catatan: </span>{{ $v->notes }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white border border-dashed border-[#ede5e6] rounded-2xl p-12 text-center">
                <i class="ti ti-rosette-discount-check text-5xl text-[#ddd] block mb-4"></i>
                <p class="text-sm text-[#aaa]">Tidak ada pengajuan verifikasi.</p>
            </div>
        @endforelse
    </div>

    @if ($verifications->hasPages())
        <div class="mt-4">{{ $verifications->links() }}</div>
    @endif

@endsection
