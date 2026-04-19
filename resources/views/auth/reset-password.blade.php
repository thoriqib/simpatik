<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password — PST BPS Kota Jambi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

<div class="w-full max-w-md">
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-[#003580] px-8 py-6 text-center text-white">
            <h1 class="text-lg font-bold">PST BPS Kota Jambi</h1>
            <p class="text-blue-200 text-sm mt-1">Buat Password Baru</p>
        </div>

        <div class="px-8 py-8">
            <form method="POST" action="{{ route('password.store') }}"
                  class="space-y-5" x-data="{ show: false }">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                {{-- Email --}}
                <div>
                    <label for="email"
                           class="block text-sm font-medium text-gray-700 mb-1">
                        Email
                    </label>
                    <input id="email" type="email" name="email"
                           value="{{ old('email', $request->email) }}"
                           required autocomplete="username"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-[#003580]
                                  {{ $errors->has('email') ? 'border-red-400 bg-red-50' : '' }}">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password baru --}}
                <div>
                    <label for="password"
                           class="block text-sm font-medium text-gray-700 mb-1">
                        Password Baru
                    </label>
                    <div class="relative">
                        <input id="password"
                               :type="show ? 'text' : 'password'"
                               name="password" required
                               autocomplete="new-password"
                               placeholder="Minimal 8 karakter"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm pr-11
                                      focus:outline-none focus:ring-2 focus:ring-[#003580]
                                      {{ $errors->has('password') ? 'border-red-400 bg-red-50' : '' }}">
                        <button type="button" @click="show = !show"
                                class="absolute right-3 top-1/2 -translate-y-1/2
                                       text-gray-400 hover:text-gray-600">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Konfirmasi password --}}
                <div>
                    <label for="password_confirmation"
                           class="block text-sm font-medium text-gray-700 mb-1">
                        Konfirmasi Password
                    </label>
                    <input id="password_confirmation"
                           :type="show ? 'text' : 'password'"
                           name="password_confirmation"
                           required autocomplete="new-password"
                           placeholder="Ulangi password baru"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-[#003580]
                                  {{ $errors->has('password_confirmation') ? 'border-red-400 bg-red-50' : '' }}">
                    @error('password_confirmation')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="w-full bg-[#003580] text-white py-3 rounded-lg
                               font-semibold hover:bg-blue-800 transition text-sm">
                    Simpan Password Baru
                </button>
            </form>
        </div>
    </div>
</div>

</body>
</html>