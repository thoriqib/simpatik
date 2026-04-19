@extends('layouts.app-admin')

@section('title', 'Jenis Layanan')
@section('page-title', 'Jenis Layanan')

@section('content')
<div class="flex justify-between items-center mt-4 mb-5">
    <p class="text-sm text-gray-500">{{ $jenisLayanan->count() }} jenis layanan</p>
    <a href="{{ route('admin.jenis-layanan.create') }}"
       class="bg-[#003580] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-800 transition">
        + Tambah Jenis Layanan
    </a>
</div>

<x-card>
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b text-gray-500 text-left">
                <th class="pb-3 font-medium">Kode</th>
                <th class="pb-3 font-medium">Nama Layanan</th>
                <th class="pb-3 font-medium">Antrian Hari Ini</th>
                <th class="pb-3 font-medium">Total Antrian</th>
                <th class="pb-3 font-medium">Status</th>
                <th class="pb-3 font-medium">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($jenisLayanan as $item)
            <tr class="hover:bg-gray-50">
                <td class="py-3">
                    <span class="font-mono font-bold text-[#003580] bg-blue-50 px-2 py-1 rounded">
                        {{ $item->kode }}
                    </span>
                </td>
                <td class="py-3">
                    <div class="font-medium">{{ $item->nama_layanan }}</div>
                    @if($item->deskripsi)
                        <div class="text-xs text-gray-400 mt-0.5">{{ Str::limit($item->deskripsi, 60) }}</div>
                    @endif
                </td>
                <td class="py-3 text-center font-semibold text-blue-700">
                    {{ $item->antrian_hari_ini }}
                </td>
                <td class="py-3 text-center text-gray-500">{{ $item->total_antrian }}</td>
                <td class="py-3">
                    @if($item->is_aktif)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Aktif</span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Nonaktif</span>
                    @endif
                </td>
                <td class="py-3 flex gap-3">
                    <a href="{{ route('admin.jenis-layanan.edit', $item) }}"
                       class="text-blue-600 hover:underline">Edit</a>
                    <form method="POST" action="{{ route('admin.jenis-layanan.destroy', $item) }}" class="inline"
                          onsubmit="return confirm('Hapus jenis layanan ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="py-8 text-center text-gray-400">Belum ada jenis layanan</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</x-card>
@endsection