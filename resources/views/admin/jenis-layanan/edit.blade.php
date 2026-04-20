@extends('layouts.app-admin')

@section('title', 'Edit Jenis Layanan')
@section('page-title', 'Edit Jenis Layanan')

@section('content')
<div class="mt-4 max-w-lg">
    <x-card>
        <form method="POST"
              action="{{ route('admin.jenis-layanan.update', $jenisLayanan) }}"
              class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Kode Layanan <span class="text-red-500">*</span>
                </label>
                <input type="text" name="kode"
                       value="{{ old('kode', $jenisLayanan->kode) }}"
                       maxlength="5"
                       style="text-transform:uppercase"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                              uppercase font-mono font-bold tracking-widest
                              focus:outline-none focus:ring-2 focus:ring-blue-500
                              {{ $errors->has('kode') ? 'border-red-400 bg-red-50' : '' }}"
                       required>
                @error('kode')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <x-form-input
                label="Nama Layanan"
                name="nama_layanan"
                :required="true"
                :value="$jenisLayanan->nama_layanan"/>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Deskripsi <span class="text-gray-400 font-normal">(opsional)</span>
                </label>
                <textarea name="deskripsi" rows="3"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                           resize-none focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('deskripsi', $jenisLayanan->deskripsi) }}</textarea>
            </div>

            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_aktif" id="is_aktif" value="1"
                       {{ old('is_aktif', $jenisLayanan->is_aktif) ? 'checked' : '' }}
                       class="w-4 h-4 rounded border-gray-300 text-[#003580]">
                <label for="is_aktif" class="text-sm text-gray-700">
                    Layanan ini aktif (tersedia untuk antrian)
                </label>
            </div>

            {{-- Info penggunaan --}}
            <div class="bg-gray-50 rounded-lg p-3 text-xs text-gray-500 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Jenis layanan ini memiliki
                <strong>{{ $jenisLayanan->antrian()->count() }} total antrian</strong>
                sepanjang waktu.
                Jika sudah memiliki data antrian, kode tidak disarankan untuk diubah.
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="bg-[#003580] text-white px-6 py-2.5 rounded-lg text-sm
                           font-semibold hover:bg-blue-800 transition">
                    Simpan Perubahan
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