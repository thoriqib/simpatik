@extends('layouts.app-admin')

@section('title', 'Detail Pengaduan')
@section('page-title', 'Detail Pengaduan')

@section('content')
<div class="mt-4">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-5">
        <a href="{{ route('admin.pengaduan.index') }}" class="hover:text-blue-600">Pengaduan</a>
        <span>/</span>
        <span class="text-gray-800 font-medium">{{ Str::limit($pengaduan->subjek, 40) }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Kolom kiri: Isi pengaduan + form tanggapan --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Isi Pengaduan --}}
            <x-card title="Isi Pengaduan">
                <h3 class="text-base font-semibold text-gray-800 mb-3">
                    {{ $pengaduan->subjek }}
                </h3>
                <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">
                    {{ $pengaduan->isi_pengaduan }}
                </p>

                @if($pengaduan->lampiran)
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs text-gray-500 mb-2">Lampiran:</p>
                    @php $ext = pathinfo($pengaduan->lampiran, PATHINFO_EXTENSION); @endphp
                    @if(in_array(strtolower($ext), ['jpg','jpeg','png']))
                        <a href="{{ Storage::url($pengaduan->lampiran) }}" target="_blank">
                            <img src="{{ Storage::url($pengaduan->lampiran) }}"
                                 alt="Lampiran"
                                 class="max-h-48 rounded-lg border border-gray-200 hover:opacity-90 transition">
                        </a>
                    @else
                        <a href="{{ Storage::url($pengaduan->lampiran) }}" target="_blank"
                           class="inline-flex items-center gap-2 text-blue-600 hover:underline text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                            </svg>
                            Lihat Lampiran ({{ strtoupper($ext) }})
                        </a>
                    @endif
                </div>
                @endif
            </x-card>

            {{-- Tanggapan / Form --}}
            @if($pengaduan->status === 'selesai')
                <x-card title="Tanggapan Admin">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <p class="text-sm text-gray-700 leading-relaxed">
                            {{ $pengaduan->tanggapan }}
                        </p>
                    </div>
                    <p class="text-xs text-gray-400 mt-3">
                        Ditanggapi oleh <strong>{{ $pengaduan->penanganan?->name }}</strong>
                        pada {{ $pengaduan->ditanggapi_pada?->isoFormat('D MMMM Y, HH:mm') }}
                    </p>
                </x-card>
            @else
                <x-card title="Berikan Tanggapan">
                    <form method="POST"
                          action="{{ route('admin.pengaduan.tanggapi', $pengaduan) }}"
                          class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Tanggapan <span class="text-red-500">*</span>
                            </label>
                            <textarea name="tanggapan" rows="5" required
                                placeholder="Tulis tanggapan resmi untuk pengaduan ini..."
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                                       resize-none focus:outline-none focus:ring-2 focus:ring-blue-500
                                       {{ $errors->has('tanggapan') ? 'border-red-400 bg-red-50' : '' }}">{{ old('tanggapan') }}</textarea>
                            @error('tanggapan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Update status --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Update Status
                            </label>
                            <select name="status"
                                class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full
                                       focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="diproses"
                                    {{ $pengaduan->status === 'diproses' ? 'selected' : '' }}>
                                    Tandai: Sedang Diproses
                                </option>
                                <option value="selesai">
                                    Tandai: Selesai (kirim tanggapan)
                                </option>
                            </select>
                        </div>

                        <button type="submit"
                            class="bg-[#003580] text-white px-6 py-2.5 rounded-lg text-sm
                                   font-semibold hover:bg-blue-800 transition">
                            Kirim Tanggapan
                        </button>
                    </form>
                </x-card>
            @endif
        </div>

        {{-- Kolom kanan: Info --}}
        <div class="space-y-5">
            <x-card title="Informasi">
                <dl class="space-y-3 text-sm">
                    <div>
                        <dt class="text-gray-500 text-xs uppercase tracking-wider mb-1">Status</dt>
                        <dd><x-badge :status="$pengaduan->status"/></dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 text-xs uppercase tracking-wider mb-1">Tanggal Masuk</dt>
                        <dd class="text-gray-800 font-medium">
                            {{ $pengaduan->created_at->isoFormat('D MMMM Y') }}
                        </dd>
                        <dd class="text-gray-400 text-xs">
                            {{ $pengaduan->created_at->isoFormat('HH:mm') }} WIB
                            ({{ $pengaduan->created_at->diffForHumans() }})
                        </dd>
                    </div>
                    @if($pengaduan->ditangani_oleh)
                    <div>
                        <dt class="text-gray-500 text-xs uppercase tracking-wider mb-1">Ditangani</dt>
                        <dd class="text-gray-800 font-medium">{{ $pengaduan->penanganan->name }}</dd>
                    </div>
                    @endif
                </dl>
            </x-card>

            {{-- Navigasi --}}
            <div class="flex flex-col gap-2">
                <a href="{{ route('admin.pengaduan.index') }}"
                   class="text-center bg-gray-100 text-gray-700 px-4 py-2.5 rounded-lg text-sm
                          font-medium hover:bg-gray-200 transition">
                    ← Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>
</div>
@endsection