@extends('layouts.app-admin')

@section('title', 'Edit Petugas')
@section('page-title', 'Edit Data Petugas')

@section('content')
<div class="mt-4 grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Form Edit --}}
    <div class="lg:col-span-2">
        <x-card title="Informasi Akun">
            <form method="POST"
                  action="{{ route('admin.petugas.update', $petugas) }}"
                  class="space-y-5">
                @csrf
                @method('PUT')

                <x-form-input
                    label="Nama Lengkap"
                    name="name"
                    :required="true"
                    :value="$petugas->name"/>

                <x-form-input
                    label="Email"
                    name="email"
                    type="email"
                    :required="true"
                    :value="$petugas->email"/>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Password Baru
                        <span class="text-gray-400 font-normal text-xs">
                            (kosongkan jika tidak ingin mengubah)
                        </span>
                    </label>
                    <div class="relative" x-data="{ show: false }">
                        <input
                            :type="show ? 'text' : 'password'"
                            name="password"
                            placeholder="Min. 8 karakter, huruf besar & angka"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm pr-10
                                   focus:outline-none focus:ring-2 focus:ring-blue-500
                                   {{ $errors->has('password') ? 'border-red-400 bg-red-50' : '' }}">
                        <button type="button" @click="show = !show"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit"
                        class="bg-[#003580] text-white px-6 py-2.5 rounded-lg text-sm
                               font-semibold hover:bg-blue-800 transition">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.petugas.index') }}"
                       class="bg-gray-100 text-gray-700 px-6 py-2.5 rounded-lg text-sm
                              font-semibold hover:bg-gray-200 transition">
                        Batal
                    </a>
                </div>
            </form>
        </x-card>
    </div>

    {{-- Panel Info & Hapus --}}
    <div class="space-y-5">
        <x-card title="Statistik Petugas">
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-500">Terdaftar sejak</dt>
                    <dd class="font-medium">{{ $petugas->created_at->isoFormat('D MMM Y') }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Total antrian ditangani</dt>
                    <dd class="font-medium">{{ $petugas->antrian()->count() }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Total penilaian</dt>
                    <dd class="font-medium">{{ $petugas->penilaianSebagaiPetugas()->count() }}</dd>
                </div>
                <div class="flex justify-between items-center">
                    <dt class="text-gray-500">Rata-rata nilai</dt>
                    <dd class="font-bold text-yellow-500">
                        @if($petugas->penilaianSebagaiPetugas()->exists())
                            ★ {{ number_format($petugas->penilaianSebagaiPetugas()->avg('nilai'), 1) }}
                        @else
                            <span class="text-gray-300 font-normal">Belum ada</span>
                        @endif
                    </dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Kehadiran bulan ini</dt>
                    <dd class="font-medium">
                        {{ $petugas->jadwalPiket()
                            ->whereYear('tanggal', now()->year)
                            ->whereMonth('tanggal', now()->month)
                            ->where('status', 'hadir')->count() }} hari
                    </dd>
                </div>
            </dl>
        </x-card>

        {{-- Zona Bahaya --}}
        <x-card>
            <h4 class="text-sm font-semibold text-red-600 mb-3">⚠️ Zona Berbahaya</h4>
            <p class="text-xs text-gray-500 mb-4">
                Menghapus petugas akan menghapus semua data jadwal dan presensi terkait.
                Data antrian yang sudah ditangani tetap tersimpan.
            </p>
            <button type="button"
                x-data
                @click="$dispatch('open-hapus-panel')"
                class="w-full bg-red-50 text-red-600 border border-red-200 py-2 rounded-lg
                       text-sm font-semibold hover:bg-red-100 transition">
                🗑️ Hapus Petugas Ini
            </button>

            {{-- Konfirmasi inline --}}
            <div x-data="{ confirm: false }"
                 @open-hapus-panel.window="confirm = true"
                 x-show="confirm" x-cloak class="mt-4">
                <p class="text-xs text-red-600 font-medium mb-3 text-center">
                    Yakin menghapus <strong>{{ $petugas->name }}</strong>?
                </p>
                <div class="flex gap-2">
                    <button @click="confirm = false"
                        class="flex-1 text-xs bg-gray-100 text-gray-600 py-2 rounded-lg">
                        Batal
                    </button>
                    <form action="{{ route('admin.petugas.destroy', $petugas) }}" method="POST"
                          class="flex-1">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="w-full text-xs bg-red-600 text-white py-2 rounded-lg
                                   font-semibold hover:bg-red-700">
                            Hapus Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </x-card>
    </div>
</div>
@endsection