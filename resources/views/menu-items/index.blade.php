<x-app-layout>
  <x-slot name="header">
    <div class="flex justify-between items-center">
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Menu Items') }}
      </h2>
      @if(Auth::check() && in_array(Auth::user()->user_type, ['admin', 'staff']))
      <a href="{{ route('menu-items.create') }}"
        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-md transition">
        + Tambah Menu
      </a>
      @endif
    </div>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

      {{-- Filter & Search --}}
      <div class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <form method="GET" action="{{ route('menu-items.index') }}" class="flex flex-col md:flex-row gap-4">
          <div class="flex-1">
            <input type="text" name="search" placeholder="Cari menu..." value="{{ $search }}"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
          </div>
          <div class="w-full md:w-64">
            <select name="category"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
              <option value="">Semua Kategori</option>
              @foreach($categories as $cat)
              <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>
                {{ $cat->name }}
              </option>
              @endforeach
            </select>
          </div>
          <button type="submit"
            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md transition">
            Cari
          </button>
          @if($search || $categoryId)
          <a href="{{ route('menu-items.index') }}"
            class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold rounded-md transition text-center">
            Reset
          </a>
          @endif
        </form>
      </div>

      {{-- Grid Menu Items --}}
      @if($menuItems->count() > 0)
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($menuItems as $item)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
          {{-- Image --}}
          <div class="aspect-square bg-gray-200 dark:bg-gray-700 relative">
            @if($item->image)
            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}"
              class="w-full h-full object-cover">
            @else
            <div class="flex items-center justify-center h-full text-gray-400">
              <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                  d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                  clip-rule="evenodd" />
              </svg>
            </div>
            @endif

            {{-- Availability Badge --}}
            <div class="absolute top-2 right-2">
              @if($item->is_available)
              <span class="px-2 py-1 text-xs bg-green-500 text-white rounded-full">Available</span>
              @else
              <span class="px-2 py-1 text-xs bg-red-500 text-white rounded-full">Unavailable</span>
              @endif
            </div>
          </div>

          {{-- Content --}}
          <div class="p-4">
            <div class="mb-2">
              <span
                class="px-2 py-1 text-xs bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200 rounded-full">
                {{ $item->category->name }}
              </span>
            </div>

            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">
              {{ $item->name }}
            </h3>

            <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 mb-4">
              Rp {{ number_format($item->price, 0, ',', '.') }}
            </p>

            <div class="flex gap-2">
              <a href="{{ route('menu-items.show', $item) }}"
                class="flex-1 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-center rounded-md transition text-sm">
                Detail
              </a>

              @if(Auth::check() && in_array(Auth::user()->user_type, ['admin', 'staff']))
              <a href="{{ route('menu-items.edit', $item) }}"
                class="px-3 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-md transition text-sm">
                Edit
              </a>
              @endif
            </div>
          </div>
        </div>
        @endforeach
      </div>

      {{-- Pagination --}}
      <div class="mt-6">
        {{ $menuItems->links() }}
      </div>
      @else
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 text-center">
        <p class="text-gray-500 dark:text-gray-400">Tidak ada menu item ditemukan</p>
      </div>
      @endif

    </div>
  </div>
</x-app-layout>