<form action="{{ route('penilaian.store') }}" method="POST">
    @csrf
    <input type="hidden" name="antrian_id" value="{{ $antrian->id }}">
    <h3>Nilai Petugas: {{ $antrian->petugas->name }}</h3>

    {{-- Rating bintang dengan Alpine.js --}}
    <div x-data="{ rating: 0 }">
        @for($i = 1; $i <= 5; $i++)
        <button type="button"
            @click="rating = {{ $i }}"
            :class="rating >= {{ $i }} ? 'text-yellow-400' : 'text-gray-300'"
            class="text-4xl">★</button>
        @endfor
        <input type="hidden" name="nilai" :value="rating">
    </div>

    <textarea name="komentar" placeholder="Komentar (opsional)..." rows="3"
        class="w-full border rounded p-2 mt-3"></textarea>

    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded mt-3">
        Kirim Penilaian
    </button>
</form>