<x-app-layout>
  <x-slot name="header">
    <div class="flex justify-between items-center">
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Edit Inventory') }}
      </h2>
      <a href="{{ route('inventory.show', $inventory) }}"
        class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
        ← Kembali
      </a>
    </div>
  </x-slot>

  <div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
      @if(session('error'))
      <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 rounded">
        {{ session('error') }}
      </div>
      @endif

      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
          <form action="{{ route('inventory.update', $inventory) }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
              <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                Informasi Item
              </h3>
              <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Stok Saat Ini</label>
                <p class="text-xl font-bold text-gray-900 dark:text-gray-100">
                  {{ number_format($inventory->stock, 2) }} {{ $inventory->unit }}
                </p>
              </div>
            </div>

            <div class="mb-6">
              <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Nama Item
              </label>
              <input type="text" id="name" name="name" value="{{ old('name', $inventory->name) }}" required
                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" />
              @error('name')
              <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
              @enderror
            </div>

            <div class="mb-6">
              <label for="min_stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Minimum Stok
              </label>
              <input type="number" step="0.001" id="min_stock" name="min_stock" value="{{ old('min_stock', $inventory->min_stock) }}" required
                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" />
              @error('min_stock')
              <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
              @enderror
            </div>

            <div class="mb-6">
              <label for="unit" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Satuan
              </label>
              <select id="unit" name="unit" required
                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                <option value="kg" {{ old('unit', $inventory->unit)==='kg'?'selected':'' }}>Kilogram (kg)</option>
                <option value="liter" {{ old('unit', $inventory->unit)==='liter'?'selected':'' }}>Liter (L)</option>
                <option value="pcs" {{ old('unit', $inventory->unit)==='pcs'?'selected':'' }}>Pieces (pcs)</option>
                <option value="pack" {{ old('unit', $inventory->unit)==='pack'?'selected':'' }}>Pack</option>
                <option value="bottle" {{ old('unit', $inventory->unit)==='bottle'?'selected':'' }}>Bottle</option>
              </select>
              @error('unit')
              <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
              @enderror
            </div>

            <div class="mb-6">
              <label for="cost_per_unit" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Harga per Unit (Opsional)
              </label>
              <input type="number" step="0.01" id="cost_per_unit" name="cost_per_unit" value="{{ old('cost_per_unit', $inventory->cost_per_unit) }}"
                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" />
              @error('cost_per_unit')
              <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
              @enderror
            </div>

            <div class="mb-6">
              <label for="expires_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Tanggal Expired (Opsional)
              </label>
              <input type="date" id="expires_at" name="expires_at"
                value="{{ old('expires_at', $inventory->expires_at?->format('Y-m-d')) }}"
                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" />
              @error('expires_at')
              <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
              @enderror
            </div>

            <div class="flex items-center gap-4">
              <button type="submit"
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-md">
                Simpan Perubahan
              </button>
              <a href="{{ route('inventory.show', $inventory) }}"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-md">
                Batal
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>