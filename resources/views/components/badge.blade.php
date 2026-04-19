@props(['status'])

@php
$classes = match($status) {
    'menunggu'  => 'bg-yellow-100 text-yellow-700',
    'dipanggil' => 'bg-blue-100 text-blue-700',
    'dilayani'  => 'bg-purple-100 text-purple-700',
    'selesai'   => 'bg-green-100 text-green-700',
    'batal'     => 'bg-gray-100 text-gray-500',
    'baru'      => 'bg-red-100 text-red-700',
    'diproses'  => 'bg-yellow-100 text-yellow-700',
    'hadir'     => 'bg-green-100 text-green-700',
    'izin'      => 'bg-blue-100 text-blue-700',
    'sakit'     => 'bg-orange-100 text-orange-700',
    'alpha'     => 'bg-red-100 text-red-700',
    default     => 'bg-gray-100 text-gray-600',
};
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $classes }}">
    {{ ucfirst($status) }}
</span>