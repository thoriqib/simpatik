{{-- Layout khusus fullscreen untuk monitor ruang tunggu --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="10">
    <title>Display Antrian — PST BPS Kota Jambi</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-[#003580] min-h-screen flex flex-col text-white">

    {{-- Header display --}}
    <div class="flex items-center justify-between px-10 py-5 border-b border-blue-700">
        <div class="flex items-center gap-4">
            <img src="{{ asset('images/logo-bps.png') }}" alt="BPS" class="h-14">
            <div>
                <div class="text-xl font-bold">PST BPS Kota Jambi</div>
                <div class="text-blue-200 text-sm">Pelayanan Statistik Terpadu</div>
            </div>
        </div>
        <div class="text-right">
            <div class="text-2xl font-mono font-bold" id="jam">--:--:--</div>
            <div class="text-blue-200 text-sm">{{ now()->isoFormat('dddd, D MMMM Y') }}</div>
        </div>
    </div>

    {{-- Konten utama --}}
    <div class="flex-1 grid grid-cols-3 gap-6 p-8">

        {{-- Panel kiri: antrian dipanggil / dilayani --}}
        <div class="col-span-2 space-y-4">
            <h2 class="text-lg font-semibold text-blue-200 uppercase tracking-wider mb-4">
                Sedang Dilayani
            </h2>
            @forelse($antrian as $item)
            <div class="bg-white bg-opacity-10 backdrop-blur rounded-2xl px-8 py-5
                        flex items-center justify-between border border-white border-opacity-20">
                <div>
                    <div class="text-5xl font-black tracking-tight">{{ $item->kode_antrian }}</div>
                    <div class="text-blue-200 text-sm mt-1">{{ $item->jenisLayanan->nama_layanan }}</div>
                </div>
                <div class="text-right">
                    <div class="text-lg font-semibold">{{ $item->petugas->name ?? 'Loket' }}</div>
                    <x-badge :status="$item->status"/>
                </div>
            </div>
            @empty
            <div class="bg-white bg-opacity-5 rounded-2xl px-8 py-12 text-center text-blue-300">
                Belum ada antrian yang dipanggil
            </div>
            @endforelse
        </div>

        {{-- Panel kanan: statistik --}}
        <div class="space-y-4">
            <div class="bg-white bg-opacity-10 rounded-2xl p-6 text-center border border-white border-opacity-20">
                <div class="text-blue-200 text-sm uppercase tracking-wider mb-2">Menunggu</div>
                <div class="text-8xl font-black">{{ $menunggu }}</div>
                <div class="text-blue-200 text-sm mt-1">antrian</div>
            </div>
            <div class="bg-white bg-opacity-10 rounded-2xl p-6 text-center border border-white border-opacity-20">
                <div class="text-blue-200 text-sm uppercase tracking-wider mb-2">Selesai Hari Ini</div>
                <div class="text-5xl font-black text-green-300">{{ $selesai }}</div>
            </div>
            <div class="bg-white text-[#003580] rounded-2xl p-5 text-center">
                <div class="text-sm font-medium mb-1">Ambil Antrian</div>
                <div class="text-xs text-gray-500">Kunjungi:</div>
                <div class="font-mono font-bold text-sm mt-1">pst.bps-jambi.go.id</div>
            </div>
        </div>
    </div>

    <script>
        // Update jam real-time
        function updateJam() {
            const now = new Date();
            document.getElementById('jam').textContent =
                now.toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit', second:'2-digit'});
        }
        updateJam();
        setInterval(updateJam, 1000);
    </script>
</body>
</html>