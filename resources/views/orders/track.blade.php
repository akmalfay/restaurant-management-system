<x-app-layout>
  <x-slot name="header">
    <div class="flex items-center justify-between">
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Lacak Pesanan Saya</h2>
      <form method="GET" action="{{ route('orders.track') }}" class="flex items-center gap-2">
        <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Cari ID order..." class="rounded-md border-gray-300 px-3 py-2" />
        <button class="px-3 py-2 bg-indigo-600 text-white rounded">Cari</button>
      </form>
    </div>
  </x-slot>

  <div class="py-8">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
      @if(session('success'))
        <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded">{{ session('success') }}</div>
      @endif

      @if($orders->count() === 0)
        <div class="bg-white p-8 rounded shadow text-center">
          <h3 class="text-lg font-medium">Belum ada pesanan untuk dilacak</h3>
          <p class="text-sm text-gray-500 mt-2">Pesanan takeaway atau delivery akan muncul di sini.</p>
          <div class="mt-4">
            <a href="{{ route('menu-items.index') }}" class="inline-block px-4 py-2 bg-indigo-600 text-white rounded">Lihat Menu</a>
          </div>
        </div>
      @else
        <div class="space-y-4">
          @foreach($orders as $order)
            <div class="bg-white rounded shadow p-4 flex flex-col md:flex-row md:items-center md:justify-between">
              <div>
                <div class="flex items-center gap-3">
                  <div class="text-sm text-gray-500">Order #{{ $order->id }}</div>
                  @php
                    $statusColor = match($order->status) {
                      'pending' => '#FEF3C7',
                      'preparing' => '#E9D5FF',
                      'ready' => '#D1FAE5',
                      default => '#FEE2E2'
                    };
                  @endphp
                  <div class="px-3 py-1 rounded-full text-sm font-medium" :style="{ backgroundColor: '{{ $statusColor }}', color: '#111' }">
                    {{ ucfirst($order->status) }}
                  </div>
                  <div class="text-sm text-gray-400">• {{ $order->type }}</div>
                </div>
                <div class="text-sm text-gray-600 mt-2">
                  Dibuat: {{ optional($order->created_at)->format('d M Y H:i') }}
                </div>

                <div class="mt-3 text-sm text-gray-700">
                  @foreach($order->orderItems as $it)
                    <div class="flex items-center justify-between py-1">
                      <div class="truncate">{{ $it->menuItem->name ?? 'Item' }} x {{ $it->quantity }}</div>
                      <div class="font-medium">Rp {{ number_format($it->price * $it->quantity,0,',','.') }}</div>
                    </div>
                  @endforeach
                </div>
              </div>

              <div class="mt-3 md:mt-0 md:text-right">
                <div class="text-sm text-gray-500">Total</div>
                <div class="text-lg font-semibold">Rp {{ number_format($order->total,0,',','.') }}</div>
                <div class="mt-3 flex gap-2 justify-end">
                  <a href="{{ route('orders.track.show', $order) }}" class="px-3 py-2 bg-gray-100 rounded">Detail</a>
                  @if(in_array($order->type, ['takeaway','delivery']) && $order->status === 'ready' && ! $order->completed_at)
                    <form action="{{ route('orders.track.complete', $order) }}" method="POST" class="inline">
                      @csrf
                      <button type="submit" class="px-3 py-2 bg-green-600 text-white rounded" onclick="return confirm('Tandai pesanan ini selesai?')">Selesai</button>
                    </form>
                  @endif
                </div>
              </div>
            </div>
          @endforeach

          <div class="mt-4">{{ $orders->links() }}</div>
        </div>
      @endif
    </div>
  </div>
</x-app-layout>