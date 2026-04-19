<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password — PST BPS Kota Jambi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

<div class="w-full max-w-md">
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-[#003580] px-8 py-6 text-center text-white">
            <h1 class="text-lg font-bold">PST BPS Kota Jambi</h1>
            <p class="text-blue-200 text-sm mt-1">Reset Password</p>
        </div>

        <div class="px-8 py-8">
            <p class="text-sm text-gray-600 mb-6">
                Masukkan alamat email Anda. Kami akan mengirimkan tautan untuk
                mengatur ulang password.
            </p>

            @if (session('status'))
                <div class="bg-green-50 border border-green-200 text-green-700
                            px-4 py-3 rounded-lg mb-5 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email"
                           class="block text-sm font-medium text-gray-700 mb-1">
                        Email
                    </label>
                    <input id="email" type="email" name="email"
                           value="{{ old('email') }}" required autofocus
                           placeholder="nama@bps-jambi.go.id"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-[#003580]
                                  {{ $errors->has('email') ? 'border-red-400 bg-red-50' : '' }}">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="w-full bg-[#003580] text-white py-3 rounded-lg
                               font-semibold hover:bg-blue-800 transition text-sm">
                    Kirim Tautan Reset Password
                </button>
            </form>

            <div class="text-center mt-5">
                <a href="{{ route('login') }}"
                   class="text-sm text-blue-600 hover:underline">
                    ← Kembali ke Login
                </a>
            </div>
        </div>
    </div>
</div>

</body>
</html>