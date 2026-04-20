@extends('layouts.app-admin')

@section('title', 'Edit Shift')
@section('page-title', 'Edit Shift Piket')

@section('content')
<div class="mt-4 max-w-md">
    <x-card>
        <form method="POST"
              action="{{ route('admin.shift.update', $shift) }}"
              class="space-y-5">
            @csrf
            @method('PUT')

            <x-form-input
                label="Nama Shift"
                name="nama_shift"
                :required="true"
                :value="$shift->nama_shift"/>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Jam Mulai <span class="text-red-500">*</span>
                    </label>
                    <input type="time" name="jam_mulai"
                           value="{{ old('jam_mulai', $shift->jam_mulai) }}" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                               focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('jam_mulai')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Jam Selesai <span class="text-red-500">*</span>
                    </label>
                    <input type="time" name="jam_selesai"
                           value="{{ old('jam_selesai', $shift->jam_selesai) }}" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                               focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('jam_selesai')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_aktif" id="is_aktif" value="1"
                       {{ old('is_aktif', $shift->is_aktif) ? 'checked' : '' }}
                       class="w-4 h-4 rounded border-gray-300 text-[#003580]">
                <label for="is_aktif" class="text-sm text-gray-700">
                    Shift ini aktif
                </label>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="bg-[#003580] text-white px-6 py-2.5 rounded-lg text-sm
                           font-semibold hover:bg-blue-800 transition">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.shift.index') }}"
                   class="bg-gray-100 text-gray-700 px-6 py-2.5 rounded-lg text-sm
                          font-semibold hover:bg-gray-200 transition">
                    Batal
                </a>
            </div>
        </form>
    </x-card>
</div>
@endsection