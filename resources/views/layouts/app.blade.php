<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-[#F7F4ED]">
    <div class="min-h-screen flex">

        {{-- Sidebar --}}
        <aside class="w-64 bg-[#0F3D3E] text-white flex-shrink-0">
            @include('layouts.navigation')
        </aside>

        {{-- Content Area --}}
        <div class="flex-1">
            {{-- Page Header --}}
            @isset($header)
                <header class="px-8 py-6 border-b border-[#E5E5E5] bg-white">
                    {{ $header }}
                </header>
            @endisset

            {{-- Page Content --}}
            <main class="p-8">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
