@extends('layouts.app-admin')

@section('title', 'Data Petugas')
@section('page-title', 'Data Petugas')

@section('content')
<div class="flex justify-between items-center mt-4 mb-5">
    <p class="text-sm text-gray-500">{{ $petugas->total() }} petugas terdaftar</p>
    <a href="{{ route('admin.petugas.create') }}"
       class="bg-[#003580] text-white px-4 py-2 rounded-lg text-sm font-medium
              hover:bg-blue-800 transition">
        + Tambah Petugas
    </a>
</div>

<x-card>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b text-gray-500 text-left">
                    <th class="pb-3 font-medium">Nama</th>
                    <th class="pb-3 font-medium">Email</th>
                    <th class="pb-3 font-medium text-center">Total Layanan</th>
                    <th class="pb-3 font-medium text-center">Rata-rata Nilai</th>
                    <th class="pb-3 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($petugas as $p)
                <tr class="hover:bg-gray-50">
                    <td class="py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-[#003580] rounded-full flex items-center
                                        justify-center text-white text-xs font-bold flex-shrink-0">
                                {{ strtoupper(substr($p->name, 0, 1)) }}
                            </div>
                            <span class="font-medium">{{ $p->name }}</span>
                        </div>
                    </td>
                    <td class="py-3 text-gray-500">{{ $p->email }}</td>
                    <td class="py-3 text-center">{{ $p->antrian_count ?? 0 }}</td>
                    <td class="py-3 text-center">
                        @if($p->avg_nilai)
                            <span class="text-yellow-500 font-semibold">
                                ★ {{ number_format($p->avg_nilai, 1) }}
                            </span>
                        @else
                            <span class="text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="py-3">
                        <div class="flex items-center gap-2">
                            {{-- Tombol Edit --}}
                            <a href="{{ route('admin.petugas.edit', $p) }}"
                               class="inline-flex items-center gap-1 text-xs font-medium
                                      bg-blue-50 text-blue-700 px-3 py-1.5 rounded-lg
                                      hover:bg-blue-100 transition">
                                ✏️ Edit
                            </a>

                            {{-- Tombol Hapus --}}
                            <button type="button"
                                x-data
                                @click="$dispatch('open-modal-hapus', { id: {{ $p->id }}, nama: '{{ addslashes($p->name) }}' })"
                                class="inline-flex items-center gap-1 text-xs font-medium
                                       bg-red-50 text-red-600 px-3 py-1.5 rounded-lg
                                       hover:bg-red-100 transition">
                                🗑️ Hapus
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-10 text-center text-gray-400">
                        Belum ada petugas terdaftar
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $petugas->links() }}</div>
</x-card>

{{-- ===== MODAL KONFIRMASI HAPUS ===== --}}
<div
    x-data="{ open: false, id: null, nama: '' }"
    @open-modal-hapus.window="open = true; id = $event.detail.id; nama = $event.detail.nama"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    style="background: rgba(0,0,0,0.5)">

    <div @click.outside="open = false"
         class="bg-white rounded-2xl shadow-xl max-w-sm w-full p-6">
        <div class="text-center mb-5">
            <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Hapus Petugas</h3>
            <p class="text-sm text-gray-500 mt-1">
                Yakin ingin menghapus petugas <strong x-text="nama"></strong>?
                Data jadwal dan presensi terkait akan ikut terhapus.
            </p>
        </div>
        <div class="flex gap-3">
            <button @click="open = false"
                class="flex-1 bg-gray-100 text-gray-700 py-2.5 rounded-lg text-sm
                       font-semibold hover:bg-gray-200 transition">
                Batal
            </button>
            {{-- Form hapus dinamis --}}
            <form :action="`/admin/petugas/${id}`" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="w-full bg-red-600 text-white py-2.5 rounded-lg text-sm
                           font-semibold hover:bg-red-700 transition">
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>

@endsection