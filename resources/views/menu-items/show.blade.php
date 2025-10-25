{{-- filepath: c:\Workspace\pemrograman-web\resources\views\menu-items\show.blade.php --}}
<x-app-layout>
  <x-slot name="header">
    <div class="flex justify-between items-center">
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Detail Menu: {{ $menuItem->name }}
      </h2>
      <a href="{{ route('menu-items.index') }}"
        class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
        ← Kembali ke Menu
      </a>
    </div>
  </x-slot>

  <div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
          <div class="grid md:grid-cols-2 gap-6">

            {{-- Image Section --}}
            <div>
              <div class="aspect-square bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden">
                @if($menuItem->image)
                <img src="{{ asset('storage/' . $menuItem->image) }}" alt="{{ $menuItem->name }}"
                  class="w-full h-full object-cover">
                @else
                <div class="flex items-center justify-center h-full text-gray-400">
                  <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                      d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                      clip-rule="evenodd" />
                  </svg>
                </div>
                @endif
              </div>
            </div>

            {{-- Info Section --}}
            <div class="space-y-6">
              {{-- Category Badge --}}
              <div>
                <span class="px-3 py-1 text-sm bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200 rounded-full">
                  {{ $menuItem->category->name }}
                </span>
              </div>

              {{-- Name --}}
              <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                  {{ $menuItem->name }}
                </h1>
              </div>

              {{-- Description --}}
              <div>
                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-2">Deskripsi</h3>
                <p class="text-gray-700 dark:text-gray-300">
                  {{ $menuItem->description ?? 'Tidak ada deskripsi' }}
                </p>
              </div>

              {{-- Price --}}
              <div>
                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-2">Harga</h3>
                <p class="text-4xl font-bold text-indigo-600 dark:text-indigo-400">
                  Rp {{ number_format($menuItem->price, 0, ',', '.') }}
                </p>
              </div>

              {{-- Availability --}}
              <div>
                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-2">Status</h3>
                @if($menuItem->is_available)
                <span class="px-4 py-2 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 rounded-full font-semibold">
                  ✓ Tersedia
                </span>
                @else
                <span class="px-4 py-2 bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 rounded-full font-semibold">
                  ✗ Tidak Tersedia
                </span>
                @endif
              </div>

              {{-- Action Buttons (Admin/Staff only) --}}
              @if(Auth::check() && in_array(Auth::user()->user_type, ['admin', 'staff']))
              <div class="flex gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('menu-items.edit', $menuItem) }}"
                  class="flex-1 px-4 py-3 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold rounded-md transition text-center">
                  Edit Menu
                </a>
                <form action="{{ route('menu-items.destroy', $menuItem) }}" method="POST"
                  onsubmit="return confirm('Yakin ingin menghapus menu ini?')" class="flex-1">
                  @csrf
                  @method('DELETE')
                  <button type="submit"
                    class="w-full px-4 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-md transition">
                    Hapus Menu
                  </button>
                </form>
              </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>