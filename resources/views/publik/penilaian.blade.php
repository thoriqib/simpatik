@extends('layouts.app-publik')

@section('title', 'Penilaian Pelayanan')

@section('content')
<div class="text-center mb-6">
    <h2 class="text-xl font-bold text-gray-800">Penilaian Pelayanan</h2>
    <p class="text-sm text-gray-500 mt-1">Berikan penilaian untuk pelayanan yang Anda terima</p>
</div>

<x-card>
    {{-- Info petugas --}}
    <div class="flex items-center gap-4 p-4 bg-blue-50 rounded-xl mb-6">
        <div class="w-12 h-12 bg-[#003580] rounded-full flex items-center justify-center
                    text-white font-bold text-lg">
            {{ strtoupper(substr($antrian->petugas->name, 0, 1)) }}
        </div>
        <div>
            <div class="font-semibold text-gray-800">{{ $antrian->petugas->name }}</div>
            <div class="text-sm text-gray-500">Antrian: {{ $antrian->kode_antrian }}</div>
            <div class="text-sm text-gray-500">{{ $antrian->jenisLayanan->nama_layanan }}</div>
        </div>
    </div>

    <form action="{{ route('penilaian.store') }}" method="POST" x-data="{ rating: 0, hover: 0 }">
        @csrf
        <input type="hidden" name="antrian_id" value="{{ $antrian->id }}">

        {{-- Bintang --}}
        <div class="text-center mb-6">
            <p class="text-sm font-medium text-gray-700 mb-3">Seberapa puas Anda dengan pelayanan kami?</p>
            <div class="flex justify-center gap-2">
                @for($i = 1; $i <= 5; $i++)
                <button type="button"
                    @click="rating = {{ $i }}"
                    @mouseenter="hover = {{ $i }}"
                    @mouseleave="hover = 0"
                    :class="(hover || rating) >= {{ $i }} ? 'text-yellow-400 scale-110' : 'text-gray-300'"
                    class="text-5xl transition-all transform cursor-pointer">
                    ★
                </button>
                @endfor
            </div>
            <input type="hidden" name="nilai" :value="rating">
            <p class="text-sm text-gray-500 mt-2 h-5" x-text="
                rating === 1 ? '😞 Sangat Tidak Puas' :
                rating === 2 ? '😕 Tidak Puas' :
                rating === 3 ? '😐 Cukup' :
                rating === 4 ? '😊 Puas' :
                rating === 5 ? '😄 Sangat Puas' : ''
            "></p>
            @error('nilai')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Komentar --}}
        <div class="mb-5">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Komentar <span class="text-gray-400">(opsional)</span>
            </label>
            <textarea name="komentar" rows="4" placeholder="Ceritakan pengalaman Anda..."
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none">{{ old('komentar') }}</textarea>
        </div>

        <button type="submit" x-bind:disabled="rating === 0"
            class="w-full bg-[#003580] text-white py-3 rounded-lg font-semibold
                   hover:bg-blue-800 transition disabled:opacity-50 disabled:cursor-not-allowed">
            Kirim Penilaian
        </button>
    </form>
</x-card>
@endsection