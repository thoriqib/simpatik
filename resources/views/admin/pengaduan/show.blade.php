@extends('layouts.app-admin')

@section('title', 'Detail Pengaduan')
@section('page-title', 'Detail Pengaduan')

@section('content')
<div class="mt-4 grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Isi pengaduan --}}
    <div class="lg:col-span-2 space-y-5">
        <x-card title="Isi Pengaduan">
            <dl class="space-y-3 text-sm">
                <div>
                    <dt class="text-gray-500 font-medium">Subjek</dt>
                    <dd class="text-gray-800 mt-0.5">{{ $pengaduan->subjek }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500 font-medium">Isi Pengaduan</dt>
                    <dd class="text-gray-800 mt-0.5 leading-relaxed">{{ $pengaduan->isi_pengaduan }}</dd>
                </div>
                @if($pengaduan->lampiran)
                <div>
                    <dt class="text-gray-500 font-medium">Lampiran</dt>
                    <dd class="mt-0.5">
                        <a href="{{ Storage::url($pengaduan->lampiran) }}" target="_blank"
                           class="text-blue-600 hover:underline text-sm">Lihat Lampiran</a>
                    </dd>
                </div>
                @endif
            </dl>
        </x-card>

        {{-- Form tanggapan --}}
        @if($pengaduan->status !== 'selesai')
        <x-card title="Berikan Tanggapan">
            <form method="POST" action="{{ route('admin.pengaduan.tanggapi', $pengaduan) }}">
                @csrf
                <textarea name="tanggapan" rows="5" required
                    placeholder="Tulis tanggapan..."
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                           focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('tanggapan', $pengaduan->tanggapan) }}</textarea>
                <button type="submit"
                    class="mt-3 bg-[#003580] text-white px-5 py-2 rounded-lg text-sm font-medium">
                    Kirim Tanggapan & Selesaikan
                </button>
            </form>
        </x-card>
        @else
        <x-card title="Tanggapan">
            <p class="text-sm text-gray-700 leading-relaxed">{{ $pengaduan->tanggapan }}</p>
            <p class="text-xs text-gray-400 mt-2">
                Oleh {{ $pengaduan->penanganan?->name }} pada
                {{ $pengaduan->ditanggapi_pada?->isoFormat('D MMM Y, HH:mm') }}
            </p>
        </x-card>
        @endif
    </div>

    {{-- Info samping --}}
    <div>
        <x-card title="Informasi">
            <dl class="space-y-3 text-sm">
                <div>
                    <dt class="text-gray-500">Status</dt>
                    <dd class="mt-0.5"><x-badge :status="$pengaduan->status"/></dd>
                </div>
                <div>
                    <dt class="text-gray-500">Tanggal Masuk</dt>
                    <dd class="text-gray-800">{{ $pengaduan->created_at->isoFormat('D MMM Y, HH:mm') }}</dd>
                </div>
            </dl>
        </x-card>
    </div>
</div>
@endsection