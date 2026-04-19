@extends('layouts.app-admin')

@section('title', 'Laporan Penilaian')
@section('page-title', 'Laporan Penilaian Petugas')

@section('content')

{{-- Filter tanggal --}}
<x-card class="mt-4 mb-5">
    <form method="GET" action="{{ route('admin.laporan.penilaian') }}" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Dari</label>
            <input type="date" name="dari" value="{{ $dari }}"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Sampai</label>
            <input type="date" name="sampai" value="{{ $sampai }}"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>
        <button type="submit" class="bg-[#003580] text-white px-4 py-2 rounded-lg text-sm font-medium">
            Tampilkan
        </button>
    </form>
</x-card>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

    {{-- Nilai per petugas --}}
    <x-card title="Ranking Nilai Petugas">
        @forelse($nilaiPerPetugas as $index => $p)
        <div class="flex items-center gap-4 py-3 border-b border-gray-100 last:border-0">
            <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm
                        {{ $index === 0 ? 'bg-yellow-100 text-yellow-700' :
                           ($index === 1 ? 'bg-gray-100 text-gray-600' :
                           ($index === 2 ? 'bg-orange-100 text-orange-600' : 'bg-gray-50 text-gray-400')) }}">
                {{ $index + 1 }}
            </div>
            <div class="flex-1">
                <div class="font-medium text-sm">{{ $p->name }}</div>
                <div class="text-xs text-gray-400">{{ $p->total_penilaian }} penilaian</div>
            </div>
            <div class="text-right">
                <div class="font-bold text-yellow-500">★ {{ number_format($p->avg_nilai ?? 0, 1) }}</div>
            </div>
        </div>
        @empty
        <p class="text-gray-400 text-sm py-4 text-center">Belum ada data penilaian</p>
        @endforelse
    </x-card>

    {{-- Distribusi bintang --}}
    <x-card title="Distribusi Nilai">
        @foreach(range(5, 1) as $bintang)
        @php $jumlah = $distribusi[$bintang] ?? 0; $total = $distribusi->sum(); @endphp
        <div class="flex items-center gap-3 mb-3">
            <div class="w-16 text-sm text-right text-gray-600 flex-shrink-0">
                {{ $bintang }} ★
            </div>
            <div class="flex-1 bg-gray-100 rounded-full h-3 overflow-hidden">
                <div class="bg-yellow-400 h-3 rounded-full transition-all"
                     style="width: {{ $total > 0 ? ($jumlah / $total * 100) : 0 }}%"></div>
            </div>
            <div class="w-10 text-xs text-gray-500 text-right flex-shrink-0">{{ $jumlah }}</div>
        </div>
        @endforeach
    </x-card>
</div>

{{-- Komentar terbaru --}}
<x-card title="Komentar Terbaru" class="mt-5">
    @forelse($komentar as $item)
    <div class="py-3 border-b border-gray-100 last:border-0">
        <div class="flex items-start justify-between gap-4">
            <div class="flex-1">
                <div class="text-sm text-gray-800">{{ $item->komentar }}</div>
                <div class="text-xs text-gray-400 mt-1">
                    Untuk: <strong>{{ $item->petugas->name }}</strong> ·
                    {{ $item->antrian->jenisLayanan->nama_layanan }} ·
                    {{ $item->created_at->isoFormat('D MMM Y') }}
                </div>
            </div>
            <div class="text-yellow-500 font-bold flex-shrink-0">
                {{ str_repeat('★', $item->nilai) }}{{ str_repeat('☆', 5 - $item->nilai) }}
            </div>
        </div>
    </div>
    @empty
    <p class="text-gray-400 text-sm py-4 text-center">Belum ada komentar</p>
    @endforelse
</x-card>

@endsection