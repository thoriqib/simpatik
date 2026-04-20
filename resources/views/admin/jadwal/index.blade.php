@extends('layouts.app-admin')

@section('title', 'Jadwal Piket')
@section('page-title', 'Jadwal Piket')

@section('content')
{{-- Form tambah jadwal --}}
<x-card title="Tambah Jadwal Piket" class="mt-5">
    <form method="POST" action="{{ route('admin.jadwal.store') }}"
          class="flex flex-wrap gap-4 items-end">
        @csrf
        <div class="flex-1 min-w-40">
            <label class="block text-sm font-medium text-gray-700 mb-1">Petugas</label>
            <select name="user_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                <option value="">Pilih petugas...</option>
                @foreach($petugas as $p)
                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex-1 min-w-36">
            <label class="block text-sm font-medium text-gray-700 mb-1">Shift</label>
            <select name="shift_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                <option value="">Pilih shift...</option>
                @foreach($shifts as $s)
                    <option value="{{ $s->id }}">{{ $s->nama_shift }} ({{ $s->jam_mulai }})</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
            <input type="date" name="tanggal" min="{{ today()->toDateString() }}"
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
        </div>
        <button type="submit"
            class="bg-[#003580] text-white px-5 py-2 rounded-lg text-sm font-medium">
            Tambah
        </button>
    </form>
</x-card>
{{-- Filter bulan/tahun --}}
<x-card class="mt-4 mb-5">
    <form method="GET" action="{{ route('admin.jadwal.index') }}" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
            <select name="bulan" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                @foreach(range(1,12) as $b)
                    <option value="{{ $b }}" {{ $bulan == $b ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($b)->isoFormat('MMMM') }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
            <select 
                name="tahun" 
                class="border border-gray-300 rounded-xl px-7 py-2 text-sm shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
                @foreach(range(now()->year - 1, now()->year + 1) as $t)
                    <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>
                        {{ $t }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit"
            class="bg-[#003580] text-white px-4 py-2 rounded-lg text-sm font-medium">Tampilkan</button>
    </form>
</x-card>

{{-- Tabel jadwal --}}
<x-card title="Jadwal Bulan {{ \Carbon\Carbon::create($tahun, $bulan)->isoFormat('MMMM Y') }}">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b text-gray-500 text-left">
                <th class="pb-3 font-medium">Tanggal</th>
                <th class="pb-3 font-medium">Petugas</th>
                <th class="pb-3 font-medium">Shift</th>
                <th class="pb-3 font-medium">Status</th>
                <th class="pb-3 font-medium">Presensi Masuk</th>
                <th class="pb-3 font-medium">Presensi Keluar</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($jadwal->flatten() as $item)
            <tr class="hover:bg-gray-50">
                <td class="py-3">{{ $item->tanggal->isoFormat('ddd, D MMM') }}</td>
                <td class="py-3 font-medium">{{ $item->petugas->name }}</td>
                <td class="py-3">
                    {{ $item->shift->nama_shift }}
                    <span class="text-gray-400 text-xs">
                        ({{ $item->shift->jam_mulai }} – {{ $item->shift->jam_selesai }})
                    </span>
                </td>
                <td class="py-3"><x-badge :status="$item->status"/></td>
                <td class="py-3 text-gray-600">
                    {{ $item->presensi?->waktu_masuk?->format('H:i') ?? '-' }}
                </td>
                <td class="py-3 text-gray-600">
                    {{ $item->presensi?->waktu_keluar?->format('H:i') ?? '-' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="py-8 text-center text-gray-400">
                    Belum ada jadwal untuk bulan ini
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</x-card>


@endsection