@extends('layouts.app-petugas')

@section('title', 'Presensi')
@section('page-title', 'Presensi Saya')

@section('content')

{{-- ===== AKSI PRESENSI HARI INI ===== --}}
<div class="mt-4">
    <x-card title="Presensi Hari Ini — {{ today()->isoFormat('dddd, D MMMM Y') }}">
        @if($jadwalHariIni)
            <div class="flex flex-wrap gap-3 items-center mb-5">
                <div class="text-sm text-gray-600">
                    Shift: <strong>{{ $jadwalHariIni->shift->nama_shift }}</strong>
                    ({{ $jadwalHariIni->shift->jam_mulai }} – {{ $jadwalHariIni->shift->jam_selesai }})
                </div>
                <x-badge :status="$jadwalHariIni->status"/>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Masuk --}}
                <div class="border border-gray-200 rounded-xl p-4">
                    <div class="text-xs text-gray-500 uppercase tracking-wider mb-2">Presensi Masuk</div>
                    @if($presensiHariIni?->waktu_masuk)
                        <div class="text-2xl font-bold text-green-600">
                            {{ $presensiHariIni->waktu_masuk->format('H:i') }}
                        </div>
                        <div class="text-xs text-gray-400 mt-1">WIB — Sudah tercatat ✅</div>
                    @else
                        <div class="text-gray-400 text-sm mb-3">Belum melakukan presensi masuk</div>
                        <form method="POST" action="{{ route('petugas.presensi.masuk') }}">
                            @csrf
                            <input type="hidden" name="jadwal_piket_id" value="{{ $jadwalHariIni->id }}">
                            <button type="submit"
                                class="w-full bg-green-600 text-white py-2 rounded-lg text-sm
                                       font-semibold hover:bg-green-700 transition">
                                ✅ Absen Masuk Sekarang
                            </button>
                        </form>
                    @endif
                </div>

                {{-- Keluar --}}
                <div class="border border-gray-200 rounded-xl p-4">
                    <div class="text-xs text-gray-500 uppercase tracking-wider mb-2">Presensi Keluar</div>
                    @if($presensiHariIni?->waktu_keluar)
                        <div class="text-2xl font-bold text-orange-500">
                            {{ $presensiHariIni->waktu_keluar->format('H:i') }}
                        </div>
                        <div class="text-xs text-gray-400 mt-1">
                            WIB — Durasi: {{ $presensiHariIni->durasi }}
                        </div>
                    @elseif($presensiHariIni?->waktu_masuk)
                        <div class="text-gray-400 text-sm mb-3">Belum melakukan presensi keluar</div>
                        <form method="POST" action="{{ route('petugas.presensi.keluar') }}">
                            @csrf
                            <button type="submit"
                                class="w-full bg-orange-500 text-white py-2 rounded-lg text-sm
                                       font-semibold hover:bg-orange-600 transition">
                                🔚 Absen Keluar Sekarang
                            </button>
                        </form>
                    @else
                        <div class="text-gray-300 text-sm italic">
                            Lakukan presensi masuk terlebih dahulu
                        </div>
                    @endif
                </div>
            </div>

        @else
            <div class="py-6 text-center text-gray-400 text-sm">
                Tidak ada jadwal piket untuk Anda hari ini.
            </div>
        @endif
    </x-card>
</div>

{{-- ===== RIWAYAT PRESENSI ===== --}}
<div class="mt-5">
    <x-card title="Riwayat Presensi Bulan Ini">

        {{-- Filter bulan/tahun --}}
        <form method="GET" action="{{ route('petugas.presensi.index') }}"
              class="flex flex-wrap gap-3 items-end mb-5">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Bulan</label>
                <select name="bulan" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    @foreach(range(1, 12) as $b)
                        <option value="{{ $b }}" {{ $bulan == $b ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($b)->isoFormat('MMMM') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Tahun</label>
                <select name="tahun" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    @foreach(range(now()->year - 1, now()->year) as $t)
                        <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit"
                class="bg-[#003580] text-white px-4 py-2 rounded-lg text-sm font-medium">
                Tampilkan
            </button>
        </form>

        {{-- Rekap bulan --}}
        <div class="grid grid-cols-3 gap-3 mb-5">
            <div class="bg-green-50 rounded-lg p-3 text-center">
                <div class="text-xl font-bold text-green-700">{{ $rekap['hadir'] }}</div>
                <div class="text-xs text-green-600">Hadir</div>
            </div>
            <div class="bg-blue-50 rounded-lg p-3 text-center">
                <div class="text-xl font-bold text-blue-700">{{ $rekap['izin'] + $rekap['sakit'] }}</div>
                <div class="text-xs text-blue-600">Izin/Sakit</div>
            </div>
            <div class="bg-red-50 rounded-lg p-3 text-center">
                <div class="text-xl font-bold text-red-700">{{ $rekap['alpha'] }}</div>
                <div class="text-xs text-red-600">Alpha</div>
            </div>
        </div>

        {{-- Tabel riwayat --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                {{-- Ganti bagian <thead> dan <tbody> tabel riwayat --}}
            <thead>
                <tr class="border-b text-gray-500 text-left">
                    <th class="pb-3 font-medium">Tanggal</th>
                    <th class="pb-3 font-medium">Shift</th>
                    <th class="pb-3 font-medium">Status</th>
                    <th class="pb-3 font-medium">Masuk</th>
                    <th class="pb-3 font-medium">Keluar</th>
                    <th class="pb-3 font-medium">Durasi</th>
                    <th class="pb-3 font-medium text-red-500">Kurang Jam</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($jadwal as $item)
                <tr class="hover:bg-gray-50 {{ $item->tanggal->isToday() ? 'bg-blue-50' : '' }}
                        {{ ($item->presensi?->kekurangan_menit ?? 0) > 0 ? 'bg-red-50' : '' }}">
                    <td class="py-3 text-sm">
                        {{ $item->tanggal->isoFormat('ddd, D MMM') }}
                        @if($item->tanggal->isToday())
                            <span class="text-xs text-blue-600">(hari ini)</span>
                        @endif
                    </td>
                    <td class="py-3 text-gray-600 text-sm">{{ $item->shift->nama_shift }}</td>
                    <td class="py-3"><x-badge :status="$item->status"/></td>
                    <td class="py-3 font-mono text-sm">
                        @if($item->presensi?->waktu_masuk)
                            <span class="{{ $item->presensi->terlambat > 0 ? 'text-orange-600 font-semibold' : 'text-green-600' }}">
                                {{ $item->presensi->waktu_masuk->format('H:i') }}
                            </span>
                            @if($item->presensi->terlambat > 0)
                                <span class="text-xs text-orange-500 ml-1">
                                    (+{{ $item->presensi->terlambat }}m)
                                </span>
                            @endif
                        @else
                            <span class="text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="py-3 font-mono text-sm">
                        @if($item->presensi?->waktu_keluar)
                            <span class="{{ $item->presensi->pulang_awal > 0 ? 'text-red-500 font-semibold' : 'text-gray-700' }}">
                                {{ $item->presensi->waktu_keluar->format('H:i') }}
                            </span>
                            @if($item->presensi->pulang_awal > 0)
                                <span class="text-xs text-red-400 ml-1">
                                    (-{{ $item->presensi->pulang_awal }}m)
                                </span>
                            @endif
                        @else
                            <span class="text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="py-3 text-gray-600 text-xs">
                        {{ $item->presensi?->durasi ?? '—' }}
                    </td>
                    <td class="py-3 text-sm font-semibold">
                        @if(($item->presensi?->kekurangan_menit ?? 0) > 0)
                            <span class="text-red-500">
                                ⚠ {{ $item->presensi->kekurangan_format }}
                            </span>
                        @elseif($item->presensi?->waktu_keluar)
                            <span class="text-green-500 text-xs">✓ Lengkap</span>
                        @else
                            <span class="text-gray-300">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-8 text-center text-gray-400">
                        Tidak ada jadwal di bulan ini
                    </td>
                </tr>
                @endforelse
            </tbody>

            {{-- Rekap kekurangan total di bawah tabel --}}
            @if($rekap['total_kekurangan'] > 0)
            <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg flex items-center gap-2 text-sm">
                <svg class="w-4 h-4 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
                </svg>
                <span class="text-red-700">
                    Total kekurangan jam bulan ini:
                    <strong>
                        {{ floor($rekap['total_kekurangan'] / 60) }}j
                        {{ $rekap['total_kekurangan'] % 60 }}m
                    </strong>
                </span>
            </div>
            @endif
            </table>
        </div>
    </x-card>
</div>

@endsection