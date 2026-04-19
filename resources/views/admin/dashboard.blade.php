@extends('layouts.app-admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
{{-- Kartu statistik ringkasan --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mt-4">
    <x-card>
        <div class="flex items-center gap-4">
            <div class="p-3 bg-blue-100 rounded-xl">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87m6-4a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <div>
                <div class="text-2xl font-bold text-gray-800">{{ $totalPetugas }}</div>
                <div class="text-sm text-gray-500">Total Petugas</div>
            </div>
        </div>
    </x-card>

    <x-card>
        <div class="flex items-center gap-4">
            <div class="p-3 bg-yellow-100 rounded-xl">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <div class="text-2xl font-bold text-gray-800">{{ $antrianHariIni }}</div>
                <div class="text-sm text-gray-500">Antrian Hari Ini</div>
            </div>
        </div>
    </x-card>

    <x-card>
        <div class="flex items-center gap-4">
            <div class="p-3 bg-green-100 rounded-xl">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <div class="text-2xl font-bold text-gray-800">{{ $antrianSelesai }}</div>
                <div class="text-sm text-gray-500">Selesai Dilayani</div>
            </div>
        </div>
    </x-card>

    <x-card>
        <div class="flex items-center gap-4">
            <div class="p-3 bg-red-100 rounded-xl">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                </svg>
            </div>
            <div>
                <div class="text-2xl font-bold text-gray-800">{{ $pengaduanBaru }}</div>
                <div class="text-sm text-gray-500">Pengaduan Baru</div>
            </div>
        </div>
    </x-card>
</div>

{{-- Tabel antrian aktif hari ini --}}
<x-card title="Antrian Aktif Hari Ini" class="mt-6">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b text-gray-500 text-left">
                <th class="pb-3 font-medium">Kode</th>
                <th class="pb-3 font-medium">Nama Pengunjung</th>
                <th class="pb-3 font-medium">Jenis Layanan</th>
                <th class="pb-3 font-medium">Petugas</th>
                <th class="pb-3 font-medium">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($antrianAktif as $item)
            <tr class="hover:bg-gray-50">
                <td class="py-3 font-mono font-semibold text-blue-700">{{ $item->kode_antrian }}</td>
                <td class="py-3">{{ $item->nama_pengunjung }}</td>
                <td class="py-3">{{ $item->jenisLayanan->nama_layanan }}</td>
                <td class="py-3">{{ $item->petugas->name ?? '-' }}</td>
                <td class="py-3"><x-badge :status="$item->status"/></td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="py-8 text-center text-gray-400">Belum ada antrian hari ini</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</x-card>
@endsection