@extends('layouts.app')

@section('title', 'Status Verifikasi Penjual')

@section('content')
    <div class="bg-[#f5f0ef] min-h-screen flex items-center justify-center px-6 py-12">
        <div class="w-full max-w-md">

            @php
                $status = $verification?->status ?? $user->seller_status;
            @endphp

            {{-- PENDING --}}
            @if ($status === 'pending')
                <div class="bg-white border border-[#ede5e6] rounded-2xl p-8 text-center">
                    <div class="w-16 h-16 bg-yellow-50 rounded-full flex items-center justify-center mx-auto mb-5">
                        <i class="ti ti-clock text-2xl text-yellow-500"></i>
                    </div>
                    <h1 class="text-lg font-bold text-[#1a0a0e] mb-2">Sedang Diverifikasi</h1>
                    <p class="text-sm text-[#888] mb-6 leading-relaxed">
                        Pendaftaran kamu sedang ditinjau oleh tim Marasa.id.
                        Proses verifikasi membutuhkan waktu hingga <strong>1x24 jam</strong>.
                    </p>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6 text-left">
                        <p class="text-xs font-bold text-yellow-700 mb-2">Info Toko yang Didaftarkan</p>
                        <div class="flex flex-col gap-1.5 text-xs text-[#666]">
                            <div class="flex items-center gap-2">
                                <i class="ti ti-store text-xs text-[#aaa]"></i>
                                <span>{{ $user->store_name }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="ti ti-map-pin text-xs text-[#aaa]"></i>
                                <span>{{ $user->store_address }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="ti ti-brand-whatsapp text-xs text-[#aaa]"></i>
                                <span>{{ $user->store_wa }}</span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('home') }}"
                        class="inline-flex items-center gap-2 text-sm text-[#7D1A2E] font-semibold hover:underline">
                        <i class="ti ti-arrow-left text-sm"></i> Kembali ke Beranda
                    </a>
                </div>

                {{-- APPROVED --}}
            @elseif($status === 'approved' || $user->isSeller())
                <div class="bg-white border border-[#ede5e6] rounded-2xl p-8 text-center">
                    <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-5">
                        <i class="ti ti-rosette-discount-check text-2xl text-green-500"></i>
                    </div>
                    <h1 class="text-lg font-bold text-[#1a0a0e] mb-2">Akun Terverifikasi!</h1>
                    <p class="text-sm text-[#888] mb-6">
                        Selamat! Akun penjual kamu sudah aktif. Mulai pasang iklan sekarang.
                    </p>
                    <a href="{{ route('listings.create') }}"
                        class="w-full inline-flex items-center justify-center gap-2 bg-[#7D1A2E] hover:bg-[#9B2035] text-white font-bold text-sm py-3 rounded-xl transition-colors mb-3">
                        <i class="ti ti-plus"></i> Pasang Iklan Pertama
                    </a>
                    <a href="{{ route('dashboard') }}"
                        class="w-full inline-flex items-center justify-center gap-2 border border-[#ede5e6] text-[#888] text-sm py-3 rounded-xl hover:border-[#7D1A2E] hover:text-[#7D1A2E] transition-all">
                        <i class="ti ti-layout-dashboard"></i> Ke Dashboard
                    </a>
                </div>

                {{-- REJECTED --}}
            @elseif($status === 'rejected')
                <div class="bg-white border border-[#ede5e6] rounded-2xl p-8 text-center">
                    <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-5">
                        <i class="ti ti-circle-x text-2xl text-red-500"></i>
                    </div>
                    <h1 class="text-lg font-bold text-[#1a0a0e] mb-2">Verifikasi Ditolak</h1>
                    <p class="text-sm text-[#888] mb-4 leading-relaxed">
                        Maaf, pendaftaran penjual kamu tidak disetujui.
                    </p>
                    @if ($verification?->notes)
                        <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 text-left">
                            <p class="text-xs font-bold text-red-700 mb-1">Catatan dari Admin</p>
                            <p class="text-xs text-[#666]">{{ $verification->notes }}</p>
                        </div>
                    @endif
                    <a href="{{ route('seller.register') }}"
                        class="w-full inline-flex items-center justify-center gap-2 bg-[#7D1A2E] hover:bg-[#9B2035] text-white font-bold text-sm py-3 rounded-xl transition-colors">
                        <i class="ti ti-refresh"></i> Daftar Ulang
                    </a>
                </div>
            @endif

        </div>
    </div>
@endsection
