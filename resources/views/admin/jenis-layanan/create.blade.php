@extends('layouts.app-admin')

@section('title', 'Tambah Jenis Layanan')
@section('page-title', 'Tambah Jenis Layanan')

@section('content')
<div class="mt-4 max-w-lg">
    <x-card>
        <form method="POST" action="{{ route('admin.jenis-layanan.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Kode Layanan <span class="text-red-500">*</span>
                </label>
                <input type="text" name="kode"
                       value="{{ old('kode') }}"
                       maxlength="5"
                       placeholder="Contoh: A, B, F"
                       style="text-transform:uppercase"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                              uppercase font-mono font-bold tracking-widest
                              focus:outline-none focus:ring-2 focus:ring-blue-500
                              {{ $errors->has('kode') ? 'border-red-400 bg-red-50' : '' }}"
                       required>
                <p class="text-xs text-gray-400 mt-1">
                    Kode satu huruf kapital, digunakan sebagai awalan nomor antrian.
                    Contoh: kode "A" → nomor antrian A001, A002, dst.
                </p>
                @error('kode')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <x-form-input
                label="Nama Layanan"
                name="nama_layanan"
                :required="true"
                placeholder="Contoh: Konsultasi Data Statistik"/>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Deskripsi <span class="text-gray-400 font-normal">(opsional)</span>
                </label>
                <textarea name="deskripsi" rows="3"
                    placeholder="Penjelasan singkat tentang jenis layanan ini..."
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                           resize-none focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="bg-[#003580] text-white px-6 py-2.5 rounded-lg text-sm
                           font-semibold hover:bg-blue-800 transition">
                    Simpan Layanan
                </button>
                <a href="{{ route('admin.jenis-layanan.index') }}"
                   class="bg-gray-100 text-gray-700 px-6 py-2.5 rounded-lg text-sm
                          font-semibold hover:bg-gray-200 transition">
                    Batal
                </a>
            </div>
        </form>
    </x-card>
</div>
@endsection