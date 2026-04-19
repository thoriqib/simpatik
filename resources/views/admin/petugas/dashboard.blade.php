@extends('layouts.app-petugas')

@section('title', 'Dashboard Petugas')
@section('page-title', 'Dashboard')

@section('content')

{{-- ===== PRESENSI ===== --}}
<div id="presensi" class="mt-4">
    <x-card title="Presensi Hari Ini">
        @if($jadwalHariIni)
            <div class="flex flex-wrap items-center gap-4 mb-4 text-sm text-gray-600">
                <span>Shift: <strong>{{ $jadwalHariIni->shift->nama_shift }}</strong></span>
                <span>{{ $jadwalHariIni->shift->jam_mulai }} – {{ $jadwalHariIni->shift->jam_selesai }}</span>
                <x-badge :status="$jadwalHariIni->status"/>
            </div>

            @if(!$presensiHariIni?->waktu_masuk)
                {{-- Belum presensi masuk --}}
                <form method="POST" action="{{ route('petugas.presensi.masuk') }}">
                    @csrf
                    <input type="hidden" name="jadwal_piket_id" value="{{ $jadwalHariIni->id }}">
                    <button type="submit"
                        class="bg-green-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-green-700">
                        ✅ Presensi Masuk ({{ now()->format('H:i') }})
                    </button>
                </form>
            @elseif(!$presensiHariIni?->waktu_keluar)
                {{-- Sudah masuk, belum keluar --}}
                <div class="flex items-center gap-4">
                    <span class="text-sm text-green-600">
                        ✅ Masuk: <strong>{{ $presensiHariIni->waktu_masuk->format('H:i') }}</strong>
                    </span>
                    <form method="POST" action="{{ route('petugas.presensi.keluar') }}">
                        @csrf
                        <button type="submit"
                            class="bg-orange-500 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-orange-600">
                            🔚 Presensi Keluar
                        </button>
                    </form>
                </div>
            @else
                {{-- Sudah lengkap --}}
                <div class="flex items-center gap-6 text-sm">
                    <span class="text-green-600">✅ Masuk: <strong>{{ $presensiHariIni->waktu_masuk->format('H:i') }}</strong></span>
                    <span class="text-orange-500">🔚 Keluar: <strong>{{ $presensiHariIni->waktu_keluar->format('H:i') }}</strong></span>
                    <span class="text-gray-500">Durasi: {{ $presensiHariIni->durasi }}</span>
                </div>
            @endif
        @else
            <p class="text-sm text-gray-400">Tidak ada jadwal piket untuk Anda hari ini.</p>
        @endif
    </x-card>
</div>

{{-- ===== ANTRIAN ===== --}}
<div id="antrian" class="mt-6">
    <x-card title="Antrian Menunggu">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b text-gray-500 text-left">
                    <th class="pb-3 font-medium">No</th>
                    <th class="pb-3 font-medium">Kode</th>
                    <th class="pb-3 font-medium">Nama</th>
                    <th class="pb-3 font-medium">Layanan</th>
                    <th class="pb-3 font-medium">Status</th>
                    <th class="pb-3 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($antrianAktif as $item)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 text-gray-400">{{ $loop->iteration }}</td>
                    <td class="py-3 font-mono font-semibold text-blue-700">{{ $item->kode_antrian }}</td>
                    <td class="py-3">{{ $item->nama_pengunjung }}</td>
                    <td class="py-3 text-gray-600">{{ $item->jenisLayanan->nama_layanan }}</td>
                    <td class="py-3"><x-badge :status="$item->status"/></td>
                    <td class="py-3 flex gap-2">
                        @if($item->status === 'menunggu')
                            <form method="POST" action="{{ route('petugas.antrian.panggil', $item) }}">
                                @csrf
                                <button type="submit"
                                    class="bg-blue-600 text-white px-3 py-1 rounded text-xs font-medium">
                                    📣 Panggil
                                </button>
                            </form>
                        @elseif(in_array($item->status, ['dipanggil', 'dilayani']))
                            <form method="POST" action="{{ route('petugas.antrian.selesai', $item) }}">
                                @csrf
                                <button type="submit"
                                    class="bg-green-600 text-white px-3 py-1 rounded text-xs font-medium">
                                    ✔ Selesai
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-8 text-center text-gray-400">
                        Tidak ada antrian menunggu saat ini
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </x-card>
</div>

{{-- ===== JADWAL BULAN INI ===== --}}
<div class="mt-6">
    <x-card title="Jadwal Piket Bulan Ini">
        <div class="grid grid-cols-7 gap-1 text-xs text-center">
            @foreach(['Min','Sen','Sel','Rab','Kam','Jum','Sab'] as $h)
                <div class="font-semibold text-gray-500 py-1">{{ $h }}</div>
            @endforeach
            {{-- Implementasi kalender sederhana: render di controller, kirim via $kalender --}}
            @foreach($kalender as $hari)
                <div class="py-2 rounded {{ $hari['jadwal'] ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-400' }}
                             {{ $hari['today'] ? 'ring-2 ring-blue-500' : '' }}">
                    {{ $hari['tanggal'] ?? '' }}
                </div>
            @endforeach
        </div>
        <p class="text-xs text-gray-400 mt-2">🟦 = Jadwal piket Anda</p>
    </x-card>
</div>

@endsection