@extends('layouts.app-petugas')

@section('title', 'Jadwal Saya')
@section('page-title', 'Jadwal Piket Saya')

@section('content')

{{-- Filter bulan/tahun --}}
<x-card class="mt-4 mb-5">
    <form method="GET" action="{{ route('petugas.jadwal') }}" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
            <select name="bulan" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                @foreach(range(1, 12) as $b)
                    <option value="{{ $b }}" {{ $bulan == $b ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($b)->isoFormat('MMMM') }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
            <select name="tahun" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                @foreach(range(now()->year - 1, now()->year + 1) as $t)
                    <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit"
            class="bg-[#003580] text-white px-4 py-2 rounded-lg text-sm font-medium">
            Tampilkan
        </button>
    </form>
</x-card>

{{-- Kartu rekap kehadiran --}}
<div class="grid grid-cols-2 sm:grid-cols-5 gap-3 mb-5">
    @foreach([
        ['label' => 'Hadir',     'key' => 'hadir',     'color' => 'bg-green-100 text-green-700'],
        ['label' => 'Izin',      'key' => 'izin',      'color' => 'bg-blue-100 text-blue-700'],
        ['label' => 'Sakit',     'key' => 'sakit',     'color' => 'bg-orange-100 text-orange-700'],
        ['label' => 'Alpha',     'key' => 'alpha',     'color' => 'bg-red-100 text-red-700'],
        ['label' => 'Terjadwal', 'key' => 'terjadwal', 'color' => 'bg-gray-100 text-gray-600'],
    ] as $item)
    <div class="bg-white rounded-xl border border-gray-100 p-4 text-center">
        <div class="text-2xl font-bold {{ $item['color'] }} rounded-lg py-1 mb-1">
            {{ $rekap[$item['key']] }}
        </div>
        <div class="text-xs text-gray-500">{{ $item['label'] }}</div>
    </div>
    @endforeach
</div>

{{-- Tabel jadwal --}}
<x-card title="Jadwal Bulan {{ \Carbon\Carbon::create($tahun, $bulan)->isoFormat('MMMM Y') }}">
    @forelse($jadwal as $item)
    <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
        <div class="flex items-center gap-4">
            {{-- Tanggal --}}
            <div class="w-12 h-12 rounded-xl flex flex-col items-center justify-center text-center
                        {{ $item->tanggal->isToday() ? 'bg-[#003580] text-white' : 'bg-gray-100 text-gray-700' }}">
                <div class="text-xs leading-none">{{ $item->tanggal->isoFormat('ddd') }}</div>
                <div class="text-lg font-bold leading-tight">{{ $item->tanggal->day }}</div>
            </div>
            {{-- Info shift --}}
            <div>
                <div class="font-medium text-sm text-gray-800">{{ $item->shift->nama_shift }}</div>
                <div class="text-xs text-gray-500">
                    {{ $item->shift->jam_mulai }} – {{ $item->shift->jam_selesai }}
                </div>
                @if($item->presensi)
                <div class="text-xs text-gray-400 mt-0.5">
                    Masuk: {{ $item->presensi->waktu_masuk?->format('H:i') ?? '-' }} |
                    Keluar: {{ $item->presensi->waktu_keluar?->format('H:i') ?? '-' }}
                    @if($item->presensi->durasi)
                        ({{ $item->presensi->durasi }})
                    @endif
                </div>
                @endif
            </div>
        </div>
        <x-badge :status="$item->status"/>
    </div>
    @empty
    <div class="py-10 text-center text-gray-400 text-sm">
        Tidak ada jadwal piket untuk bulan ini
    </div>
    @endforelse
</x-card>

@endsection