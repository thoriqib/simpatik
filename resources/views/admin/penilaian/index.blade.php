@extends('layouts.app-admin')

@section('title', 'Data Penilaian')
@section('page-title', 'Data Penilaian Pelayanan')

@section('content')

{{-- Filter --}}
<x-card class="mt-4 mb-5">
    <form method="GET" action="{{ route('admin.penilaian.index') }}"
          class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs text-gray-500 mb-1">Dari</label>
            <input type="date" name="dari" value="{{ $dari }}"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Sampai</label>
            <input type="date" name="sampai" value="{{ $sampai }}"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Nilai</label>
            <select name="nilai" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="">Semua Nilai</option>
                @foreach(range(5, 1) as $n)
                    <option value="{{ $n }}" {{ $nilai == $n ? 'selected' : '' }}>
                        {{ str_repeat('★', $n) }} ({{ $n }} bintang)
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit"
            class="bg-[#003580] text-white px-4 py-2 rounded-lg text-sm font-medium">
            Tampilkan
        </button>
    </form>
</x-card>

{{-- Rata-rata keseluruhan --}}
@if($rataRata)
<div class="mb-5 bg-yellow-50 border border-yellow-200 rounded-xl px-5 py-4
            flex items-center gap-4">
    <div class="text-4xl font-black text-yellow-500">
        {{ number_format($rataRata, 1) }}
    </div>
    <div>
        <div class="text-yellow-600 font-semibold">Rata-rata Penilaian</div>
        <div class="text-yellow-500 text-sm">
            @for($i = 1; $i <= 5; $i++)
                <span class="{{ $i <= round($rataRata) ? 'text-yellow-400' : 'text-yellow-200' }}">★</span>
            @endfor
            dari {{ $penilaian->total() }} penilaian
        </div>
    </div>
</div>
@endif

{{-- Tabel penilaian --}}
<x-card>
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b text-gray-500 text-left">
                <th class="pb-3 font-medium">Tanggal</th>
                <th class="pb-3 font-medium">Petugas</th>
                <th class="pb-3 font-medium">Layanan</th>
                <th class="pb-3 font-medium">Nilai</th>
                <th class="pb-3 font-medium">Komentar</th>
                <th class="pb-3 font-medium">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($penilaian as $item)
            <tr class="hover:bg-gray-50">
                <td class="py-3 text-gray-500 text-xs">
                    {{ $item->antrian->tanggal->isoFormat('D MMM Y') }}
                </td>
                <td class="py-3 font-medium">{{ $item->petugas->name }}</td>
                <td class="py-3 text-gray-500 text-xs">
                    {{ $item->antrian->jenisLayanan->nama_layanan }}
                </td>
                <td class="py-3">
                    <div class="flex items-center gap-1">
                        <span class="text-yellow-400 font-bold">{{ $item->nilai }}</span>
                        <span class="text-yellow-400 text-xs">
                            {{ str_repeat('★', $item->nilai) }}{{ str_repeat('☆', 5 - $item->nilai) }}
                        </span>
                    </div>
                </td>
                <td class="py-3 text-gray-600 text-xs max-w-xs">
                    {{ $item->komentar ? Str::limit($item->komentar, 60) : '—' }}
                </td>
                <td class="py-3">
                    <form action="{{ route('admin.penilaian.destroy', $item) }}" method="POST"
                          onsubmit="return confirm('Hapus penilaian ini?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="text-xs text-red-500 hover:underline">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="py-8 text-center text-gray-400">
                    Belum ada penilaian pada periode ini
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $penilaian->links() }}</div>
</x-card>

@endsection