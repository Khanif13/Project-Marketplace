@extends('layouts.app')

@section('title', 'Daftar Jadi Penjual')

@section('content')
    <div class="bg-[#f5f0ef] min-h-screen">
        <div class="max-w-xl mx-auto px-6 py-12">

            {{-- Header --}}
            <div class="text-center mb-8">
                <div class="w-14 h-14 bg-[#7D1A2E]/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="ti ti-store text-2xl text-[#7D1A2E]"></i>
                </div>
                <h1 class="text-xl font-black text-[#1a0a0e]">Daftar Jadi Penjual</h1>
                <p class="text-sm text-[#aaa] mt-2">
                    Isi data toko kamu. Tim kami akan memverifikasi dalam 1x24 jam.
                </p>
            </div>

            <form method="POST" action="{{ route('seller.register.submit') }}" enctype="multipart/form-data">
                @csrf

                <div class="bg-white border border-[#ede5e6] rounded-2xl p-6 mb-5">
                    <h2 class="text-sm font-bold text-[#1a0a0e] mb-5 flex items-center gap-2">
                        <span class="w-1 h-4 bg-[#7D1A2E] rounded-full inline-block"></span>
                        Info Toko
                    </h2>

                    {{-- Nama Toko --}}
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-[#555] mb-1.5">
                            Nama Toko <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="store_name" value="{{ old('store_name') }}"
                            placeholder="Contoh: Toko Budi Elektronik"
                            class="w-full border border-[#ede5e6] rounded-xl px-4 py-2.5 text-sm text-[#1a0a0e] outline-none focus:border-[#7D1A2E] transition-colors @error('store_name') border-red-400 @enderror">
                        @error('store_name')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Alamat Toko --}}
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-[#555] mb-1.5">
                            Alamat Toko <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="store_address" value="{{ old('store_address') }}"
                            placeholder="Contoh: Jl. Merdeka No. 12, dekat kampus Unsulbar"
                            class="w-full border border-[#ede5e6] rounded-xl px-4 py-2.5 text-sm text-[#1a0a0e] outline-none focus:border-[#7D1A2E] transition-colors @error('store_address') border-red-400 @enderror">
                        @error('store_address')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nomor WA --}}
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-[#555] mb-1.5">
                            Nomor WhatsApp <span class="text-red-500">*</span>
                        </label>
                        <div
                            class="flex border border-[#ede5e6] rounded-xl overflow-hidden focus-within:border-[#7D1A2E] transition-colors @error('store_wa') border-red-400 @enderror">
                            <span
                                class="bg-[#f5f0ef] px-4 py-2.5 text-sm text-[#aaa] border-r border-[#ede5e6] shrink-0">+62</span>
                            <input type="text" name="store_wa" value="{{ old('store_wa') }}" placeholder="8123456789"
                                class="flex-1 px-4 py-2.5 text-sm text-[#1a0a0e] outline-none bg-transparent">
                        </div>
                        <p class="text-xs text-[#aaa] mt-1">Nomor ini akan ditampilkan ke pembeli untuk dihubungi.</p>
                        @error('store_wa')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Foto KTP (opsional) --}}
                    <div>
                        <label class="block text-xs font-semibold text-[#555] mb-1.5">
                            Foto KTP <span class="text-[#aaa] font-normal">(opsional, mempercepat verifikasi)</span>
                        </label>
                        <label
                            class="flex items-center gap-3 border border-dashed border-[#ede5e6] hover:border-[#7D1A2E] rounded-xl px-4 py-3 cursor-pointer transition-colors"
                            x-data="{ fileName: '' }">
                            <i class="ti ti-id-badge text-xl text-[#aaa]"></i>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-[#555]" x-text="fileName || 'Upload foto KTP'"></p>
                                <p class="text-xs text-[#aaa]">JPG/PNG, maks 2MB</p>
                            </div>
                            <input type="file" name="ktp_photo" accept="image/*" class="hidden"
                                @change="fileName = $event.target.files[0]?.name || ''">
                        </label>
                        @error('ktp_photo')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Info proses --}}
                <div class="bg-[#7D1A2E]/5 border border-[#7D1A2E]/15 rounded-xl p-4 mb-6 flex items-start gap-3">
                    <i class="ti ti-info-circle text-[#7D1A2E] text-base mt-0.5 shrink-0"></i>
                    <div class="text-xs text-[#666] leading-relaxed">
                        Setelah mendaftar, akun kamu akan berstatus <strong>Menunggu Verifikasi</strong>.
                        Admin akan mereview dan mengaktifkan akun dalam <strong>1x24 jam</strong>.
                        Kamu akan mendapat notifikasi setelah diverifikasi.
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-[#7D1A2E] hover:bg-[#9B2035] text-white font-bold text-sm py-3.5 rounded-xl transition-colors">
                    Daftar Jadi Penjual
                </button>
            </form>
        </div>
    </div>
@endsection
