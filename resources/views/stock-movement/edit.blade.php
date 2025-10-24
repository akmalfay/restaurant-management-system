<x-app-layout>
  <x-slot name="header">
    <div class="flex justify-between items-center">
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Edit Pergerakan Stok') }}
      </h2>
      <a href="{{ route('inventory.show', $stockMovement->inventory_id) }}"
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
          <div class="mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
              Informasi Pergerakan
            </h3>
            <div class="space-y-2">
              <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Item</label>
                <p class="text-gray-900 dark:text-gray-100">{{ $stockMovement->inventory->name }}</p>
              </div>
              <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal</label>
                <p class="text-gray-900 dark:text-gray-100">{{ $stockMovement->created_at->format('d F Y H:i') }}</p>
              </div>
            </div>
          </div>

          <form action="{{ route('stockMovement.update', $stockMovement) }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="mb-6">
              <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Tipe Pergerakan
              </label>
              <select id="type" name="type" required
                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                <option value="purchase" {{ old('type', $stockMovement->type)==='purchase'?'selected':'' }}>📦 Purchase</option>
                <option value="usage" {{ old('type', $stockMovement->type)==='usage'?'selected':'' }}>🍳 Usage</option>
                <option value="waste" {{ old('type', $stockMovement->type)==='waste'?'selected':'' }}>🗑️ Waste</option>
                <option value="adjustment" {{ old('type', $stockMovement->type)==='adjustment'?'selected':'' }}>⚙️ Adjustment</option>
              </select>
              @error('type')
              <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
              @enderror
            </div>

            <div class="mb-6">
              <label for="quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Jumlah
              </label>
              <input type="number" step="0.001" id="quantity" name="quantity" value="{{ old('quantity', $stockMovement->quantity) }}" required
                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" />
              <p class="mt-1 text-xs text-gray-500">
                Satuan: {{ $stockMovement->inventory->unit }}. Gunakan + untuk tambah, - untuk kurangi
              </p>
              @error('quantity')
              <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
              @enderror
            </div>

            <div class="flex items-center gap-4">
              <button type="submit"
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-md">
                Simpan Perubahan
              </button>
              <a href="{{ route('inventory.show', $stockMovement->inventory_id) }}"
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