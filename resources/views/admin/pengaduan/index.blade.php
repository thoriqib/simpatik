@extends('layouts.app-admin')

@section('title', 'Pengaduan')
@section('page-title', 'Daftar Pengaduan')

@section('content')
<x-card class="mt-4">
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
            <tr class="hover:bg-gray-50">
                <td class="py-3 text-gray-500">{{ $item->created_at->format('d/m/Y') }}</td>
                <td class="py-3 font-medium">{{ $item->subjek }}</td>
                <td class="py-3"><x-badge :status="$item->status"/></td>
                <td class="py-3 text-gray-500">{{ $item->penanganan?->name ?? '-' }}</td>
                <td class="py-3">
                    <a href="{{ route('admin.pengaduan.show', $item) }}"
                       class="text-blue-600 hover:underline">Detail</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="py-8 text-center text-gray-400">Belum ada pengaduan</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $pengaduan->links() }}</div>
</x-card>
@endsection