{{-- Layout utama dengan header BPS --}}
<header style="background:#003580;" class="text-white p-4 text-center">
    <img src="{{ asset('images/logo-bps.png') }}" alt="BPS" class="h-12 inline mr-3">
    <h1 class="text-xl font-bold inline">PST BPS Kota Jambi</h1>
</header>

<main class="max-w-lg mx-auto p-4">
    <h2 class="text-center text-lg font-semibold mb-4">Ambil Nomor Antrian</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <form action="{{ route('antrian.ambil') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Jenis Layanan</label>
            <select name="jenis_layanan_id" class="w-full border rounded p-2" required>
                @foreach($jenisLayanan as $jenis)
                <option value="{{ $jenis->id }}">{{ $jenis->nama_layanan }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Nama Lengkap <span class="text-red-500">*</span></label>
            <input type="text" name="nama_pengunjung" class="w-full border rounded p-2" required>
        </div>
        <div class="mb-3">
            <label>No. HP (opsional)</label>
            <input type="tel" name="no_hp" class="w-full border rounded p-2">
        </div>
        <div class="mb-3">
            <label>Email (opsional)</label>
            <input type="email" name="email" class="w-full border rounded p-2">
        </div>
        <button type="submit"
            class="w-full bg-blue-700 text-white py-3 rounded font-bold text-lg">
            🎫 Ambil Nomor Antrian
        </button>
    </form>

    <div class="mt-6 text-center">
        <a href="{{ route('pengaduan.create') }}" class="text-sm text-gray-500 underline">
            📢 Kirim Pengaduan
        </a>
    </div>
</main>