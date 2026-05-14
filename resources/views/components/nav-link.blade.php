@props(['active' => false])

@php
$classes = $active
            ? 'group flex items-center px-4 py-3 text-sm font-bold rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white transition-all shadow-lg shadow-blue-500/30'
            : 'group flex items-center px-4 py-3 text-sm font-semibold rounded-xl text-gray-500 hover:bg-gray-100 hover:text-gray-900 transition-all';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    <span class="truncate">{{ $slot }}</span>
    @if($active)
        <span class="ml-auto w-2 h-2 rounded-full bg-white animate-pulse"></span>
    @endif
</a>
