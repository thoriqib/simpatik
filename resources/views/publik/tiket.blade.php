{{-- Halaman tiket menggunakan layout sendiri untuk optimasi cetak --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket {{ $antrian->kode_antrian }}</title>
    @vite('resources/css/app.css')
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white; }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    {{-- Tiket --}}
    <div class="bg-white rounded-2xl shadow-lg w-72 overflow-hidden">
        {{-- Header tiket --}}
        <div class="bg-[#003580] text-white px-6 py-4 text-center">
            <img src="{{ asset('images/logo-bps.png') }}" alt="BPS" class="h-10 mx-auto mb-2">
            <div class="font-bold text-sm">PST BPS Kota Jambi</div>
            <div class="text-blue-200 text-xs">Pelayanan Statistik Terpadu</div>
        </div>

        {{-- Nomor antrian --}}
        <div class="px-6 py-5 text-center border-b border-dashed border-gray-200">
            <div class="text-xs text-gray-500 uppercase tracking-widest mb-1">
                {{ $antrian->jenisLayanan->nama_layanan }}
            </div>
            <div class="text-7xl font-black text-[#003580] tracking-tight my-2">
                {{ $antrian->kode_antrian }}
            </div>
            <div class="text-sm text-gray-600">{{ $antrian->nama_pengunjung }}</div>
            <div class="text-xs text-gray-400 mt-1">
                {{ $antrian->tanggal->isoFormat('dddd, D MMMM Y') }}
            </div>
        </div>

        {{-- Info antrian --}}
        <div class="px-6 py-4">
            @if($menunggu > 0)
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg px-4 py-2.5 text-center mb-3">
                <div class="text-xs text-yellow-600">Antrian di depan Anda</div>
                <div class="text-2xl font-bold text-yellow-700">{{ $menunggu }}</div>
            </div>
            @else
            <div class="bg-green-50 border border-green-200 rounded-lg px-4 py-2.5 text-center mb-3">
                <div class="text-sm font-medium text-green-700">✅ Anda berikutnya!</div>
            </div>
            @endif
            <p class="text-xs text-gray-400 text-center">
                Harap menunggu hingga nomor Anda dipanggil
            </p>
        </div>

        {{-- Barcode area (placeholder) --}}
        <div class="px-6 pb-5 text-center">
            <div class="text-xs text-gray-300 font-mono">{{ $antrian->kode_antrian }} · {{ now()->format('d/m/Y') }}</div>
        </div>
    </div>

    {{-- Tombol aksi (tidak ikut cetak) --}}
    <div class="no-print fixed bottom-6 left-0 right-0 flex justify-center gap-3 px-4">
        <button onclick="window.print()"
            class="bg-[#003580] text-white px-6 py-3 rounded-full font-semibold shadow-lg
                   hover:bg-blue-800 transition flex items-center gap-2">
            🖨️ Cetak Tiket
        </button>
        <a href="{{ route('antrian.display') }}"
            class="bg-white text-gray-700 px-6 py-3 rounded-full font-semibold shadow-lg
                   hover:bg-gray-50 transition flex items-center gap-2 border">
            📺 Display
        </a>
        @if($antrian->status === 'selesai')
        <a href="{{ route('penilaian.create', $antrian->kode_antrian) }}"
            class="bg-yellow-400 text-yellow-900 px-6 py-3 rounded-full font-semibold shadow-lg
                   hover:bg-yellow-300 transition flex items-center gap-2">
            ⭐ Beri Nilai
        </a>
        @endif
    </div>

</body>
</html>