<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Pelayanan') — PST BPS Kota Jambi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans min-h-screen flex flex-col">

    {{-- Header --}}
    <header class="bg-[#003580] text-white shadow">
        <div class="max-w-2xl mx-auto px-4 py-4 flex items-center gap-4">
            <img src="{{ asset('images/logo-bps.png') }}" alt="BPS" class="h-12">
            <div>
                <div class="font-bold text-lg leading-tight">PST BPS Kota Jambi</div>
                <div class="text-blue-200 text-sm">Pelayanan Statistik Terpadu</div>
            </div>
        </div>
    </header>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="max-w-2xl mx-auto w-full px-4 mt-4">
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2">
                <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif
    @if($errors->any())
        <div class="max-w-2xl mx-auto w-full px-4 mt-4">
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- Content --}}
    <main class="flex-1 max-w-2xl mx-auto w-full px-4 py-6">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-white border-t mt-auto">
        <div class="max-w-2xl mx-auto px-4 py-4 text-center text-sm text-gray-400">
            © {{ date('Y') }} BPS Kota Jambi — Pelayanan Statistik Terpadu
        </div>
    </footer>

</body>
</html>