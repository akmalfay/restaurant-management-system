<x-app-layout>
  <x-slot name="header">
    <div class="flex justify-between items-center">
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Detail Customer') }}
      </h2>
      <a href="{{ route('customer.index') }}"
        class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
        ← Kembali
      </a>
    </div>
  </x-slot>

  @php
  $openAdjust = request()->boolean('adjust') || $errors->any();
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

      <!-- Customer Info -->
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6">
          <div class="flex items-start gap-6">
            <div class="flex-shrink-0">
              @php
              $defaultAvatar = 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=96&background=6366f1&color=fff';
              @endphp
              <div class="h-24 w-24 overflow-hidden rounded-full border-4 border-gray-200 dark:border-gray-700">
                <img class="h-full w-full object-cover"
                  src="{{ $user->image ? asset('storage/' . $user->image) : $defaultAvatar }}"
                  onerror="this.onerror=null; this.src='{{ $defaultAvatar }}';"
                  alt="{{ $user->name }}">
              </div>
            </div>

            <div class="flex-1">
              <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                {{ $user->name }}
              </h3>

              <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</label>
                <p class="text-gray-600 dark:text-gray-400 mt-1">
                  {{ $user->email }}
                </p>
              </div>

              <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Telepon</label>
                <p class="text-gray-600 dark:text-gray-400 mt-1">
                  {{ $user->phone ?? '-' }}
                </p>
              </div>

              <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Alamat</label>
                <p class="text-gray-600 dark:text-gray-400 mt-1">
                  {{ $user->address ?? '-' }}
                </p>
              </div>
              <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Poin</label>
                  <p class="mt-1 text-2xl font-bold text-yellow-600 dark:text-yellow-400">
                    {{ number_format($user->customerDetail?->points ?? 0) }} pts
                  </p>
                </div>

                <div>
                  <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Member Sejak</label>
                  <p class="mt-1 text-gray-900 dark:text-gray-100">
                    {{ $user->created_at->format('d F Y') }}
                  </p>
                </div>
              </div>

              <!-- Admin Actions -->
              <div class="mt-6 flex gap-3">
                <a href="{{ route('customer.edit', $user) }}"
                  class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold rounded-md">
                  Edit Customer
                </a>

                <a href="{{ route('customer.show', array_merge(['user' => $user], ['adjust' => 1])) }}"
                  class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-md">
                  Sesuaikan Poin
                </a>

                <form action="{{ route('customer.destroy', $user) }}" method="POST"
                  onsubmit="return confirm('Yakin ingin menghapus customer {{ $user->name }}?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-md">
                    Hapus Customer
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal Adjust Points -->
      @if($openAdjust)
      <div class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/50"></div>

        <div class="relative bg-white dark:bg-gray-800 w-full max-w-md mx-4 rounded-lg shadow-xl">
          <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h4 class="text-lg font-semibold">Sesuaikan Poin</h4>
            <a href="{{ route('customer.show', $user) }}"
              class="p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </a>
          </div>

          <div class="p-6">
            @if ($errors->any())
            <div class="mb-4 p-3 rounded bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-200">
              <ul class="list-disc ms-5">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
            @endif

            <form action="{{ route('customer.adjustPoints', $user) }}" method="POST" class="space-y-4">
              @csrf

              <div>
                <label class="block text-sm font-medium mb-1">Jumlah Poin</label>
                <input type="number" name="points" value="{{ old('points') }}" required
                  placeholder="Gunakan + untuk tambah, - untuk kurangi"
                  class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200" />
                <p class="mt-1 text-xs text-gray-500">Contoh: 100 (tambah) atau -50 (kurangi)</p>
              </div>

              <div>
                <label class="block text-sm font-medium mb-1">Tipe</label>
                <select name="type" required
                  class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
                  <optgroup label="Tambah Poin">
                    <option value="bonus" {{ old('type')==='bonus'?'selected':'' }}>🎁 Bonus (Ulang Tahun, Event)</option>
                    <option value="compensation" {{ old('type')==='compensation'?'selected':'' }}>🎯 Kompensasi (Ketidaknyamanan)</option>
                    <option value="loyalty_reward" {{ old('type')==='loyalty_reward'?'selected':'' }}>⭐ Loyalty Reward</option>
                    <option value="referral" {{ old('type')==='referral'?'selected':'' }}>👥 Referral Bonus</option>
                    <option value="promotion" {{ old('type')==='promotion'?'selected':'' }}>📢 Promosi Khusus</option>
                    <option value="cashback" {{ old('type')==='cashback'?'selected':'' }}>💸 Cashback</option>
                    <option value="refund" {{ old('type')==='refund'?'selected':'' }}>💰 Refund (Pengembalian)</option>
                  </optgroup>
                  <optgroup label="Kurangi Poin">
                    <option value="expired" {{ old('type')==='expired'?'selected':'' }}>⏰ Poin Kadaluarsa</option>
                    <option value="penalty" {{ old('type')==='penalty'?'selected':'' }}>⚠️ Penalti</option>
                  </optgroup>
                  <optgroup label="Koreksi">
                    <option value="adjustment" {{ old('type')==='adjustment'?'selected':'' }}>⚙️ Adjustment (Koreksi Manual)</option>
                  </optgroup>
                </select>
              </div>

              <div>
                <label class="block text-sm font-medium mb-1">Keterangan</label>
                <textarea name="description" rows="3" required
                  class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200"
                  placeholder="Misal: Bonus ulang tahun">{{ old('description') }}</textarea>
              </div>

              <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                  class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-md">
                  Simpan
                </button>
                <a href="{{ route('customer.show', $user) }}"
                  class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-semibold rounded-md">
                  Batal
                </a>
              </div>
            </form>
          </div>
        </div>
      </div>
      @endif

      <!-- Loyalty Points History -->
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
          <h4 class="text-lg font-semibold mb-4">Riwayat Transaksi Poin</h4>

          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
              <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                    Tanggal
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                    Tipe
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                    Keterangan
                  </th>
                  <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                    Poin
                  </th>
                  <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                    Aksi
                  </th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($user->customerDetail?->loyaltyPoints ?? [] as $point)
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                    {{ $point->created_at->format('d M Y H:i') }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 text-xs font-semibold rounded-full
    @if($point->type === 'earn') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
    @elseif($point->type === 'redeem') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
    @elseif($point->type === 'bonus') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
    @elseif($point->type === 'refund') bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200
    @elseif($point->type === 'compensation') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
    @elseif($point->type === 'loyalty_reward') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
    @elseif($point->type === 'referral') bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-200
    @elseif($point->type === 'promotion') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
    @elseif($point->type === 'cashback') bg-teal-100 text-teal-800 dark:bg-teal-900 dark:text-teal-200
    @elseif($point->type === 'expired') bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
    @elseif($point->type === 'penalty') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
    @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
    @endif">
                      {{ ucfirst(str_replace('_', ' ', $point->type)) }}
                    </span>
                  </td>
                  <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                    {{ $point->description ?? ($point->order_id ? 'Order #' . $point->order_id : '-') }}
                  </td>
                  @php
                  $type = $point->type;
                  $raw = (int) $point->points;
                  $negativeTypes = ['redeem','expired','penalty'];
                  $positiveTypes = ['earn','bonus','refund','compensation','loyalty_reward','referral','promotion','cashback'];

                  if (in_array($type, $negativeTypes)) {
                  $sign = '-';
                  $cls = 'text-red-600 dark:text-red-400';
                  $amount = number_format(abs($raw));
                  } elseif (in_array($type, $positiveTypes)) {
                  $sign = '+';
                  $cls = 'text-green-600 dark:text-green-400';
                  $amount = number_format(abs($raw));
                  } else { // adjustment atau lainnya
                  $sign = $raw >= 0 ? '+' : '-';
                  $cls = $raw >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400';
                  $amount = number_format(abs($raw));
                  }
                  @endphp

                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold {{ $cls }}">
                    {{ $sign }}{{ $amount }}
                  </td>

                  <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                    <div class="flex items-center justify-center gap-3">
                      <a href="{{ route('loyaltyPoint.edit', $point) }}"
                        class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300">
                        Edit
                      </a>

                      <form action="{{ route('loyaltyPoint.destroy', $point) }}" method="POST" class="inline-block"
                        onsubmit="return confirm('Yakin ingin menghapus riwayat poin ini?')">
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
                  <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                    Belum ada transaksi poin
                  </td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>