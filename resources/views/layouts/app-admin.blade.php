<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — SIMPATIK - PST BPS Kota Jambi</title>
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
            <div class="font-bold text-sm leading-tight">SIMPATIK 1571</div>
            <div class="text-xs text-blue-200">Sistem Informasi Manajemen Pelayanan Statistik BPS Kota Jambi</div>
        </div>
    </div>

    {{-- Navigasi --}}
    <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-700 transition
                  {{ request()->routeIs('admin.dashboard') ? 'bg-blue-700' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 12l9-9 9 9M5 10v10h5v-6h4v6h5V10"/>
            </svg>
            Dashboard
        </a>

        <p class="text-xs text-blue-300 uppercase tracking-wider px-3 pt-4 pb-1">Petugas</p>
        <a href="{{ route('admin.petugas.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-700 transition
                  {{ request()->routeIs('admin.petugas.*') ? 'bg-blue-700' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87m6-4a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            Data Petugas
        </a>
        <a href="{{ route('admin.shift.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-700 transition
                  {{ request()->routeIs('admin.shift.*') ? 'bg-blue-700' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Pengaturan Shift
        </a>
        <a href="{{ route('admin.jadwal.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-700 transition
                  {{ request()->routeIs('admin.jadwal.*') ? 'bg-blue-700' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Jadwal Piket
        </a>

        <p class="text-xs text-blue-300 uppercase tracking-wider px-3 pt-4 pb-1">Layanan</p>
        <a href="{{ route('admin.jenis-layanan.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-700 transition
                  {{ request()->routeIs('admin.jenis-layanan.*') ? 'bg-blue-700' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Jenis Layanan
        </a>
        <a href="{{ route('admin.pengaduan.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-700 transition
                  {{ request()->routeIs('admin.pengaduan.*') ? 'bg-blue-700' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
            </svg>
            Pengaduan
        </a>

        <p class="text-xs text-blue-300 uppercase tracking-wider px-3 pt-4 pb-1">Laporan</p>
        <a href="{{ route('admin.laporan.antrian') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-700 transition
                  {{ request()->routeIs('admin.laporan.antrian') ? 'bg-blue-700' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            Lap. Antrian
        </a>
        <a href="{{ route('admin.laporan.penilaian') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-700 transition
                  {{ request()->routeIs('admin.laporan.penilaian') ? 'bg-blue-700' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
            </svg>
            Lap. Penilaian
        </a>
        <a href="{{ route('admin.laporan.presensi') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-700 transition
                  {{ request()->routeIs('admin.laporan.presensi') ? 'bg-blue-700' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Lap. Presensi
        </a>
        {{-- Di dalam <nav> admin, tambahkan setelah "Lap. Presensi" --}}
        <p class="text-xs text-blue-300 uppercase tracking-wider px-3 pt-4 pb-1">Penilaian</p>
        <a href="{{ route('admin.penilaian.index') }}"
        class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-700 transition
                {{ request()->routeIs('admin.penilaian.*') ? 'bg-blue-700' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0
                        1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755
                        1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197
                        -1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81
                        .588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
            </svg>
            Semua Penilaian
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
                <div class="text-xs text-blue-300">Administrator</div>
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
        @if(session('warning'))
            <div class="bg-yellow-50 border border-yellow-200 text-yellow-800
                        px-4 py-3 rounded-lg mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0 text-yellow-500"
                    fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213
                            2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11
                            13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1
                            1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                {{ session('warning') }}
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