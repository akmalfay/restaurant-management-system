<x-app-layout>
  <x-slot name="header">
    <div class="flex items-center justify-between">
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Reservasi Saya') }}
      </h2>
      <a href="{{ route('reservations.index') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md text-sm">
        Pesan Meja Baru
      </a>
    </div>
  </x-slot>

  <div class="py-8">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

      @if(session('status'))
      <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ session('status') }}
      </div>
      @endif

      <!-- Filter Buttons -->
      <div class="mb-6 flex gap-2 flex-wrap">
        <a href="{{ route('reservations.my', ['filter' => 'all']) }}"
          class="px-4 py-2 rounded-lg text-sm font-medium {{ $filter === 'all' ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200' }}">
          Semua
        </a>
        <a href="{{ route('reservations.my', ['filter' => 'upcoming']) }}"
          class="px-4 py-2 rounded-lg text-sm font-medium {{ $filter === 'upcoming' ? 'bg-amber-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200' }}">
          Akan Datang
        </a>
        <a href="{{ route('reservations.my', ['filter' => 'ongoing']) }}"
          class="px-4 py-2 rounded-lg text-sm font-medium {{ $filter === 'ongoing' ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200' }}">
          Sedang Berlangsung
        </a>
        <a href="{{ route('reservations.my', ['filter' => 'finished']) }}"
          class="px-4 py-2 rounded-lg text-sm font-medium {{ $filter === 'finished' ? 'bg-gray-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200' }}">
          Selesai
        </a>
      </div>

      <!-- Reservations List -->
      <div class="space-y-4">
        @forelse($reservations as $reservation)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
          <!-- Header -->
          <div class="p-6 border-b border-gray-200 dark:border-gray-700 {{ $reservation->status === 'upcoming' ? 'bg-amber-50 dark:bg-amber-900/20' : ($reservation->status === 'ongoing' ? 'bg-blue-50 dark:bg-blue-900/20' : 'bg-gray-50 dark:bg-gray-700/30') }}">
            <div class="flex items-start justify-between">
              <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                  <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                    {{ $reservation->table->name }}
                  </h3>
                  <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $reservation->status === 'upcoming' ? 'bg-amber-200 text-amber-800 dark:bg-amber-900/60 dark:text-amber-300' : ($reservation->status === 'ongoing' ? 'bg-blue-200 text-blue-800 dark:bg-blue-900/60 dark:text-blue-300' : 'bg-gray-300 text-gray-800 dark:bg-gray-600 dark:text-gray-200') }}">
                    {{ ucfirst($reservation->status) }}
                  </span>
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                  <p>📅 {{ \Carbon\Carbon::parse($reservation->reservation_date)->locale('id')->translatedFormat('l, d F Y') }}</p>
                  <p>🕐 {{ $reservation->start_time->format('H:i') }} - {{ $reservation->end_time->format('H:i') }}</p>
                  <p>👥 {{ $reservation->table->capacity }} kursi</p>
                </div>
              </div>
              <div class="text-right">
                <p class="text-lg font-semibold text-indigo-600 dark:text-indigo-400 mb-2">
                  {{ $reservation->orders->count() }} Pesanan
                </p>
                @if(in_array($reservation->status, ['upcoming', 'ongoing']))
                <form method="POST" action="{{ route('reservations.cancel', ['reservation' => $reservation->id]) }}" onsubmit="return confirm('Batalkan reservasi ini?')" class="inline">
                  @csrf @method('PATCH')
                  <button type="submit" class="px-3 py-1 rounded bg-red-500 hover:bg-red-600 text-white text-sm">
                    Batalkan
                  </button>
                </form>
                @endif
              </div>
            </div>
          </div>

          <!-- Orders Section -->
          @if($reservation->orders->count() > 0)
          <div class="p-6 bg-gray-50 dark:bg-gray-700/50">
            <h4 class="font-semibold text-gray-800 dark:text-gray-200 mb-4">Pesanan ({{ $reservation->orders->count() }})</h4>
            <div class="space-y-3">
              @foreach($reservation->orders as $order)
              <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                <div class="flex items-center justify-between mb-2">
                  <p class="font-semibold text-indigo-600 dark:text-indigo-400">#{{ $order->id }}</p>
                  <span class="text-xs px-2 py-1 rounded {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300' : ($order->status === 'paid' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300') }}">
                    {{ ucfirst($order->status) }}
                  </span>
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                  <p><strong>Subtotal:</strong> Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                </div>

                <!-- Items List -->
                @if($order->orderItems->count() > 0)
                <details class="cursor-pointer">
                  <summary class="text-sm text-indigo-600 dark:text-indigo-400 font-semibold hover:underline">
                    Lihat {{ $order->orderItems->count() }} Item
                  </summary>
                  <div class="mt-3 pl-4 border-l-2 border-gray-300 dark:border-gray-600 space-y-2">
                    @foreach($order->orderItems as $item)
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                      <p class="font-medium">{{ $item->menuItem->name ?? 'Menu Item' }}</p>
                      <p class="text-xs">{{ $item->quantity }}x @ Rp {{ number_format($item->price, 0, ',', '.') }} = Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                    </div>
                    @endforeach
                  </div>
                </details>
                @endif
              </div>
              @endforeach
            </div>
          </div>
          @else
          <div class="p-6 text-center text-gray-500 dark:text-gray-400">
            <p class="mb-3">📭 Belum ada pesanan</p>
            <a href="{{ route('menu-items.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline font-medium">
              Pesan Menu →
            </a>
          </div>
          @endif
        </div>
        @empty
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-12 text-center">
          <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          <p class="text-gray-600 dark:text-gray-400 text-lg mb-4">
            @if($filter === 'all')
              Belum ada reservasi
            @else
              Belum ada reservasi {{ $filter }}
            @endif
          </p>
          <a href="{{ route('reservations.index') }}" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg inline-block">
            Pesan Meja Sekarang
          </a>
        </div>
        @endforelse
      </div>

      <!-- Pagination -->
      @if($reservations->hasPages())
      <div class="mt-8">
        {{ $reservations->links() }}
      </div>
      @endif

    </div>
  </div>
</x-app-layout>
