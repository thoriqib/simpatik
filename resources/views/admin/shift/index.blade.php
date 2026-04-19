@extends('layouts.app-admin')

@section('title', 'Pengaturan Shift')
@section('page-title', 'Pengaturan Shift Piket')

@section('content')
<div class="flex justify-between items-center mt-4 mb-5">
    <p class="text-sm text-gray-500">{{ $shifts->count() }} shift terdaftar</p>
    <a href="{{ route('admin.shift.create') }}"
       class="bg-[#003580] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-800 transition">
        + Tambah Shift
    </a>
</div>

<x-card>
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b text-gray-500 text-left">
                <th class="pb-3 font-medium">Nama Shift</th>
                <th class="pb-3 font-medium">Jam Mulai</th>
                <th class="pb-3 font-medium">Jam Selesai</th>
                <th class="pb-3 font-medium">Status</th>
                <th class="pb-3 font-medium">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($shifts as $shift)
            <tr class="hover:bg-gray-50">
                <td class="py-3 font-medium">{{ $shift->nama_shift }}</td>
                <td class="py-3 font-mono">{{ $shift->jam_mulai }}</td>
                <td class="py-3 font-mono">{{ $shift->jam_selesai }}</td>
                <td class="py-3">
                    @if($shift->is_aktif)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Aktif</span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Nonaktif</span>
                    @endif
                </td>
                <td class="py-3 flex gap-3">
                    <a href="{{ route('admin.shift.edit', $shift) }}"
                       class="text-blue-600 hover:underline text-sm">Edit</a>

                    <form method="POST" action="{{ route('admin.shift.toggle', $shift) }}" class="inline">
                        @csrf @method('PATCH')
                        <button type="submit" class="text-yellow-600 hover:underline text-sm">
                            {{ $shift->is_aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.shift.destroy', $shift) }}" class="inline"
                          onsubmit="return confirm('Hapus shift ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline text-sm">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="py-8 text-center text-gray-400">Belum ada shift terdaftar</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</x-card>
@endsection