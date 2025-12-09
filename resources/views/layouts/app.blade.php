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

        <!-- Memuat Tailwind CSS via CDN -->
        <script src="https://cdn.tailwindcss.com"></script>

        <style>
            body {
                font-family: 'Figtree', sans-serif;
            }
        </style>
        
        <!-- Konfigurasi Tailwind untuk menambahkan warna 'amber' -->
        <script>
            tailwind.config = {
                darkMode: 'media', // atau 'class'
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Figtree', 'ui-sans-serif', 'system-ui'],
                        },
                        colors: {
                            amber: {
                                50: '#fffbeb',
                                100: '#fef3c7',
                                200: '#fde68a',
                                300: '#fcd34d',
                                400: '#fbbf24',
                                500: '#f59e0b',
                                600: '#d97706',
                                700: '#b45309',
                                800: '#92400e',
                                900: '#78350f',
                                950: '#451a03',
                            }
                        }
                    }
                }
            }
        </script>

        <!-- Scripts (Vite) - Dibiarkan untuk jika Anda beralih kembali -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        {{-- Mengganti latar belakang agar seragam dengan landing page --}}
        <div class="flex min-h-screen">

            {{-- SIDEBAR --}}
            <aside class="w-64 bg-[#0F3D3E] text-white flex-shrink-0">
                @include('layouts.navigation')
            </aside>

            {{-- MAIN CONTENT SECTION --}}
            <div class="flex-1 flex flex-col">

                {{-- HEADER --}}
                @isset($header)
                    <header class="bg-white shadow px-8 py-6 border-b border-gray-200">
                        {{ $header }}
                    </header>
                @endisset

                {{-- PAGE CONTENT --}}
                <main class="p-8">
                    {{ $slot }}
                </main>
            </div>

        </div>
    </div>
</body>
</html>