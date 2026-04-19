@extends('layouts.app-admin')

@section('title', 'Laporan Presensi')
@section('page-title', 'Laporan Presensi Petugas')

@section('content')

{{-- Filter --}}
<x-card class="mt-4 mb-5">
    <form method="GET" action="{{ route('admin.laporan.presensi') }}" class="flex flex-wrap gap-3 items-end">
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
        <button type="submit" class="bg-[#003580] text-white px-4 py-2 rounded-lg text-sm font-medium">
            Tampilkan
        </button>
    </form>
</x-card>

{{-- Rekap per petugas --}}
<x-card title="Rekap Kehadiran Bulan {{ \Carbon\Carbon::create($tahun, $bulan)->isoFormat('MMMM Y') }}" class="mb-5">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b text-gray-500 text-left">
                <th class="pb-3 font-medium">Petugas</th>
                <th class="pb-3 font-medium text-center">Total</th>
                <th class="pb-3 font-medium text-center text-green-600">Hadir</th>
                <th class="pb-3 font-medium text-center text-blue-600">Izin</th>
                <th class="pb-3 font-medium text-center text-orange-600">Sakit</th>
                <th class="pb-3 font-medium text-center text-red-600">Alpha</th>
                <th class="pb-3 font-medium text-center text-gray-500">Terjadwal</th>
                <th class="pb-3 font-medium text-center">% Hadir</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($rekapPerPetugas as $r)
            @php $persen = $r['total'] > 0 ? round($r['hadir'] / $r['total'] * 100) : 0; @endphp
            <tr class="hover:bg-gray-50">
                <td class="py-3 font-medium">{{ $r['nama'] }}</td>
                <td class="py-3 text-center text-gray-600">{{ $r['total'] }}</td>
                <td class="py-3 text-center font-semibold text-green-600">{{ $r['hadir'] }}</td>
                <td class="py-3 text-center text-blue-600">{{ $r['izin'] }}</td>
                <td class="py-3 text-center text-orange-600">{{ $r['sakit'] }}</td>
                <td class="py-3 text-center text-red-600">{{ $r['alpha'] }}</td>
                <td class="py-3 text-center text-gray-400">{{ $r['terjadwal'] }}</td>
                <td class="py-3 text-center">
                    <div class="flex items-center gap-2">
                        <div class="flex-1 bg-gray-100 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width:{{ $persen }}%"></div>
                        </div>
                        <span class="text-xs text-gray-600 w-8">{{ $persen }}%</span>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="py-8 text-center text-gray-400">Belum ada data presensi</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</x-card>

@endsection