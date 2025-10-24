<x-app-layout>
  <x-slot name="header">
    <div class="flex justify-between items-center">
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Edit Riwayat Poin') }}
      </h2>
      <a href="{{ route('customer.show', $loyaltyPoint->customer->user_id) }}"
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
              Informasi Transaksi
            </h3>
            <div class="space-y-3">
              <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Customer</label>
                <p class="text-gray-900 dark:text-gray-100">{{ $loyaltyPoint->customer->user->name }}</p>
              </div>
              <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Transaksi</label>
                <p class="text-gray-900 dark:text-gray-100">{{ $loyaltyPoint->created_at->format('d F Y H:i') }}</p>
              </div>
              @if($loyaltyPoint->order_id)
              <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Order ID</label>
                <p class="text-gray-900 dark:text-gray-100">#{{ $loyaltyPoint->order_id }}</p>
              </div>
              @endif
            </div>
          </div>

          <form action="{{ route('loyaltyPoint.update', $loyaltyPoint) }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="mb-6">
              <label for="points" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Jumlah Poin
              </label>
              <input type="number" id="points" name="points" value="{{ old('points', $loyaltyPoint->points) }}" required
                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" />
              <p class="mt-1 text-xs text-gray-500">Gunakan angka positif (+) untuk tambah atau negatif (−) untuk kurangi</p>
              @error('points')
              <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
              @enderror
            </div>

            <div class="mb-6">
              <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Tipe
              </label>
              <select id="type" name="type" required
                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                <optgroup label="Transaksi">
                  <option value="earn" {{ old('type', $loyaltyPoint->type)==='earn'?'selected':'' }}>Earn (Dapat dari Order)</option>
                  <option value="redeem" {{ old('type', $loyaltyPoint->type)==='redeem'?'selected':'' }}>Redeem (Tukar Poin)</option>
                </optgroup>
                <optgroup label="Tambah Poin">
                  <option value="bonus" {{ old('type', $loyaltyPoint->type)==='bonus'?'selected':'' }}>🎁 Bonus</option>
                  <option value="compensation" {{ old('type', $loyaltyPoint->type)==='compensation'?'selected':'' }}>🎯 Kompensasi</option>
                  <option value="loyalty_reward" {{ old('type', $loyaltyPoint->type)==='loyalty_reward'?'selected':'' }}>⭐ Loyalty Reward</option>
                  <option value="referral" {{ old('type', $loyaltyPoint->type)==='referral'?'selected':'' }}>👥 Referral</option>
                  <option value="promotion" {{ old('type', $loyaltyPoint->type)==='promotion'?'selected':'' }}>📢 Promosi</option>
                  <option value="cashback" {{ old('type', $loyaltyPoint->type)==='cashback'?'selected':'' }}>💸 Cashback</option>
                  <option value="refund" {{ old('type', $loyaltyPoint->type)==='refund'?'selected':'' }}>💰 Refund</option>
                </optgroup>
                <optgroup label="Kurangi Poin">
                  <option value="expired" {{ old('type', $loyaltyPoint->type)==='expired'?'selected':'' }}>⏰ Kadaluarsa</option>
                  <option value="penalty" {{ old('type', $loyaltyPoint->type)==='penalty'?'selected':'' }}>⚠️ Penalti</option>
                </optgroup>
                <optgroup label="Koreksi">
                  <option value="adjustment" {{ old('type', $loyaltyPoint->type)==='adjustment'?'selected':'' }}>⚙️ Adjustment</option>
                </optgroup>
              </select>
              @error('type')
              <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
              @enderror
            </div>

            <div class="mb-6">
              <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Keterangan (Opsional)
              </label>
              <textarea id="description" name="description" rows="3"
                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                placeholder="Catatan tambahan...">{{ old('description', $loyaltyPoint->description) }}</textarea>
              @error('description')
              <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
              @enderror
            </div>

            <div class="flex items-center gap-4">
              <button type="submit"
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-md">
                Simpan Perubahan
              </button>
              <a href="{{ route('customer.show', $loyaltyPoint->customer->user_id) }}"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-md">
                Batal
              </a>

              <form action="{{ route('loyaltyPoint.destroy', $loyaltyPoint) }}" method="POST" class="ms-auto"
                onsubmit="return confirm('Yakin ingin menghapus riwayat poin ini?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                  class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-md">
                  Hapus Riwayat
                </button>
              </form>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>