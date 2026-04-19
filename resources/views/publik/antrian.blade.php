@extends('layouts.app-publik')

@section('title', 'Ambil Antrian')

@section('content')
<div class="text-center mb-6">
    <h2 class="text-xl font-bold text-gray-800">Ambil Nomor Antrian</h2>
    <p class="text-sm text-gray-500 mt-1">Isi data di bawah untuk mendapatkan nomor antrian Anda</p>
</div>

<x-card>
    <form action="{{ route('antrian.ambil') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Jenis Layanan <span class="text-red-500">*</span>
            </label>
            <select name="jenis_layanan_id"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                <option value="">Pilih jenis layanan...</option>
                @foreach($jenisLayanan as $jenis)
                <option value="{{ $jenis->id }}" {{ old('jenis_layanan_id') == $jenis->id ? 'selected' : '' }}>
                    [{{ $jenis->kode }}] {{ $jenis->nama_layanan }}
                </option>
                @endforeach
            </select>
            @error('jenis_layanan_id')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <x-form-input label="Nama Lengkap" name="nama_pengunjung" :required="true"/>
        <x-form-input label="No. HP" name="no_hp" type="tel"/>
        <x-form-input label="Email" name="email" type="email"/>

        <button type="submit"
            class="w-full bg-[#003580] text-white py-3 rounded-lg font-semibold text-base
                   hover:bg-blue-800 transition mt-2">
            🎫 Ambil Nomor Antrian
        </button>
    </form>
</x-card>

<div class="mt-5 flex justify-center gap-6 text-sm">
    <a href="{{ route('antrian.display') }}" class="text-blue-600 hover:underline">
        📺 Lihat Display Antrian
    </a>
    <a href="{{ route('pengaduan.create') }}" class="text-gray-500 hover:underline">
        📢 Kirim Pengaduan
    </a>
</div>
@endsection