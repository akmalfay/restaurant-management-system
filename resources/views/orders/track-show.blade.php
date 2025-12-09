<x-app-layout>
  <x-slot name="header">
    <div class="flex items-center justify-between">
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Detail Pesanan #{{ $order->id }}</h2>
      <a href="{{ route('orders.track') }}" class="text-sm text-gray-600">← Kembali</a>
    </div>
  </x-slot>

  <div class="py-8">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white rounded shadow p-6">
        <div class="flex items-center justify-between">
          <div>
            <div class="text-sm text-gray-500">Tipe: {{ ucfirst($order->type) }}</div>
            <div class="text-sm text-gray-500">Dibuat: {{ optional($order->created_at)->format('d M Y H:i') }}</div>
          </div>
          @php
            $statusColors = [
              'pending' => 'bg-yellow-100',
              'preparing' => 'bg-purple-100',
              'ready' => 'bg-green-100',
              'completed' => 'bg-red-100'
            ];
            $statusClass = $statusColors[$order->status] ?? 'bg-red-100';
          @endphp
          <div class="text-sm px-3 py-1 rounded-full {{ $statusClass }}">
            {{ ucfirst($order->status) }}
          </div>
        </div>

        <div class="mt-4 border-t pt-4 space-y-3">
          @foreach($order->orderItems as $it)
            <div class="flex items-center justify-between">
              <div>
                <div class="font-medium">{{ $it->menuItem->name ?? 'Item' }}</div>
                <div class="text-sm text-gray-500">Qty: {{ $it->quantity }} • Rp {{ number_format($it->price,0,',','.') }} / pcs</div>
              </div>
              <div class="font-medium">Rp {{ number_format($it->price * $it->quantity,0,',','.') }}</div>
            </div>
          @endforeach
        </div>

        <div class="mt-6 flex items-center justify-between">
          <div class="text-sm text-gray-500">Total dibayar</div>
          <div class="text-xl font-semibold">Rp {{ number_format($order->total,0,',','.') }}</div>
        </div>

        <div class="mt-4 text-sm text-gray-600">
          {{-- Placeholder for additional info: ETA, driver, pickup code --}}
          @if($order->type === 'delivery')
            <div>Estimasi pengantaran: -</div>
          @elseif($order->type === 'takeaway')
            <div>Kode ambil: #{{ $order->id }}</div>
          @endif
        </div>

        <div class="mt-4">
          @if(in_array($order->type, ['takeaway','delivery']) && $order->status === 'ready' && ! $order->completed_at)
            <form action="{{ route('orders.track.complete', $order) }}" method="POST">
              @csrf
              <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded" onclick="return confirm('Tandai pesanan ini selesai?')">Selesai</button>
            </form>
          @elseif($order->completed_at)
            <div class="text-sm text-green-600">Pesanan ditandai selesai pada {{ optional($order->completed_at)->format('d M Y H:i') }}</div>
          @endif
        </div>
      </div>
    </div>
  </div>
</x-app-layout>