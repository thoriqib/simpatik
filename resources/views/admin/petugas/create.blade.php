@extends('layouts.app-admin')

@section('title', 'Tambah Petugas')
@section('page-title', 'Tambah Petugas Baru')

@section('content')
<div class="mt-4 max-w-lg">
    <x-card>
        <form method="POST" action="{{ route('admin.petugas.store') }}" class="space-y-5">
            @csrf

            <x-form-input
                label="Nama Lengkap"
                name="name"
                :required="true"
                placeholder="Contoh: Budi Santoso"/>

            <x-form-input
                label="Email"
                name="email"
                type="email"
                :required="true"
                placeholder="nama@bps-jambi.go.id"/>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Password <span class="text-red-500">*</span>
                </label>
                <div class="relative" x-data="{ show: false }">
                    <input
                        :type="show ? 'text' : 'password'"
                        name="password"
                        required
                        placeholder="Min. 8 karakter, huruf besar, angka"
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
                    Simpan Petugas
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
@endsection