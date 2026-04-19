@props(['title' => null])

<div {{ $attributes->merge(['class' => 'bg-white rounded-xl shadow-sm border border-gray-100 p-6']) }}>
    @if($title)
        <h3 class="text-base font-semibold text-gray-800 mb-4">{{ $title }}</h3>
    @endif
    {{ $slot }}
</div>