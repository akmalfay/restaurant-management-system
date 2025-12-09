@props(['active' => false])

@php
// Kelas untuk tautan yang aktif (warna amber)
$activeClasses = 'bg-amber-50 dark:bg-amber-900/50 text-amber-700 dark:text-amber-300';
// Kelas untuk tautan normal
$defaultClasses = 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800';

$classes = ($active ?? false) ? $activeClasses : $defaultClasses;
@endphp

<a {{ $attributes->merge(['class' => $classes . ' group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-150']) }}>
    {{ $slot }}
</a>