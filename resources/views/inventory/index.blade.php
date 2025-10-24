<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Inventory Management') }}
    </h2>
  </x-slot>

  @php
  $openAdd = request()->boolean('add') || $errors->any();
  @endphp

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      @if(session('success'))
      <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 rounded">
        {{ session('success') }}
      </div>
      @endif

      @if(session('error'))
      <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 rounded">
        {{ session('error') }}
      </div>
      @endif

      <!-- ALERT BANNER -->
      @php
      $expiredItems = $inventories->filter(fn($item) => $item->isExpired());
      $nearExpiryItems = $inventories->filter(fn($item) => $item->isNearExpiry());
      @endphp

      @if($expiredItems->count() > 0)
      <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 border-l-4 border-red-600 dark:border-red-400 text-red-700 dark:text-red-300 rounded">
        <div class="flex items-center">
          <span class="text-2xl mr-3">🔴</span>
          <div>
            <strong class="font-bold">KADALUARSA!</strong>
            <p class="text-sm">{{ $expiredItems->count() }} item sudah melewati tanggal kadaluarsa</p>
          </div>
        </div>
      </div>
      @endif

      @if($nearExpiryItems->count() > 0)
      <div class="mb-4 p-4 bg-yellow-100 dark:bg-yellow-900 border-l-4 border-yellow-600 dark:border-yellow-400 text-yellow-700 dark:text-yellow-300 rounded">
        <div class="flex items-center">
          <span class="text-2xl mr-3">⚠️</span>
          <div>
            <strong class="font-bold">PERINGATAN!</strong>
            <p class="text-sm">{{ $nearExpiryItems->count() }} item akan kadaluarsa dalam 7 hari</p>
          </div>
        </div>
      </div>
      @endif

      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
          <!-- HEADER: Search & Add Button -->
          <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <!-- Search Form -->
            <form method="GET" action="{{ route('inventory.index') }}" class="flex-1 max-w-md">
              <div class="relative flex gap-2">
                <div class="relative flex-1">
                  <input type="text" name="search" value="{{ $search ?? '' }}"
                    placeholder="Cari nama item..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                  </div>
                </div>

                <!-- Tombol Search -->
                <button type="submit"
                  class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition inline-flex items-center">
                  <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                  </svg>
                  Cari
                </button>

                @if($search)
                <!-- Tombol Clear/Reset -->
                <a href="{{ route('inventory.index') }}"
                  class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-lg transition inline-flex items-center">
                  <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                  Reset
                </a>
                @endif
              </div>
            </form>

            <!-- Add Button -->
            <a href="{{ route('inventory.index', ['add' => 1]) }}"
              class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              Tambah Item
            </a>
          </div>

          <!-- Search Result Info -->
          @if($search)
          <div class="mb-4 px-4 py-2 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
            <p class="text-sm text-blue-700 dark:text-blue-300">
              Menampilkan hasil pencarian untuk: <strong>"{{ $search }}"</strong>
              <span class="ml-2 text-blue-600 dark:text-blue-400">({{ $inventories->total() }} item ditemukan)</span>
            </p>
          </div>
          @endif

          <!-- Table -->
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
              <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                    Nama Item
                  </th>
                  <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                    Stok
                  </th>
                  <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                    Min. Stok
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                    Status
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                    Expired Terdekat
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                    Aksi
                  </th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($inventories as $item)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                      {{ $item->name }}
                    </div>
                    @if($item->cost_per_unit)
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                      Rp {{ number_format($item->cost_per_unit, 0, ',', '.') }}/{{ $item->unit }}
                    </div>
                    @endif
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                    <span class="font-semibold {{ $item->isLowStock() ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-gray-100' }}">
                      {{ number_format($item->total_stock, 2) }} {{ $item->unit }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500 dark:text-gray-400">
                    {{ number_format($item->min_stock, 2) }} {{ $item->unit }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    @if($item->isLowStock())
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                      Low Stock
                    </span>
                    @else
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                      Normal
                    </span>
                    @endif
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm">
                    @if($item->next_expiry)
                    @php
                    $expiryDate = \Carbon\Carbon::parse($item->next_expiry);
                    @endphp
                    <span class="font-medium
      @if($item->isExpired())
        text-red-600 dark:text-red-400 font-bold
      @elseif($item->isNearExpiry())
        text-yellow-600 dark:text-yellow-400 font-semibold
      @elseif($item->isSafe())
        text-green-600 dark:text-green-400
      @else
        text-gray-900 dark:text-gray-100
      @endif">
                      {{ $expiryDate->format('d M Y') }}

                      @if($item->isExpired())
                      @php
                      $daysExpired = (int) abs(now()->diffInDays($expiryDate, false));
                      @endphp
                      <span class="ml-1 px-2 py-0.5 text-xs font-bold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                        LEWAT {{ $daysExpired }} HARI
                      </span>
                      @elseif($item->isNearExpiry())
                      @php
                      $daysLeft = (int) now()->diffInDays($expiryDate, false);
                      @endphp
                      <span class="ml-1 px-2 py-0.5 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                        {{ $daysLeft }} HARI LAGI
                      </span>
                      @endif
                    </span>
                    @else
                    <span class="text-gray-400 dark:text-gray-600">Tidak ada exp</span>
                    @endif
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex items-center gap-3">
                      <a href="{{ route('inventory.show', $item) }}"
                        class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                        Detail
                      </a>

                      <a href="{{ route('inventory.edit', $item) }}"
                        class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300">
                        Edit
                      </a>

                      <form action="{{ route('inventory.destroy', $item) }}" method="POST" class="inline-block"
                        onsubmit="return confirm('Yakin ingin menghapus {{ $item->name }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                          class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                          Hapus
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                    Belum ada data inventory
                  </td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <div class="mt-4">
            {{ $inventories->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>