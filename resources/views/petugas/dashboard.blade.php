@extends('layouts.app-petugas')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- ===== PRESENSI HARI INI ===== --}}
<div id="presensi" class="mt-4">
    <x-card title="Presensi Hari Ini">
        @if($jadwalHariIni)
            {{-- Info shift --}}
            <div class="flex flex-wrap items-center gap-3 mb-5 p-3 bg-blue-50 rounded-lg text-sm">
                <div class="flex items-center gap-2 text-blue-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>{{ today()->isoFormat('dddd, D MMMM Y') }}</span>
                </div>
                <span class="text-blue-600 font-semibold">
                    Shift {{ $jadwalHariIni->shift->nama_shift }}
                    ({{ $jadwalHariIni->shift->jam_mulai }} – {{ $jadwalHariIni->shift->jam_selesai }})
                </span>
                <x-badge :status="$jadwalHariIni->status"/>
            </div>

            {{-- Status presensi --}}
            @if(!$presensiHariIni?->waktu_masuk)
                {{-- Belum absen masuk --}}
                <div class="text-center py-4">
                    <p class="text-gray-500 text-sm mb-4">Anda belum melakukan presensi masuk hari ini.</p>
                    <form method="POST" action="{{ route('petugas.presensi.masuk') }}">
                        @csrf
                        <input type="hidden" name="jadwal_piket_id" value="{{ $jadwalHariIni->id }}">
                        <button type="submit"
                            class="inline-flex items-center gap-2 bg-green-600 text-white
                                   px-8 py-3 rounded-xl font-semibold hover:bg-green-700
                                   active:scale-95 transition text-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Presensi Masuk ({{ now()->format('H:i') }})
                        </button>
                    </form>
                </div>

            @elseif(!$presensiHariIni?->waktu_keluar)
                {{-- Sudah masuk, belum keluar --}}
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm font-semibold text-green-700">Sudah Masuk</div>
                            <div class="text-xs text-gray-500">
                                Pukul {{ $presensiHariIni->waktu_masuk->format('H:i') }} WIB
                            </div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('petugas.presensi.keluar') }}">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center gap-2 bg-orange-500 text-white
                                   px-6 py-2.5 rounded-xl font-semibold hover:bg-orange-600
                                   active:scale-95 transition text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 16l4-4m0 0l-4-4m4 4H7"/>
                            </svg>
                            Presensi Keluar
                        </button>
                    </form>
                </div>

            @else
                {{-- Sudah lengkap --}}
                <div class="flex flex-wrap items-center gap-6 text-sm">
                    <div class="flex items-center gap-2 text-green-600">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                        </svg>
                        <span>Masuk: <strong>{{ $presensiHariIni->waktu_masuk->format('H:i') }}</strong></span>
                    </div>
                    <div class="flex items-center gap-2 text-orange-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 16l4-4m0 0l-4-4m4 4H7"/>
                        </svg>
                        <span>Keluar: <strong>{{ $presensiHariIni->waktu_keluar->format('H:i') }}</strong></span>
                    </div>
                    <div class="text-gray-500">
                        Durasi: <strong>{{ $presensiHariIni->durasi }}</strong>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs
                                 font-medium bg-green-100 text-green-700">
                        ✅ Presensi Lengkap
                    </span>
                </div>
            @endif

        @else
            <div class="py-6 text-center text-gray-400">
                <svg class="w-10 h-10 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-sm">Tidak ada jadwal piket untuk Anda hari ini.</p>
                <a href="{{ route('petugas.jadwal') }}"
                   class="text-blue-600 hover:underline text-xs mt-1 inline-block">
                    Lihat jadwal bulan ini →
                </a>
            </div>
        @endif
    </x-card>
</div>

{{-- ===== STATISTIK SINGKAT ===== --}}
<div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mt-5">
    <x-card>
        <div class="text-center">
            <div class="text-3xl font-bold text-blue-700">{{ $antrianSayaHariIni }}</div>
            <div class="text-xs text-gray-500 mt-1">Antrian Saya Hari Ini</div>
        </div>
    </x-card>
    <x-card>
        <div class="text-center">
            <div class="text-3xl font-bold text-yellow-600">
                {{ $antrianAktif->where('status', 'menunggu')->count() }}
            </div>
            <div class="text-xs text-gray-500 mt-1">Antrian Menunggu</div>
        </div>
    </x-card>
    <x-card class="col-span-2 sm:col-span-1">
        <div class="text-center">
            <div class="text-3xl font-bold text-green-600">
                {{ $antrianAktif->where('status', 'selesai')->count() }}
            </div>
            <div class="text-xs text-gray-500 mt-1">Selesai Hari Ini</div>
        </div>
    </x-card>
</div>

{{-- ===== ANTRIAN AKTIF ===== --}}
<div id="antrian" class="mt-5">
    <x-card title="Antrian Menunggu Hari Ini">
        @if($antrianAktif->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b text-gray-500 text-left">
                        <th class="pb-3 font-medium">Kode</th>
                        <th class="pb-3 font-medium">Nama</th>
                        <th class="pb-3 font-medium">Layanan</th>
                        <th class="pb-3 font-medium">Status</th>
                        <th class="pb-3 font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($antrianAktif as $item)
                    <tr class="hover:bg-gray-50
                               {{ $item->status === 'dipanggil' ? 'bg-blue-50' : '' }}
                               {{ $item->status === 'dilayani'  ? 'bg-purple-50' : '' }}">
                        <td class="py-3 font-mono font-bold text-[#003580] text-base">
                            {{ $item->kode_antrian }}
                        </td>
                        <td class="py-3">
                            <div class="font-medium">{{ $item->nama_pengunjung }}</div>
                            @if($item->no_hp)
                                <div class="text-xs text-gray-400">{{ $item->no_hp }}</div>
                            @endif
                        </td>
                        <td class="py-3 text-gray-600 text-xs">
                            {{ $item->jenisLayanan->nama_layanan }}
                        </td>
                        <td class="py-3"><x-badge :status="$item->status"/></td>
                        <td class="py-3">
                            <div class="flex gap-2">
                                @if($item->status === 'menunggu')
                                    <form method="POST"
                                          action="{{ route('petugas.antrian.panggil', $item) }}">
                                        @csrf
                                        <button type="submit"
                                            class="bg-blue-600 text-white px-3 py-1.5 rounded-lg
                                                   text-xs font-medium hover:bg-blue-700 transition">
                                            📣 Panggil
                                        </button>
                                    </form>
                                @elseif($item->status === 'dipanggil')
                                    <form method="POST"
                                          action="{{ route('petugas.antrian.mulai', $item) }}">
                                        @csrf
                                        <button type="submit"
                                            class="bg-purple-600 text-white px-3 py-1.5 rounded-lg
                                                   text-xs font-medium hover:bg-purple-700 transition">
                                            ▶ Mulai Layani
                                        </button>
                                    </form>
                                @elseif($item->status === 'dilayani')
                                    <form method="POST"
                                          action="{{ route('petugas.antrian.selesai', $item) }}">
                                        @csrf
                                        <button type="submit"
                                            class="bg-green-600 text-white px-3 py-1.5 rounded-lg
                                                   text-xs font-medium hover:bg-green-700 transition">
                                            ✔ Selesai
                                        </button>
                                    </form>
                                    <form method="POST"
                                          action="{{ route('petugas.antrian.batal', $item) }}">
                                        @csrf
                                        <button type="submit"
                                            class="bg-gray-100 text-gray-600 px-3 py-1.5 rounded-lg
                                                   text-xs font-medium hover:bg-gray-200 transition">
                                            Batal
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <div class="py-10 text-center text-gray-400">
                <p class="text-sm">Tidak ada antrian aktif saat ini.</p>
            </div>
        @endif
    </x-card>
</div>

{{-- ===== KALENDER JADWAL ===== --}}
<div class="mt-5">
    <x-card title="Jadwal Piket Bulan Ini">
        <div class="grid grid-cols-7 gap-1 text-xs text-center mb-1">
            @foreach(['Min','Sen','Sel','Rab','Kam','Jum','Sab'] as $h)
                <div class="font-semibold text-gray-500 py-1">{{ $h }}</div>
            @endforeach
        </div>
        <div class="grid grid-cols-7 gap-1 text-xs text-center">
            @foreach($kalender as $hari)
                @if($hari['tanggal'])
                    <div class="py-2 rounded-lg
                        {{ $hari['today']  ? 'ring-2 ring-[#003580] font-bold' : '' }}
                        {{ $hari['jadwal'] ? 'bg-blue-100 text-blue-800 font-semibold' : 'text-gray-400' }}">
                        {{ $hari['tanggal'] }}
                    </div>
                @else
                    <div></div>
                @endif
            @endforeach
        </div>
        <div class="mt-3 flex items-center gap-4 text-xs text-gray-500">
            <span class="flex items-center gap-1">
                <span class="w-3 h-3 bg-blue-100 rounded inline-block"></span> Jadwal piket Anda
            </span>
            <span class="flex items-center gap-1">
                <span class="w-3 h-3 border-2 border-[#003580] rounded inline-block"></span> Hari ini
            </span>
        </div>
    </x-card>
</div>

@endsection