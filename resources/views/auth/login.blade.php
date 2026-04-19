<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — PST BPS Kota Jambi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

<div class="w-full max-w-md">

    {{-- Card login --}}
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

        {{-- Header biru --}}
        <div class="bg-[#003580] px-8 py-8 text-center text-white">
            <img src="{{ asset('images/logo-bps.png') }}" alt="BPS"
                 class="h-16 mx-auto mb-3"
                 onerror="this.style.display='none'">
            <h1 class="text-xl font-bold">PST BPS Kota Jambi</h1>
            <p class="text-blue-200 text-sm mt-1">
                Sistem Manajemen Pelayanan Statistik Terpadu
            </p>
        </div>

        {{-- Form --}}
        <div class="px-8 py-8">
            <h2 class="text-lg font-semibold text-gray-800 mb-6 text-center">
                Masuk ke Sistem
            </h2>

            {{-- Status (misal: password reset berhasil) --}}
            @if (session('status'))
                <div class="bg-green-50 border border-green-200 text-green-700
                            px-4 py-3 rounded-lg mb-5 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email"
                           class="block text-sm font-medium text-gray-700 mb-1">
                        Email
                    </label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="nama@bps-jambi.go.id"
                        class="w-full border rounded-lg px-4 py-2.5 text-sm
                               focus:outline-none focus:ring-2 focus:ring-[#003580] focus:border-transparent
                               {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                    >
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password dengan toggle show/hide --}}
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label for="password"
                               class="block text-sm font-medium text-gray-700">
                            Password
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                               class="text-xs text-blue-600 hover:underline">
                                Lupa password?
                            </a>
                        @endif
                    </div>

                    <div class="relative" x-data="{ show: false }">
                        <input
                            id="password"
                            :type="show ? 'text' : 'password'"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="••••••••"
                            class="w-full border rounded-lg px-4 py-2.5 text-sm pr-11
                                   focus:outline-none focus:ring-2 focus:ring-[#003580] focus:border-transparent
                                   {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                        >
                        <button type="button"
                                @click="show = !show"
                                class="absolute right-3 top-1/2 -translate-y-1/2
                                       text-gray-400 hover:text-gray-600 transition">
                            {{-- Ikon mata terbuka --}}
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943
                                         9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            {{-- Ikon mata tertutup --}}
                            <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7
                                         a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243
                                         M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532
                                         l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5
                                         c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0
                                         01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember me --}}
                <div class="flex items-center">
                    <input id="remember_me" type="checkbox" name="remember"
                           class="w-4 h-4 rounded border-gray-300
                                  text-[#003580] focus:ring-[#003580]">
                    <label for="remember_me" class="ml-2 text-sm text-gray-600">
                        Ingat saya selama 30 hari
                    </label>
                </div>

                {{-- Tombol login --}}
                <button type="submit"
                        class="w-full bg-[#003580] text-white py-3 rounded-lg
                               font-semibold hover:bg-blue-800 active:scale-95
                               transition text-sm">
                    Masuk
                </button>
            </form>
        </div>
    </div>

    {{-- Link kembali ke antrian --}}
    <div class="text-center mt-5">
        <a href="{{ route('home') }}"
           class="text-sm text-gray-500 hover:text-gray-700 hover:underline">
            ← Kembali ke Halaman Antrian
        </a>
    </div>

    <p class="text-center text-xs text-gray-400 mt-3">
        © {{ date('Y') }} BPS Kota Jambi. Hak Cipta Dilindungi.
    </p>
</div>

</body>
</html>