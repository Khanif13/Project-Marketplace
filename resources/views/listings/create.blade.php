@extends('layouts.app')

@section('title', 'Pasang Iklan Baru')

@section('content')
    <div class="bg-[#f5f0ef] min-h-screen">
        <div class="max-w-3xl mx-auto px-6 py-10">

            <div class="mb-8">
                <h1 class="text-xl font-black text-[#1a0a0e]">Pasang Iklan Baru</h1>
                <p class="text-sm text-[#aaa] mt-1">Isi detail barang yang ingin kamu jual</p>
            </div>

            <form method="POST" action="{{ route('listings.store') }}" enctype="multipart/form-data" x-data="listingForm()">
                @csrf

                {{-- Upload Foto --}}
                <div class="bg-white rounded-2xl border border-[#ede5e6] p-6 mb-5">
                    <label class="block text-sm font-bold text-[#1a0a0e] mb-1">
                        Foto Barang <span class="text-red-500">*</span>
                    </label>
                    <p class="text-xs text-[#aaa] mb-4">Minimal 1 foto, maksimal 6 foto. Format JPG/PNG, maks 2MB per foto.
                    </p>

                    <div class="grid grid-cols-6 gap-2">
                        {{-- Preview gambar yang dipilih --}}
                        <template x-for="(preview, index) in previews" :key="index">
                            <div class="relative aspect-square rounded-xl overflow-hidden border-2 border-[#7D1A2E]">
                                <img :src="preview" class="w-full h-full object-cover">
                                <button type="button" @click="removeImage(index)"
                                    class="absolute top-1 right-1 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center text-xs">
                                    <i class="ti ti-x"></i>
                                </button>
                            </div>
                        </template>

                        {{-- Tombol tambah --}}
                        <template x-if="previews.length < 6">
                            <label
                                class="aspect-square rounded-xl border-2 border-dashed border-[#ede5e6] hover:border-[#7D1A2E] flex flex-col items-center justify-center cursor-pointer transition-colors">
                                <i class="ti ti-plus text-xl text-[#ccc]"></i>
                                <span class="text-[10px] text-[#ccc] mt-1">Tambah</span>
                                <input type="file" name="images[]" multiple accept="image/*" class="hidden"
                                    @change="handleImages">
                            </label>
                        </template>
                    </div>

                    @error('images')
                        <p class="text-xs text-red-500 mt-2">{{ $message }}</p>
                    @enderror
                    @error('images.*')
                        <p class="text-xs text-red-500 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Info Dasar --}}
                <div class="bg-white rounded-2xl border border-[#ede5e6] p-6 mb-5">
                    <h2 class="text-sm font-bold text-[#1a0a0e] mb-5 flex items-center gap-2">
                        <span class="w-1 h-4 bg-[#7D1A2E] rounded-full inline-block"></span>
                        Informasi Barang
                    </h2>

                    {{-- Judul --}}
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-[#555] mb-1.5">
                            Judul Iklan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title') }}"
                            placeholder="Contoh: iPhone 13 Pro Max 256GB Mulus"
                            class="w-full border border-[#ede5e6] rounded-xl px-4 py-2.5 text-sm text-[#1a0a0e] outline-none focus:border-[#7D1A2E] transition-colors @error('title') border-red-400 @enderror">
                        @error('title')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kategori --}}
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-[#555] mb-1.5">
                            Kategori <span class="text-red-500">*</span>
                        </label>
                        <select name="category_id"
                            class="w-full border border-[#ede5e6] rounded-xl px-4 py-2.5 text-sm text-[#555] outline-none focus:border-[#7D1A2E] transition-colors @error('category_id') border-red-400 @enderror">
                            <option value="">Pilih kategori...</option>
                            @foreach ($categories as $parent => $subs)
                                <optgroup label="{{ $parent }}">
                                    @foreach ($subs as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-[#555] mb-1.5">
                            Deskripsi <span class="text-red-500">*</span>
                        </label>
                        <textarea name="description" rows="5" placeholder="Jelaskan kondisi barang, kelengkapan, alasan jual, dll."
                            class="w-full border border-[#ede5e6] rounded-xl px-4 py-2.5 text-sm text-[#1a0a0e] outline-none focus:border-[#7D1A2E] transition-colors resize-none @error('description') border-red-400 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kondisi --}}
                    <div>
                        <label class="block text-xs font-semibold text-[#555] mb-2">
                            Kondisi <span class="text-red-500">*</span>
                        </label>
                        <div class="flex gap-3">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="condition" value="used"
                                    {{ old('condition', 'used') === 'used' ? 'checked' : '' }} class="accent-[#7D1A2E]">
                                <span class="text-sm text-[#555]">Bekas</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="condition" value="new"
                                    {{ old('condition') === 'new' ? 'checked' : '' }} class="accent-[#7D1A2E]">
                                <span class="text-sm text-[#555]">Baru</span>
                            </label>
                        </div>
                        @error('condition')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Harga & Stok --}}
                <div class="bg-white rounded-2xl border border-[#ede5e6] p-6 mb-5">
                    <h2 class="text-sm font-bold text-[#1a0a0e] mb-5 flex items-center gap-2">
                        <span class="w-1 h-4 bg-[#7D1A2E] rounded-full inline-block"></span>
                        Harga & Stok
                    </h2>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        {{-- Harga --}}
                        <div>
                            <label class="block text-xs font-semibold text-[#555] mb-1.5">
                                Harga (Rp) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="price" value="{{ old('price') }}" placeholder="0" min="0"
                                class="w-full border border-[#ede5e6] rounded-xl px-4 py-2.5 text-sm text-[#1a0a0e] outline-none focus:border-[#7D1A2E] transition-colors @error('price') border-red-400 @enderror">
                            @error('price')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Stok --}}
                        <div>
                            <label class="block text-xs font-semibold text-[#555] mb-1.5">
                                Stok <span class="text-[#aaa] font-normal">(opsional)</span>
                            </label>
                            <input type="number" name="stock" value="{{ old('stock') }}"
                                placeholder="Kosongkan jika tidak ditentukan" min="0"
                                class="w-full border border-[#ede5e6] rounded-xl px-4 py-2.5 text-sm text-[#1a0a0e] outline-none focus:border-[#7D1A2E] transition-colors">
                        </div>
                    </div>

                    {{-- Nego --}}
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_negotiable" value="1"
                            {{ old('is_negotiable') ? 'checked' : '' }} class="w-4 h-4 accent-[#7D1A2E]">
                        <div>
                            <span class="text-sm text-[#1a0a0e] font-medium">Harga bisa nego</span>
                            <p class="text-xs text-[#aaa]">Pembeli bisa menawar harga lewat WhatsApp</p>
                        </div>
                    </label>
                </div>

                {{-- Lokasi --}}
                <div class="bg-white rounded-2xl border border-[#ede5e6] p-6 mb-6">
                    <h2 class="text-sm font-bold text-[#1a0a0e] mb-5 flex items-center gap-2">
                        <span class="w-1 h-4 bg-[#7D1A2E] rounded-full inline-block"></span>
                        Lokasi
                    </h2>
                    <div>
                        <label class="block text-xs font-semibold text-[#555] mb-1.5">
                            Alamat <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="address" value="{{ old('address', auth()->user()->store_address) }}"
                            placeholder="Contoh: Jl. Merdeka No. 12, dekat kampus Unsulbar"
                            class="w-full border border-[#ede5e6] rounded-xl px-4 py-2.5 text-sm text-[#1a0a0e] outline-none focus:border-[#7D1A2E] transition-colors @error('address') border-red-400 @enderror">
                        @error('address')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Submit --}}
                <div class="flex items-center gap-3">
                    <button type="submit"
                        class="flex-1 bg-[#7D1A2E] hover:bg-[#9B2035] text-white font-bold text-sm py-3.5 rounded-xl transition-colors">
                        Pasang Iklan Sekarang
                    </button>
                    <a href="{{ route('dashboard') }}"
                        class="px-6 py-3.5 border border-[#ede5e6] text-[#888] text-sm rounded-xl hover:border-[#7D1A2E] hover:text-[#7D1A2E] transition-all">
                        Batal
                    </a>
                </div>

            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function listingForm() {
                return {
                    previews: [],
                    files: [],

                    handleImages(e) {
                        const newFiles = Array.from(e.target.files);
                        const remaining = 6 - this.previews.length;
                        newFiles.slice(0, remaining).forEach(file => {
                            const reader = new FileReader();
                            reader.onload = (ev) => this.previews.push(ev.target.result);
                            reader.readAsDataURL(file);
                            this.files.push(file);
                        });
                        e.target.value = '';
                    },

                    removeImage(index) {
                        this.previews.splice(index, 1);
                        this.files.splice(index, 1);
                    }
                }
            }
        </script>
    @endpush

@endsection
