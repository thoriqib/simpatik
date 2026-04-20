@extends('layouts.app-publik')

@section('title', 'Jadwal Petugas')

@section('content')

<div class="mb-5">
    <h2 class="text-xl font-bold text-gray-800">Jadwal Petugas Pelayanan</h2>
    <p class="text-sm text-gray-500 mt-1">
        Jadwal piket petugas PST BPS Kota Jambi bulan
        {{ \Carbon\Carbon::create($tahun, $bulan)->isoFormat('MMMM Y') }}
    </p>
</div>

{{-- Filter bulan/tahun --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 mb-5">
    <form method="GET" action="{{ route('jadwal.publik') }}"
          class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs text-gray-500 mb-1">Bulan</label>
            <select name="bulan"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm
                       focus:outline-none focus:ring-2 focus:ring-blue-500">
                @foreach(range(1, 12) as $b)
                    <option value="{{ $b }}" {{ $bulan == $b ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($b)->isoFormat('MMMM') }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Tahun</label>
            <select name="tahun"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm
                       focus:outline-none focus:ring-2 focus:ring-blue-500">
                @foreach(range(now()->year - 1, now()->year + 1) as $t)
                    <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit"
            class="bg-[#003580] text-white px-5 py-2 rounded-lg text-sm font-medium">
            Tampilkan
        </button>
    </form>
</div>

{{-- Tabel jadwal --}}
@if(count($hariKerja) > 0)
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">

    {{-- Header tabel: Tanggal | Shift Pagi | Shift Siang --}}
    <div class="grid border-b border-gray-200 bg-[#003580] text-white text-sm font-semibold"
         style="grid-template-columns: 140px repeat({{ $shifts->count() }}, 1fr)">
        <div class="px-4 py-3">Tanggal</div>
        @foreach($shifts as $shift)
        <div class="px-4 py-3 border-l border-blue-700">
            <div>{{ $shift->nama_shift }}</div>
            <div class="text-blue-200 text-xs font-normal">
                {{ $shift->jam_mulai }} – {{ $shift->jam_selesai }}
            </div>
        </div>
        @endforeach
    </div>

    {{-- Baris per hari kerja --}}
    @foreach($hariKerja as $tanggal)
    @php
        $tglStr   = $tanggal->toDateString();
        $jadwalHari = $semuaJadwal[$tglStr] ?? collect();
        $isToday  = $tanggal->isToday();
        $isMingguIni = $tanggal->isCurrentWeek();
    @endphp
    <div class="grid border-b border-gray-100 last:border-0 text-sm
                {{ $isToday ? 'bg-blue-50' : 'hover:bg-gray-50' }} transition"
         style="grid-template-columns: 140px repeat({{ $shifts->count() }}, 1fr)">

        {{-- Kolom tanggal --}}
        <div class="px-4 py-4 border-r border-gray-100 flex-shrink-0">
            <div class="font-semibold {{ $isToday ? 'text-[#003580]' : 'text-gray-700' }}">
                {{ $tanggal->isoFormat('ddd') }}
                @if($isToday)
                    <span class="ml-1 text-xs bg-[#003580] text-white px-1.5 py-0.5 rounded">
                        Hari ini
                    </span>
                @endif
            </div>
            <div class="text-gray-500 text-xs mt-0.5">
                {{ $tanggal->isoFormat('D MMMM') }}
            </div>
        </div>

        {{-- Kolom per shift --}}
        @foreach($shifts as $shift)
        @php
            $petugasShift = $jadwalHari->where('shift_id', $shift->id);
        @endphp
        <div class="px-4 py-4 border-r border-gray-100 last:border-0">
            @if($petugasShift->isEmpty())
                <span class="text-gray-300 text-xs italic">—</span>
            @else
                <div class="space-y-1.5">
                    @foreach($petugasShift as $jadwal)
                    <div class="flex items-center gap-2">
                        {{-- Avatar inisial --}}
                        <div class="w-6 h-6 rounded-full flex-shrink-0 flex items-center justify-center
                                    text-white text-xs font-bold
                                    {{ $jadwal->status === 'hadir'     ? 'bg-green-500' :
                                       ($jadwal->status === 'izin'     ? 'bg-blue-400' :
                                       ($jadwal->status === 'sakit'    ? 'bg-orange-400' :
                                       ($jadwal->status === 'alpha'    ? 'bg-red-400' : 'bg-[#003580]'))) }}">
                            {{ strtoupper(substr($jadwal->petugas->name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="text-xs font-medium text-gray-800 leading-tight">
                                {{ $jadwal->petugas->name }}
                            </div>
                            @if($jadwal->status !== 'terjadwal')
                            <div class="text-xs leading-tight
                                        {{ $jadwal->status === 'hadir'  ? 'text-green-600' :
                                           ($jadwal->status === 'izin'  ? 'text-blue-500' :
                                           ($jadwal->status === 'sakit' ? 'text-orange-500' : 'text-red-500')) }}">
                                {{ ucfirst($jadwal->status) }}
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
        @endforeach
    </div>
    @endforeach
</div>

{{-- Legenda --}}
<div class="mt-4 flex flex-wrap gap-4 text-xs text-gray-500">
    <span class="flex items-center gap-1.5">
        <span class="w-4 h-4 rounded-full bg-[#003580] inline-block"></span> Terjadwal
    </span>
    <span class="flex items-center gap-1.5">
        <span class="w-4 h-4 rounded-full bg-green-500 inline-block"></span> Hadir
    </span>
    <span class="flex items-center gap-1.5">
        <span class="w-4 h-4 rounded-full bg-blue-400 inline-block"></span> Izin
    </span>
    <span class="flex items-center gap-1.5">
        <span class="w-4 h-4 rounded-full bg-orange-400 inline-block"></span> Sakit
    </span>
    <span class="flex items-center gap-1.5">
        <span class="w-4 h-4 rounded-full bg-red-400 inline-block"></span> Alpha
    </span>
</div>

@else
<div class="bg-white rounded-xl border border-gray-100 p-10 text-center text-gray-400">
    <p>Tidak ada hari kerja di bulan ini.</p>
</div>
@endif

@endsection