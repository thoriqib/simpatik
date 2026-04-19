@extends('layouts.app-publik')

@section('title', 'Kirim Pengaduan')

@section('content')
<div class="text-center mb-6">
    <h2 class="text-xl font-bold text-gray-800">Kirim Pengaduan</h2>
    <p class="text-sm text-gray-500 mt-1">
        Pengaduan bersifat anonim. Sampaikan keluhan atau masukan Anda dengan jujur.
    </p>
</div>

<x-card>
    <form action="{{ route('pengaduan.store') }}" method="POST" enctype="multipart/form-data"
          class="space-y-4">
        @csrf

        <x-form-input label="Subjek Pengaduan" name="subjek" :required="true"
            placeholder="Contoh: Petugas kurang responsif"/>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Isi Pengaduan <span class="text-red-500">*</span>
            </label>
            <textarea name="isi_pengaduan" rows="5" required
                placeholder="Jelaskan pengaduan Anda secara detail..."
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none
                       {{ $errors->has('isi_pengaduan') ? 'border-red-400 bg-red-50' : '' }}">{{ old('isi_pengaduan') }}</textarea>
            @error('isi_pengaduan')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Lampiran <span class="text-gray-400">(opsional, maks 2MB)</span>
            </label>
            <input type="file" name="lampiran" accept=".jpg,.png,.pdf"
                class="w-full text-sm text-gray-500 border border-gray-300 rounded-lg
                       file:mr-3 file:py-2 file:px-4 file:border-0 file:text-sm
                       file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
            @error('lampiran')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="pt-2">
            <button type="submit"
                class="w-full bg-[#003580] text-white py-3 rounded-lg font-semibold
                       hover:bg-blue-800 transition">
                📢 Kirim Pengaduan
            </button>
        </div>
    </form>
</x-card>

<div class="mt-4 text-center">
    <a href="{{ route('home') }}" class="text-sm text-gray-500 hover:underline">
        ← Kembali ke Halaman Antrian
    </a>
</div>
@endsection