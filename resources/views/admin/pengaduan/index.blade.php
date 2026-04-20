@extends('layouts.app-admin')

@section('title', 'Pengaduan')
@section('page-title', 'Daftar Pengaduan')

@section('content')

{{-- Tab filter status --}}
<div class="flex gap-2 mt-4 mb-5 flex-wrap">
    <a href="{{ route('admin.pengaduan.index') }}"
       class="px-4 py-2 rounded-lg text-sm font-medium transition
              {{ !$status ? 'bg-[#003580] text-white' : 'bg-white text-gray-600 border hover:bg-gray-50' }}">
        Semua
    </a>
    <a href="{{ route('admin.pengaduan.index', ['status' => 'baru']) }}"
       class="px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-2
              {{ $status === 'baru' ? 'bg-red-600 text-white' : 'bg-white text-gray-600 border hover:bg-gray-50' }}">
        Baru
        @if($jumlahBaru > 0)
            <span class="bg-red-100 text-red-700 text-xs px-1.5 py-0.5 rounded-full
                         {{ $status === 'baru' ? 'bg-red-500 text-white' : '' }}">
                {{ $jumlahBaru }}
            </span>
        @endif
    </a>
    <a href="{{ route('admin.pengaduan.index', ['status' => 'diproses']) }}"
       class="px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-2
              {{ $status === 'diproses' ? 'bg-yellow-500 text-white' : 'bg-white text-gray-600 border hover:bg-gray-50' }}">
        Diproses
        @if($jumlahDiproses > 0)
            <span class="text-xs px-1.5 py-0.5 rounded-full
                         {{ $status === 'diproses' ? 'bg-yellow-400 text-yellow-900' : 'bg-yellow-100 text-yellow-700' }}">
                {{ $jumlahDiproses }}
            </span>
        @endif
    </a>
    <a href="{{ route('admin.pengaduan.index', ['status' => 'selesai']) }}"
       class="px-4 py-2 rounded-lg text-sm font-medium transition
              {{ $status === 'selesai' ? 'bg-green-600 text-white' : 'bg-white text-gray-600 border hover:bg-gray-50' }}">
        Selesai
    </a>
</div>

<x-card>
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b text-gray-500 text-left">
                <th class="pb-3 font-medium">Tgl Masuk</th>
                <th class="pb-3 font-medium">Subjek</th>
                <th class="pb-3 font-medium">Status</th>
                <th class="pb-3 font-medium">Ditangani</th>
                <th class="pb-3 font-medium">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($pengaduan as $item)
            <tr class="hover:bg-gray-50
                       {{ $item->status === 'baru' ? 'border-l-4 border-l-red-400' : '' }}">
                <td class="py-3 text-gray-500 text-xs">
                    {{ $item->created_at->isoFormat('D MMM Y') }}
                    <div class="text-gray-400">{{ $item->created_at->diffForHumans() }}</div>
                </td>
                <td class="py-3">
                    <div class="font-medium text-gray-800">{{ $item->subjek }}</div>
                    <div class="text-xs text-gray-400 mt-0.5">
                        {{ Str::limit($item->isi_pengaduan, 60) }}
                    </div>
                </td>
                <td class="py-3"><x-badge :status="$item->status"/></td>
                <td class="py-3 text-gray-500 text-sm">
                    {{ $item->penanganan?->name ?? '—' }}
                </td>
                <td class="py-3">
                    <a href="{{ route('admin.pengaduan.show', $item) }}"
                       class="inline-flex items-center gap-1 text-xs font-medium
                              bg-blue-50 text-blue-700 px-3 py-1.5 rounded-lg
                              hover:bg-blue-100 transition">
                        Detail →
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="py-10 text-center text-gray-400">
                    Tidak ada pengaduan
                    {{ $status ? "dengan status \"{$status}\"" : '' }}
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $pengaduan->links() }}</div>
</x-card>

@endsection