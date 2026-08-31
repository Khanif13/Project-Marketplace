@extends('admin.layouts.app')

@section('title', 'Tambah Kategori')
@section('page-title', 'Tambah Kategori')

@section('content')
    <div class="max-w-lg">
        <div class="bg-white border border-[#ede5e6] rounded-2xl p-6">
            <form method="POST" action="{{ route('admin.categories.store') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-xs font-semibold text-[#555] mb-1.5">Nama Kategori <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Elektronik"
                        class="w-full border border-[#ede5e6] rounded-xl px-4 py-2.5 text-sm outline-none focus:border-[#7D1A2E] @error('name') border-red-400 @enderror">
                    @error('name')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-semibold text-[#555] mb-1.5">Parent Kategori <span
                            class="text-[#aaa] font-normal">(opsional)</span></label>
                    <select name="parent_id"
                        class="w-full border border-[#ede5e6] rounded-xl px-4 py-2.5 text-sm text-[#555] outline-none focus:border-[#7D1A2E] bg-white">
                        <option value="">— Kategori Utama —</option>
                        @foreach ($parents as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-semibold text-[#555] mb-1.5">Icon <span
                            class="text-[#aaa] font-normal">(nama class Tabler Icons)</span></label>
                    <input type="text" name="icon" value="{{ old('icon') }}" placeholder="Contoh: ti-device-laptop"
                        class="w-full border border-[#ede5e6] rounded-xl px-4 py-2.5 text-sm outline-none focus:border-[#7D1A2E]">
                    <p class="text-xs text-[#aaa] mt-1">
                        Cari icon di <a href="https://tabler.io/icons" target="_blank"
                            class="text-[#7D1A2E] hover:underline">tabler.io/icons</a>
                    </p>
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-semibold text-[#555] mb-1.5">Urutan</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                        class="w-full border border-[#ede5e6] rounded-xl px-4 py-2.5 text-sm outline-none focus:border-[#7D1A2E]">
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                        class="flex-1 bg-[#7D1A2E] hover:bg-[#9B2035] text-white font-bold text-sm py-3 rounded-xl transition-colors">
                        Simpan Kategori
                    </button>
                    <a href="{{ route('admin.categories.index') }}"
                        class="px-6 py-3 border border-[#ede5e6] text-[#888] text-sm rounded-xl hover:border-[#7D1A2E] transition-all">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
