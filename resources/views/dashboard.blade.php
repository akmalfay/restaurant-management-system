<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-[#0F3D3E] leading-tight">
            Dashboard Overview
        </h2>
        <p class="text-sm text-gray-600">Welcome back! Here's what's happening today.</p>
    </x-slot>

    {{-- Konten Dashboard Baru --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        {{-- Kartu KPI 1 --}}
        <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-lg flex items-center space-x-4">
            <div class="shrink-0 p-3 bg-amber-100 dark:bg-amber-900/50 rounded-lg">
                <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
            </div>
            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Reservasi Hari Ini</h4>
                {{-- Ganti '12' dengan data asli dari controller --}}
                <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1">12</p>
            </div>
        </div>
        
        {{-- Kartu KPI 2 --}}
        <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-lg flex items-center space-x-4">
            <div class="shrink-0 p-3 bg-red-100 dark:bg-red-900/50 rounded-lg">
                <svg class="w-6 h-6 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z" /></svg>
            </div>
            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Stok Kritis</h4>
                {{-- Ganti '3' dengan data asli dari controller --}}
                <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1">3 <span class="text-lg font-medium">item</span></p>
            </div>
        </div>

        {{-- Kartu KPI 3 --}}
        <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-lg flex items-center space-x-4">
            <div class="shrink-0 p-3 bg-blue-100 dark:bg-blue-900/50 rounded-lg">
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM10.5 16.5h-3" /></svg>
            </div>
            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Pesanan Aktif</h4>
                {{-- Ganti '8' dengan data asli dari controller --}}
                <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1">8</p>
            </div>
        </div>

        {{-- Kartu KPI 4 --}}
        <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-lg flex items-center space-x-4">
            <div class="shrink-0 p-3 bg-green-100 dark:bg-green-900/50 rounded-lg">
                <svg class="w-6 h-6 text-green-600 dark:text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0l.879-.659M12 2.25A.75.75 0 0112.75 3v.342a.75.75 0 01-.63.746H11.88a.75.75 0 01-.63-.746V3A.75.75 0 0112 2.25z" /></svg>
            </div>
            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Pendapatan Hari Ini</h4>
                {{-- Ganti 'Rp 2.5jt' dengan data asli dari controller --}}
                <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1">Rp 2.5jt</p>
            </div>
        </div>
    </div>

    {{-- Kolom Utama --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Kolom 1: Reservasi Akan Datang -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-900 p-6 rounded-xl shadow-lg">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Reservasi Akan Datang</h3>
            <div class="space-y-4">
                {{-- Ganti ini dengan loop @foreach dari data reservasi --}}
                
                <!-- Contoh Reservasi 1 -->
                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <div class="flex items-center gap-4">
                        <span class="text-xl font-bold text-amber-600 dark:text-amber-400">18:00</span>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-gray-100">Andi Budianto</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Meja: VIP-01 (4 orang)</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                        Confirmed
                    </span>
                </div>
                <!-- Contoh Reservasi 2 -->
                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <div class="flex items-center gap-4">
                        <span class="text-xl font-bold text-amber-600 dark:text-amber-400">19:30</span>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-gray-100">Citra Lestari</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Meja: Indoor-02 (2 orang)</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                        Confirmed
                    </span>
                </div>
                <!-- Contoh Reservasi 3 (Pending) -->
                <div class="flex items-center justify-between p-4 bg-yellow-50 dark:bg-yellow-900/50 rounded-lg">
                    <div class="flex items-center gap-4">
                        <span class="text-xl font-bold text-yellow-600 dark:text-yellow-400">20:00</span>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-gray-100">David Santoso</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Meja: Teras-03 (6 orang)</p>
                        </div>
                    </div>
                    <a href="#" class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-600 text-white hover:bg-yellow-700">
                        Konfirmasi
                    </a>
                </div>
            </div>
        </div>

        <!-- Kolom 2: Stok Kritis -->
        <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-lg">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Stok Kritis</h3>
            <div class="space-y-4">
                {{-- Ganti ini dengan loop @foreach dari data inventaris --}}
                
                <!-- Contoh Item Stok Kritis 1 -->
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-semibold text-gray-900 dark:text-gray-100">Daging Sapi</p>
                        <p class="text-sm text-red-600 dark:text-red-400 font-medium">Sisa: 8.5 kg <span class="text-gray-500">(Min: 10 kg)</span></p>
                    </div>
                    <a href="#" class="px-3 py-1 text-xs font-semibold rounded-full bg-red-600 text-white hover:bg-red-700">
                        Restock
                    </a>
                </div>
                <!-- Contoh Item Stok Kritis 2 -->
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-semibold text-gray-900 dark:text-gray-100">Susu Segar</p>
                        <p class="text-sm text-red-600 dark:text-red-400 font-medium">Sisa: 12.0 L <span class="text-gray-500">(Min: 15 L)</span></p>
                    </div>
                    <a href="#" class="px-3 py-1 text-xs font-semibold rounded-full bg-red-600 text-white hover:bg-red-700">
                        Restock
                    </a>
                </div>
                <!-- Contoh Item Stok Hampir Habis -->
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-semibold text-gray-900 dark:text-gray-100">Wortel</p>
                        <p class="text-sm text-yellow-600 dark:text-yellow-400 font-medium">Sisa: 8.2 kg <span class="text-gray-500">(Min: 8 kg)</span></p>
                    </div>
                    <a href="#" class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-600 text-white hover:bg-yellow-700">
                        Restock
                    </a>
                </div>
                <p class="text-gray-600 text-sm mt-1">
                    Everything looks good. Continue managing your restaurant system.
                </p>
            </div>
        </div>

    </div>
</x-app-layout>
