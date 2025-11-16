<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RestoMan - Cita Rasa Autentik</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,700&display=swap" rel="stylesheet" />

    <!-- Memuat Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* CSS untuk menyembunyikan scrollbar pada slider */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none; /* IE dan Edge */
            scrollbar-width: none; /* Firefox */
        }
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
</head>
<body class="bg-white text-gray-800 dark:bg-gray-950 dark:text-gray-200 min-h-screen font-sans antialiased">

    <!-- --- Komponen 1: Header & Navigasi --- -->
    <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-sm shadow-sm dark:bg-gray-900/80">
        <nav class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 flex justify-between items-center py-5">
            <div class="text-3xl font-bold text-amber-600 dark:text-amber-400">
                RestoMan
            </div>
            
            <!-- Navigasi Desktop -->
            <div class="hidden md:flex space-x-8 items-center">
                <a href="#hero" class="text-gray-600 dark:text-gray-300 hover:text-amber-600 dark:hover:text-amber-400 transition-colors">Home</a>
                <a href="#menu" class="text-gray-600 dark:text-gray-300 hover:text-amber-600 dark:hover:text-amber-400 transition-colors">Menu</a>
                
                {{-- Logika Tautan Otentikasi Laravel --}}
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-5 py-2 bg-amber-600 text-white rounded-lg font-medium hover:bg-amber-700 transition-colors shadow-sm">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-5 py-2 bg-amber-600 text-white rounded-lg font-medium hover:bg-amber-700 transition-colors shadow-sm">
                            Login
                        </a>

                        {{-- Menambahkan tautan Register jika rute 'register' ada --}}
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-5 py-2 bg-amber-600 text-white rounded-lg font-medium hover:bg-amber-700 transition-colors shadow-sm">
                                Register
                            </a>
                        @endif
                    @endauth
                @endif
            </div>

            <!-- Tombol Menu Mobile -->
            <div class="md:hidden">
                <button id="mobile-menu-button" class="text-gray-700 dark:text-gray-300 focus:outline-none">
                    <!-- Ikon Menu (Heroicons) -->
                    <svg id="icon-menu" class="w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    <!-- Ikon X (Heroicons) - Tersembunyi -->
                    <svg id="icon-x" class="w-7 h-7 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </nav>

        <!-- Menu Mobile Dropdown -->
        <div id="mobile-menu" class="md:hidden hidden absolute top-full left-0 w-full bg-white dark:bg-gray-900 shadow-xl py-4">
            <div class="flex flex-col space-y-4 px-6">
                <a href="#hero" class="py-2 text-lg text-gray-700 dark:text-gray-200 hover:text-amber-600 dark:hover:text-amber-400 mobile-nav-link">Home</a>
                <a href="#menu" class="py-2 text-lg text-gray-700 dark:text-gray-200 hover:text-amber-600 dark:hover:text-amber-400 mobile-nav-link">Menu</a>
                
                {{-- Logika Tautan Otentikasi Laravel (Mobile) --}}
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="mt-2 w-full px-5 py-3 bg-amber-600 text-white text-center rounded-lg font-medium hover:bg-amber-700 transition-colors shadow-sm">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="mt-2 w-full px-5 py-3 bg-amber-600 text-white text-center rounded-lg font-medium hover:bg-amber-700 transition-colors shadow-sm">
                            Login Staf
                        </a>

                        {{-- Menambahkan tautan Register (Mobile) jika rute 'register' ada --}}
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="py-2 text-lg text-gray-700 dark:text-gray-200 hover:text-amber-600 dark:hover:text-amber-400 mobile-nav-link">
                                Register
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </header>

    <!-- --- Konten Utama --- -->
    <main>
        <!-- --- Komponen 2: Hero Section --- -->
        <section id="hero" class="relative h-[70vh] md:h-[80vh] min-h-[500px] flex items-center justify-center text-white">
            <!-- Background Image -->
            <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://placehold.co/1920x1080/333/555?text=Foto+Restoran+Modern')"></div>
            <!-- Overlay Gelap -->
            <div class="absolute inset-0 bg-black/60"></div>

            <!-- Konten Teks -->
            <div class="relative z-10 text-center max-w-3xl px-4">
                <h1 class="text-4xl sm:text-5xl md:text-7xl font-extrabold mb-6 drop-shadow-lg leading-tight">
                    Seni Cita Rasa Autentik
                </h1>
                <p class="text-lg md:text-xl mb-10 text-gray-200 drop-shadow-md">
                    Rasakan pengalaman kuliner tak terlupakan dengan bahan-bahan segar dan suasana yang nyaman.
                </p>
                <div class="flex justify-center">
                    <a href="#menu" class="px-8 py-3 bg-amber-600 text-white font-semibold rounded-lg shadow-lg hover:bg-amber-700 transition transform hover:scale-105">
                        Lihat Menu Unggulan
                    </a>
                </div>
            </div>
        </section>

        <!-- --- Komponen 3: Menu Unggulan --- -->
        <section id="menu" class="py-20 sm:py-28 bg-gray-50 dark:bg-gray-950 overflow-hidden">
            <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center mb-12">
                    <h2 class="text-4xl font-bold text-gray-900 dark:text-white">
                        Menu Unggulan
                    </h2>
                    <!-- Tombol Navigasi Slider -->
                    <div class="hidden sm:flex space-x-2">
                        <button id="scroll-left-btn" class="p-2 rounded-full bg-white dark:bg-gray-800 shadow-md hover:bg-gray-100 dark:hover:bg-gray-700 transition text-gray-700 dark:text-gray-300" aria-label="Geser ke kiri">
                            <!-- Ikon ChevronLeft (Heroicons) -->
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                            </svg>
                        </button>
                        <button id="scroll-right-btn" class="p-2 rounded-full bg-white dark:bg-gray-800 shadow-md hover:bg-gray-100 dark:hover:bg-gray-700 transition text-gray-700 dark:text-gray-300" aria-label="Geser ke kanan">
                            <!-- Ikon ChevronRight (Heroicons) -->
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Container Slider -->
                <div id="slider-container" class="flex overflow-x-auto scroll-smooth py-4 -mb-4 space-x-8 scrollbar-hide">
                    
                    {{-- Ganti bagian ini dengan loop Blade dari data menu Anda --}}
                    {{-- @foreach ($featuredMenuItems as $item) --}}
                    <!-- Item 1 (Contoh) -->
                    <div class="flex-shrink-0 w-[300px]">
                        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg overflow-hidden transition duration-300 hover:shadow-2xl hover:-translate-y-2 h-full flex flex-col">
                            {{-- Ganti src dengan: {{ Storage::url($item->image) }} --}}
                            {{-- Pastikan Anda menjalankan `php artisan storage:link` --}}
                            <img src="{{ asset('storage/menu/nasi_goreng.png') }}" alt="Nasi Goreng" class="w-full h-56 object-cover flex-shrink-0" onerror="this.onerror=null;this.src='https://placehold.co/600x400/a16207/white?text=Nasi+Goreng';">
                            <div class="p-6 flex flex-col flex-grow">
                                <h3 class="text-2xl font-semibold mb-2 text-gray-900 dark:text-white">Nasi Goreng Spesial</h3>
                                <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 flex-grow">Nasi goreng spesial dengan telur, ayam, dan sayuran segar.</p>
                                <div class="text-2xl font-bold text-amber-600 dark:text-amber-400 mt-auto">Rp 50.000</div>
                            </div>
                        </div>
                    </div>
                    <!-- Item 2 (Contoh) -->
                    <div class="flex-shrink-0 w-[300px]">
                        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg overflow-hidden transition duration-300 hover:shadow-2xl hover:-translate-y-2 h-full flex flex-col">
                            <img src="{{ asset('storage/menu/sate_ayam.png') }}" alt="Sate Ayam" class="w-full h-56 object-cover flex-shrink-0" onerror="this.onerror=null;this.src='https://placehold.co/600x400/a16207/white?text=Sate+Ayam';">
                            <div class="p-6 flex flex-col flex-grow">
                                <h3 class="text-2xl font-semibold mb-2 text-gray-900 dark:text-white">Sate Ayam Madura</h3>
                                <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 flex-grow">Sate ayam bumbu kacang dengan lontong dan acar segar.</p>
                                <div class="text-2xl font-bold text-amber-600 dark:text-amber-400 mt-auto">Rp 60.000</div>
                            </div>
                        </div>
                    </div>
                    <!-- Item 3 (Contoh) -->
                    <div class="flex-shrink-0 w-[300px]">
                        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg overflow-hidden transition duration-300 hover:shadow-2xl hover:-translate-y-2 h-full flex flex-col">
                            <img src="{{ asset('storage/menu/ikan_bakar.png') }}" alt="Ikan Bakar" class="w-full h-56 object-cover flex-shrink-0" onerror="this.onerror=null;this.src='https://placehold.co/600x400/a16207/white?text=Ikan+Bakar';">
                            <div class="p-6 flex flex-col flex-grow">
                                <h3 class="text-2xl font-semibold mb-2 text-gray-900 dark:text-white">Ikan Bakar Jimbaran</h3>
                                <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 flex-grow">Ikan segar bakar dengan bumbu kecap dan sambal matah.</p>
                                <div class="text-2xl font-bold text-amber-600 dark:text-amber-400 mt-auto">Rp 75.000</div>
                            </div>
                        </div>
                    </div>
                    <!-- Item 4 (Contoh) -->
                    <div class="flex-shrink-0 w-[300px]">
                        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg overflow-hidden transition duration-300 hover:shadow-2xl hover:-translate-y-2 h-full flex flex-col">
                            <img src="{{ asset('storage/menu/kopi.png') }}" alt="Kopi" class="w-full h-56 object-cover flex-shrink-0" onerror="this.onerror=null;this.src='https://placehold.co/600x400/a16207/white?text=Kopi';">
                            <div class="p-6 flex flex-col flex-grow">
                                <h3 class="text-2xl font-semibold mb-2 text-gray-900 dark:text-white">Kopi Gayo</h3>
                                <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 flex-grow">Kopi hitam premium asli Gayo, disajikan panas atau dingin.</p>
                                <div class="text-2xl font-bold text-amber-600 dark:text-amber-400 mt-auto">Rp 18.000</div>
                            </div>
                        </div>
                    </div>
                    <!-- Item 5 (Contoh) -->
                    <div class="flex-shrink-0 w-[300px]">
                        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg overflow-hidden transition duration-300 hover:shadow-2xl hover:-translate-y-2 h-full flex flex-col">
                            <img src="{{ asset('storage/menu/capcay.png') }}" alt="Capcay" class="w-full h-56 object-cover flex-shrink-0" onerror="this.onerror=null;this.src='https://placehold.co/600x400/a16207/white?text=Capcay';">
                            <div class="p-6 flex flex-col flex-grow">
                                <h3 class="text-2xl font-semibold mb-2 text-gray-900 dark:text-white">Capcay Seafood</h3>
                                <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 flex-grow">Tumisan sayur segar dengan udang, cumi, dan bakso ikan.</p>
                                <div class="text-2xl font-bold text-amber-600 dark:text-amber-400 mt-auto">Rp 45.000</div>
                            </div>
                        </div>
                    </div>
                    {{-- @endforeach --}}
                </div>

                <div class="text-center mt-16">
                    <a href="{{ route('menu-items.index') }}" class="px-8 py-3 border-2 border-amber-600 text-amber-600 font-semibold rounded-lg hover:bg-amber-600 hover:text-white transition-all duration-300">
                        Lihat Menu Lengkap
                    </a>
                </div>
            </div>
        </section>
    </main>

    <!-- --- Komponen 6: Footer --- -->
    <footer class="bg-gray-900 dark:bg-black text-gray-400 py-16">
        <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center text-center md:text-left">
                <div>
                    <div class="text-3xl font-bold text-amber-500 mb-2">
                        RestoMan
                    </div>
                    <p class="text-sm">&copy; {{ date('Y') }} RestoMan. Dibuat dengan Laravel & Tailwind CSS.</p>
                </div>
                <div class="flex space-x-6 mt-8 md:mt-0">
                    <a href="#" class="hover:text-white transition-colors" aria-label="Facebook">
                        <!-- Ikon Facebook (Custom) -->
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M22.675 0h-21.35C.602 0 0 .602 0 1.348v21.304C0 23.398.602 24 1.325 24H12.82v-9.294H9.692v-3.622h3.128V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12V24h5.713c.723 0 1.325-.602 1.325-1.348V1.348C24 .602 23.398 0 22.675 0z"/></svg>
                    </a>
                    <a href="#" class="hover:text-white transition-colors" aria-label="Instagram">
                        <!-- Ikon Instagram (Custom) -->
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.07 1.646.07 4.85s-.012 3.584-.07 4.85c-.148 3.225-1.664 4.771-4.919 4.919-1.266.058-1.646.07-4.85.07s-3.584-.012-4.85-.07c-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.646-.07-4.85s.012-3.584.07-4.85c.149-3.225 1.664-4.771 4.919-4.919 1.266-.057 1.646-.07 4.85-.07zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12s.014 3.667.072 4.947c.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24s3.667-.014 4.947-.072c4.358-.2 6.78-2.618 6.98-6.98C23.986 15.667 24 15.259 24 12s-.014-3.667-.072-4.947c-.2-4.358-2.618-6.78-6.98-6.98C15.667.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.88 1.44 1.44 0 000-2.88z"/></svg>
                    </a>
                    <a href="#" class="hover:text-white transition-colors" aria-label="Twitter">
                        <!-- Ikon Twitter (Custom) -->
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- --- JavaScript Internal --- -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- Fungsionalitas Menu Mobile ---
            const menuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            const iconMenu = document.getElementById('icon-menu');
            const iconX = document.getElementById('icon-x');
            const navLinks = document.querySelectorAll('.mobile-nav-link');

            if (menuButton) {
                // Toggle menu
                menuButton.addEventListener('click', () => {
                    mobileMenu.classList.toggle('hidden');
                    iconMenu.classList.toggle('hidden');
                    iconX.classList.toggle('hidden');
                });
            }

            // Tutup menu setelah klik link
            navLinks.forEach(link => {
                link.addEventListener('click', () => {
                    mobileMenu.classList.add('hidden');
                    iconMenu.classList.remove('hidden');
                    iconX.classList.add('hidden');
                });
            });

            // --- Fungsionalitas Slider ---
            const sliderContainer = document.getElementById('slider-container');
            const scrollLeftBtn = document.getElementById('scroll-left-btn');
            const scrollRightBtn = document.getElementById('scroll-right-btn');
            
            if (scrollLeftBtn && scrollRightBtn && sliderContainer) {
                const scrollAmount = 320; // Jarak geser (lebar card + gap)

                scrollLeftBtn.addEventListener('click', () => {
                    sliderContainer.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
                });

                scrollRightBtn.addEventListener('click', () => {
                    sliderContainer.scrollBy({ left: scrollAmount, behavior: 'smooth' });
                });
            }
        });
    </script>

</body>
</html>