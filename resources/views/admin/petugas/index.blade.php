@extends('layouts.app-admin')

@section('title', 'Data Petugas')
@section('page-title', 'Data Petugas')

@section('content')
<div class="flex justify-between items-center mt-4 mb-5">
    <p class="text-sm text-gray-500">{{ $petugas->total() }} petugas terdaftar</p>
    <a href="{{ route('admin.petugas.create') }}"
       class="bg-[#003580] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-800 transition">
        + Tambah Petugas
    </a>
</div>

<x-card>
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b text-gray-500 text-left">
                <th class="pb-3 font-medium">Nama</th>
                <th class="pb-3 font-medium">Email</th>
                <th class="pb-3 font-medium">Total Layanan</th>
                <th class="pb-3 font-medium">Rata-rata Nilai</th>
                <th class="pb-3 font-medium">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($petugas as $p)
            <tr class="hover:bg-gray-50">
                <td class="py-3 font-medium">{{ $p->name }}</td>
                <td class="py-3 text-gray-500">{{ $p->email }}</td>
                <td class="py-3">{{ $p->antrian_count ?? 0 }}</td>
                <td class="py-3">
                    @if($p->avg_nilai)
                        <span class="text-yellow-500">★</span> {{ number_format($p->avg_nilai, 1) }}
                    @else
                        <span class="text-gray-400">-</span>
                    @endif
                </td>
                <td class="py-3">
                    <a href="{{ route('admin.petugas.edit', $p) }}"
                       class="text-blue-600 hover:underline mr-3">Edit</a>
                    <form action="{{ route('admin.petugas.destroy', $p) }}" method="POST" class="inline"
                          onsubmit="return confirm('Hapus petugas ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="py-8 text-center text-gray-400">Belum ada petugas</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $petugas->links() }}</div>
</x-card>
@endsection