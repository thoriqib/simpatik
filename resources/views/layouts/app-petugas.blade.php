<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — PST BPS Kota Jambi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Alpine.js sudah di-bundle via app.js --}}
</head>
<body class="bg-gray-100 font-sans">

{{-- ===== SIDEBAR ===== --}}
<aside class="fixed top-0 left-0 h-full w-64 bg-[#003580] text-white flex flex-col z-30">
    {{-- Logo --}}
    <div class="flex items-center gap-3 px-6 py-5 border-b border-blue-700">
        <img src="{{ asset('images/logo-bps.png') }}" alt="BPS" class="h-10">
        <div>
            <div class="font-bold text-sm leading-tight">PST BPS</div>
            <div class="text-xs text-blue-200">Kota Jambi</div>
        </div>
    </div>

    {{-- Navigasi --}}
    {{-- Ganti bagian <nav> dengan menu khusus petugas --}}
    <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
        <a href="{{ route('petugas.dashboard') }}"
        class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-700 transition
                {{ request()->routeIs('petugas.dashboard') ? 'bg-blue-700' : '' }}">
            {{-- ikon dashboard --}}
            Dashboard
        </a>
        <a href="{{ route('petugas.jadwal') }}"
        class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-700 transition
                {{ request()->routeIs('petugas.jadwal') ? 'bg-blue-700' : '' }}">
            {{-- ikon kalender --}}
            Jadwal Saya
        </a>

        <p class="text-xs text-blue-300 uppercase tracking-wider px-3 pt-4 pb-1">Presensi</p>
        <a href="{{ route('petugas.dashboard') }}#presensi"
        class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-700 transition">
            {{-- ikon clock --}}
            Presensi Hari Ini
        </a>

        <p class="text-xs text-blue-300 uppercase tracking-wider px-3 pt-4 pb-1">Antrian</p>
        <a href="{{ route('petugas.dashboard') }}#antrian"
        class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-700 transition">
            {{-- ikon queue --}}
            Kelola Antrian
        </a>
    </nav>



    {{-- Profil bawah --}}
    <div class="px-4 py-4 border-t border-blue-700">
        <div class="flex items-center gap-3 px-3 py-2">
            <div class="w-8 h-8 bg-blue-300 rounded-full flex items-center justify-center text-blue-900 font-bold text-sm">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <div class="text-sm font-medium truncate">{{ auth()->user()->name }}</div>
                <div class="text-xs text-blue-300">Petugas Pelayanan</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="mt-1">
            @csrf
            <button type="submit"
                class="w-full text-left flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-700 transition text-sm text-blue-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Keluar
            </button>
        </form>
    </div>
</aside>

{{-- ===== MAIN CONTENT ===== --}}
<div class="ml-64 min-h-screen flex flex-col">

    {{-- Topbar --}}
    <header class="bg-white shadow-sm px-6 py-4 flex items-center justify-between">
        <h1 class="text-lg font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
        <div class="text-sm text-gray-500">{{ now()->isoFormat('dddd, D MMMM Y') }}</div>
    </header>

    {{-- Flash messages --}}
    <div class="px-6 pt-4">
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif
    </div>

    {{-- Page content --}}
    <main class="flex-1 px-6 pb-8">
        @yield('content')
    </main>
</div>

</body>
</html>