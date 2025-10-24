<x-app-layout>
  <x-slot name="header">
    <div class="flex justify-between items-center">
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Detail Inventory: {{ $inventory->name }}
      </h2>
      <a href="{{ route('inventory.index') }}"
        class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
        ← Kembali
      </a>
    </div>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

      {{-- Info Inventory --}}
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div>
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Stock</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
              {{ number_format($inventory->stock, 2) }} {{ $inventory->unit }}
            </p>
          </div>
          <div>
            <p class="text-sm text-gray-500 dark:text-gray-400">Min Stock</p>
            <p class="text-xl font-semibold text-gray-900 dark:text-gray-100">
              {{ number_format($inventory->min_stock, 2) }} {{ $inventory->unit }}
            </p>
          </div>
          <div>
            <p class="text-sm text-gray-500 dark:text-gray-400">Expires At</p>
            <p class="text-xl font-semibold {{ $inventory->isExpired() ? 'text-red-600' : ($inventory->isNearExpiry() ? 'text-yellow-600' : 'text-green-600') }}">
              {{ $inventory->expires_at?->format('d M Y') ?? '-' }}
            </p>
          </div>
          <div>
            <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
            @if($inventory->isLowStock())
            <span class="px-3 py-1 text-sm bg-red-100 text-red-800 rounded-full">Low Stock</span>
            @else
            <span class="px-3 py-1 text-sm bg-green-100 text-green-800 rounded-full">Good</span>
            @endif
          </div>
        </div>

        {{-- Action Buttons --}}
        <div class="mt-6 flex gap-3">
          <a href="{{ route('inventory.edit', $inventory) }}"
            class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold rounded-md transition">
            Edit Info
          </a>

          <button onclick="toggleModal('purchaseModal')"
            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-md transition">
            + Tambah Stock (Purchase)
          </button>

          <button onclick="toggleModal('usageModal')"
            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md transition">
            Gunakan Stock (Usage)
          </button>

          <button onclick="toggleModal('wasteModal')"
            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-md transition">
            Buang Stock (Waste)
          </button>

          <button onclick="toggleModal('adjustmentModal')"
            class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-md transition">
            Adjustment
          </button>
        </div>
      </div>

      {{-- Daftar Batch --}}
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Batch Stock</h3>

          @if($inventory->batches->count() > 0)
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
              <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Batch ID</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Quantity</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Expires At</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                </tr>
              </thead>
              <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($inventory->batches as $batch)
                <tr>
                  <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">#{{ $batch->id }}</td>
                  <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-gray-100">
                    {{ number_format($batch->quantity, 2) }} {{ $inventory->unit }}
                  </td>
                  <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                    {{ $batch->expires_at?->format('d M Y') ?? 'No expiry' }}
                  </td>
                  <td class="px-6 py-4">
                    @if($batch->expires_at && $batch->expires_at->isPast())
                    <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">Expired</span>
                    @elseif($batch->expires_at && $batch->expires_at->diffInDays(now()) <= 7)
                      <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full">Near Expiry</span>
                      @else
                      <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Good</span>
                      @endif
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          @else
          <p class="text-gray-500 dark:text-gray-400">Belum ada batch stock</p>
          @endif
        </div>
      </div>

      {{-- Riwayat Pergerakan --}}
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Riwayat Pergerakan Stock</h3>

          @if($inventory->stockMovements->count() > 0)
          <div class="space-y-2">
            @foreach($inventory->stockMovements as $movement)
            <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded">
              <div class="flex-1">
                <span class="px-2 py-1 text-xs rounded-full
                      @if($movement->type === 'purchase') bg-green-100 text-green-800
                      @elseif($movement->type === 'usage') bg-blue-100 text-blue-800
                      @elseif($movement->type === 'waste') bg-red-100 text-red-800
                      @else bg-gray-100 text-gray-800
                      @endif">
                  {{ ucfirst($movement->type) }}
                </span>
                <span class="ml-2 text-sm text-gray-600 dark:text-gray-300">
                  Batch #{{ $movement->batch_id }}
                </span>
                @if($movement->notes)
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $movement->notes }}</p>
                @endif
              </div>
              <div class="text-right">
                <p class="font-semibold {{ $movement->quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                  {{ $movement->quantity > 0 ? '+' : '' }}{{ number_format($movement->quantity, 2) }} {{ $inventory->unit }}
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                  {{ $movement->created_at->format('d M Y H:i') }}
                </p>
              </div>
            </div>
            @endforeach
          </div>
          @else
          <p class="text-gray-500 dark:text-gray-400">Belum ada riwayat pergerakan</p>
          @endif
        </div>
      </div>

    </div>
  </div>

  {{-- Modal Purchase --}}
  <div id="purchaseModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
      <div class="mt-3">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Tambah Stock (Purchase)</h3>
        <form action="{{ route('stock-movements.store', $inventory) }}" method="POST">
          @csrf
          <input type="hidden" name="type" value="purchase">

          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jumlah ({{ $inventory->unit }})</label>
            <input type="number" step="0.001" name="quantity" required
              class="w-full px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
          </div>

          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Expired</label>
            <input type="date" name="expires_at" required
              class="w-full px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
          </div>

          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Catatan (opsional)</label>
            <textarea name="notes" rows="2"
              class="w-full px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
          </div>

          <div class="flex gap-2">
            <button type="submit" class="flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md">
              Simpan
            </button>
            <button type="button" onclick="toggleModal('purchaseModal')"
              class="flex-1 px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-md">
              Batal
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- Modal Usage --}}
  <div id="usageModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
      <div class="mt-3">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Gunakan Stock (Usage)</h3>
        <form action="{{ route('stock-movements.store', $inventory) }}" method="POST">
          @csrf
          <input type="hidden" name="type" value="usage">

          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Jumlah ({{ $inventory->unit }})
              <span class="text-xs text-gray-500">Max: {{ number_format($inventory->stock, 2) }}</span>
            </label>
            <input type="number" step="0.001" name="quantity" required max="{{ $inventory->stock }}"
              class="w-full px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
          </div>

          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Catatan</label>
            <textarea name="notes" rows="2" placeholder="Untuk produksi menu apa?"
              class="w-full px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
          </div>

          <div class="flex gap-2">
            <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">
              Simpan
            </button>
            <button type="button" onclick="toggleModal('usageModal')"
              class="flex-1 px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-md">
              Batal
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- Modal Waste --}}
  <div id="wasteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
      <div class="mt-3">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Buang Stock (Waste)</h3>
        <form action="{{ route('stock-movements.store', $inventory) }}" method="POST">
          @csrf
          <input type="hidden" name="type" value="waste">

          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jumlah ({{ $inventory->unit }})</label>
            <input type="number" step="0.001" name="quantity" required max="{{ $inventory->stock }}"
              class="w-full px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
          </div>

          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Alasan</label>
            <textarea name="notes" rows="2" required placeholder="Expired, rusak, dll"
              class="w-full px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
          </div>

          <div class="flex gap-2">
            <button type="submit" class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md">
              Simpan
            </button>
            <button type="button" onclick="toggleModal('wasteModal')"
              class="flex-1 px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-md">
              Batal
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- Modal Adjustment --}}
  <div id="adjustmentModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
      <div class="mt-3">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Adjustment Stock</h3>
        <form action="{{ route('stock-movements.store', $inventory) }}" method="POST">
          @csrf
          <input type="hidden" name="type" value="adjustment">

          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Jumlah ({{ $inventory->unit }})
              <span class="text-xs text-gray-500">Gunakan + untuk tambah, - untuk kurang</span>
            </label>
            <input type="number" step="0.001" name="quantity" required
              class="w-full px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"
              placeholder="contoh: +5 atau -3">
          </div>

          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Alasan</label>
            <textarea name="notes" rows="2" required placeholder="Stock opname, koreksi, dll"
              class="w-full px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
          </div>

          <div class="flex gap-2">
            <button type="submit" class="flex-1 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-md">
              Simpan
            </button>
            <button type="button" onclick="toggleModal('adjustmentModal')"
              class="flex-1 px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-md">
              Batal
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    function toggleModal(modalId) {
      const modal = document.getElementById(modalId);
      modal.classList.toggle('hidden');
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
      const modals = ['purchaseModal', 'usageModal', 'wasteModal', 'adjustmentModal'];
      modals.forEach(id => {
        const modal = document.getElementById(id);
        if (event.target == modal) {
          modal.classList.add('hidden');
        }
      });
    }
  </script>
</x-app-layout>