@extends('layouts.app')

@section('title', 'Edit Iklan')

@section('content')
    <div class="bg-[#f5f0ef] min-h-screen">
        <div class="max-w-3xl mx-auto px-6 py-10">

            <div class="mb-8">
                <h1 class="text-xl font-black text-[#1a0a0e]">Edit Iklan</h1>
                <p class="text-sm text-[#aaa] mt-1">Perbarui informasi iklan kamu</p>
            </div>

            <form method="POST" action="{{ route('listings.update', $listing->slug) }}" enctype="multipart/form-data"
                x-data="editForm()">
                @csrf
                @method('PUT')

                {{-- Foto Existing --}}
                <div class="bg-white rounded-2xl border border-[#ede5e6] p-6 mb-5">
                    <label class="block text-sm font-bold text-[#1a0a0e] mb-1">Foto Barang</label>
                    <p class="text-xs text-[#aaa] mb-4">Upload foto baru akan menggantikan semua foto lama.</p>

                    {{-- Foto existing --}}
                    @if ($listing->images->count() && !old('replace_images'))
                        <div class="grid grid-cols-6 gap-2 mb-4">
                            @foreach ($listing->images as $img)
                                <div class="relative aspect-square rounded-xl overflow-hidden border-2 border-[#7D1A2E]/30">
                                    <img src="{{ Storage::url($img->path) }}" class="w-full h-full object-cover">
                                </div>
                            @endforeach
                        </div>
                        <p class="text-xs text-[#aaa] mb-3">Foto saat ini. Upload baru di bawah untuk menggantinya.</p>
                    @endif

                    {{-- Upload baru --}}
                    <div class="grid grid-cols-6 gap-2">
                        <template x-for="(preview, index) in previews" :key="index">
                            <div class="relative aspect-square rounded-xl overflow-hidden border-2 border-[#7D1A2E]">
                                <img :src="preview" class="w-full h-full object-cover">
                                <button type="button" @click="removeImage(index)"
                                    class="absolute top-1 right-1 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center text-xs">
                                    <i class="ti ti-x"></i>
                                </button>
                            </div>
                        </template>
                        <template x-if="previews.length < 6">
                            <label
                                class="aspect-square rounded-xl border-2 border-dashed border-[#ede5e6] hover:border-[#7D1A2E] flex flex-col items-center justify-center cursor-pointer transition-colors">
                                <i class="ti ti-plus text-xl text-[#ccc]"></i>
                                <span class="text-[10px] text-[#ccc] mt-1">Ganti</span>
                                <input type="file" name="images[]" multiple accept="image/*" class="hidden"
                                    @change="handleImages">
                            </label>
                        </template>
                    </div>
                </div>

                {{-- Info Dasar --}}
                <div class="bg-white rounded-2xl border border-[#ede5e6] p-6 mb-5">
                    <h2 class="text-sm font-bold text-[#1a0a0e] mb-5 flex items-center gap-2">
                        <span class="w-1 h-4 bg-[#7D1A2E] rounded-full inline-block"></span>
                        Informasi Barang
                    </h2>

                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-[#555] mb-1.5">Judul Iklan <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title', $listing->title) }}"
                            class="w-full border border-[#ede5e6] rounded-xl px-4 py-2.5 text-sm text-[#1a0a0e] outline-none focus:border-[#7D1A2E] transition-colors @error('title') border-red-400 @enderror">
                        @error('title')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-[#555] mb-1.5">Kategori <span
                                class="text-red-500">*</span></label>
                        <select name="category_id"
                            class="w-full border border-[#ede5e6] rounded-xl px-4 py-2.5 text-sm text-[#555] outline-none focus:border-[#7D1A2E] transition-colors">
                            @foreach ($categories as $parent => $subs)
                                <optgroup label="{{ $parent }}">
                                    @foreach ($subs as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ old('category_id', $listing->category_id) == $cat->id ? 'selected' : '' }}>
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

                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-[#555] mb-1.5">Deskripsi <span
                                class="text-red-500">*</span></label>
                        <textarea name="description" rows="5"
                            class="w-full border border-[#ede5e6] rounded-xl px-4 py-2.5 text-sm text-[#1a0a0e] outline-none focus:border-[#7D1A2E] transition-colors resize-none @error('description') border-red-400 @enderror">{{ old('description', $listing->description) }}</textarea>
                        @error('description')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#555] mb-2">Kondisi <span
                                class="text-red-500">*</span></label>
                        <div class="flex gap-3">
                            @foreach (['used' => 'Bekas', 'new' => 'Baru'] as $val => $label)
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="condition" value="{{ $val }}"
                                        {{ old('condition', $listing->condition) === $val ? 'checked' : '' }}
                                        class="accent-[#7D1A2E]">
                                    <span class="text-sm text-[#555]">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Harga & Stok --}}
                <div class="bg-white rounded-2xl border border-[#ede5e6] p-6 mb-5">
                    <h2 class="text-sm font-bold text-[#1a0a0e] mb-5 flex items-center gap-2">
                        <span class="w-1 h-4 bg-[#7D1A2E] rounded-full inline-block"></span>
                        Harga & Stok
                    </h2>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-semibold text-[#555] mb-1.5">Harga (Rp) <span
                                    class="text-red-500">*</span></label>
                            <input type="number" name="price" value="{{ old('price', $listing->price) }}" min="0"
                                class="w-full border border-[#ede5e6] rounded-xl px-4 py-2.5 text-sm text-[#1a0a0e] outline-none focus:border-[#7D1A2E] transition-colors @error('price') border-red-400 @enderror">
                            @error('price')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#555] mb-1.5">Stok <span
                                    class="text-[#aaa] font-normal">(opsional)</span></label>
                            <input type="number" name="stock" value="{{ old('stock', $listing->stock) }}" min="0"
                                placeholder="Kosongkan jika tidak ditentukan"
                                class="w-full border border-[#ede5e6] rounded-xl px-4 py-2.5 text-sm text-[#1a0a0e] outline-none focus:border-[#7D1A2E] transition-colors">
                        </div>
                    </div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_negotiable" value="1"
                            {{ old('is_negotiable', $listing->is_negotiable) ? 'checked' : '' }}
                            class="w-4 h-4 accent-[#7D1A2E]">
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
                        <label class="block text-xs font-semibold text-[#555] mb-1.5">Alamat <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="address" value="{{ old('address', $listing->address) }}"
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
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('listings.show', $listing->slug) }}"
                        class="px-6 py-3.5 border border-[#ede5e6] text-[#888] text-sm rounded-xl hover:border-[#7D1A2E] hover:text-[#7D1A2E] transition-all">
                        Batal
                    </a>
                </div>

            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function editForm() {
                return {
                    previews: [],
                    handleImages(e) {
                        const newFiles = Array.from(e.target.files);
                        const remaining = 6 - this.previews.length;
                        newFiles.slice(0, remaining).forEach(file => {
                            const reader = new FileReader();
                            reader.onload = (ev) => this.previews.push(ev.target.result);
                            reader.readAsDataURL(file);
                        });
                        e.target.value = '';
                    },
                    removeImage(index) {
                        this.previews.splice(index, 1);
                    }
                }
            }
        </script>
    @endpush
@endsection
