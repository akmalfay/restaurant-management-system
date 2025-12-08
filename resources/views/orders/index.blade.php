<x-app-layout>
  <x-slot name="header">
    <div class="flex items-center justify-between">
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">Orders</h2>
      <form method="GET" action="{{ route('orders.index') }}" class="flex items-center gap-2">
        <input name="q" value="{{ $q ?? '' }}" placeholder="Search order id or customer" class="text-sm rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 px-2 py-1" />

        <select name="type" class="text-sm rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 px-2 py-1">
          <option value="">All types</option>
          <option value="dine_in" {{ (isset($type) && $type==='dine_in') ? 'selected' : '' }}>Dine in</option>
          <option value="takeway" {{ (isset($type) && $type==='takeway') ? 'selected' : '' }}>Takeaway</option>
          <option value="delivery" {{ (isset($type) && $type==='delivery') ? 'selected' : '' }}>Delivery</option>
        </select>

        <select name="status" class="text-sm rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 px-2 py-1">
          <option value="">All statuses</option>
          @foreach(['pending','preparing','ready'] as $st)
          <option value="{{ $st }}" {{ (isset($status) && $status === $st) ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
          @endforeach
        </select>

        <button type="submit" class="text-sm px-3 py-1 bg-indigo-600 text-white rounded">Search</button>
        <a href="{{ route('orders.index') }}" class="text-sm px-3 py-1 bg-gray-300 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 rounded">Clear</a>
      </form>
    </div>
  </x-slot>

  <div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-4">
        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead>
              <tr class="text-left text-xs text-gray-500 dark:text-gray-400">
                <th class="p-2">Order</th>
                <th class="p-2">Customer</th>
                <th class="p-2">Type</th>
                <th class="p-2">Items</th>
                <th class="p-2">Status</th>
                <th class="p-2 text-right">Total</th>
                <th class="p-2">Time</th>
              </tr>
            </thead>
            <tbody>
              @foreach($orders as $o)
              <tr class="border-t">
                <td class="p-2 text-gray-800 dark:text-gray-200">
                  <button class="text-indigo-600 dark:text-indigo-300 hover:underline text-sm" onclick="showOrderDetail({{ $o->id }}, this)">#{{ $o->id }}</button>
                </td>
                <td class="p-2">
                  @if($o->customer && $o->customer->user)
                  <a href="{{ route('customer.show', $o->customer->user->id) }}" class="text-sm text-gray-800 dark:text-gray-200 hover:underline">
                    {{ $o->customer->user->name }}
                  </a>
                  @else
                  <span class="text-sm text-gray-600 dark:text-gray-400">Walk-in</span>
                  @endif
                </td>
                <td class="p-2 text-sm text-gray-700 dark:text-gray-300">{{ ucfirst(str_replace('_',' ',$o->type)) }}</td>

                {{-- items button --}}
                <td class="p-2 text-sm text-gray-700 dark:text-gray-300">
                  @if($o->orderItems->isNotEmpty())
                  <button type="button" onclick="showOrderDetail({{ $o->id }})" class="text-sm px-2 py-1 bg-gray-100 dark:bg-gray-900 dark:text-gray-200 rounded hover:bg-gray-200 dark:hover:bg-gray-800">
                    View items ({{ $o->orderItems->count() }})
                  </button>
                  @else
                  <span class="text-sm text-gray-500 dark:text-gray-400">-</span>
                  @endif
                </td>

                {{-- action buttons (replace dropdown) --}}
                <td class="p-2">
                  <div class="flex items-center gap-2">
                    @if($o->status === 'pending')
                    <form method="POST" action="{{ route('orders.updateStatus', $o) }}">
                      @csrf @method('PATCH')
                      <input type="hidden" name="status" value="preparing">
                      <button type="submit" class="px-2 py-1 text-xs bg-amber-500 text-white rounded">Prepare</button>
                    </form>
                    <form method="POST" action="{{ route('orders.updateStatus', $o) }}" onsubmit="return confirm('Batalkan order ini?')">
                      @csrf @method('PATCH')
                      <input type="hidden" name="status" value="cancel">
                      <button type="submit" class="px-2 py-1 text-xs bg-red-600 text-white rounded">Cancel</button>
                    </form>
                    @elseif($o->status === 'preparing')
                    <form method="POST" action="{{ route('orders.updateStatus', $o) }}">
                      @csrf @method('PATCH')
                      <input type="hidden" name="status" value="ready">
                      <button type="submit" class="px-2 py-1 text-xs bg-green-600 text-white rounded">Complete</button>
                    </form>
                    {{-- no Cancel option while preparing (preparing -> ready only) --}}
                    @else
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst($o->status) }}</span>
                    @endif
                  </div>
                </td>

                <td class="p-2 text-right text-sm text-gray-800 dark:text-gray-200">Rp {{ number_format($o->total,0,',','.') }}</td>
                <td class="p-2 text-sm text-gray-700 dark:text-gray-300">{{ optional($o->order_time)->format('d M H:i') }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <div class="mt-4">
          {{ $orders->links() }}
        </div>
      </div>
    </div>
  </div>

  <!-- Receipt modal (hidden) -->
  <div id="orderReceiptModal" class="fixed inset-0 hidden items-center justify-center z-50">
    <div class="absolute inset-0 bg-black/40"></div>
    <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-lg w-[420px] max-w-full mx-4">
      <div class="p-4">
        <div id="receiptContent" class="text-sm text-gray-800 dark:text-gray-200"></div>
        <div class="mt-4 flex justify-end gap-2">
          <button onclick="printReceipt()" class="px-3 py-1 bg-indigo-600 text-white rounded text-sm">Print</button>
          <button onclick="closeReceipt()" class="px-3 py-1 bg-gray-600 text-white rounded text-sm">Close</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    function money(v) {
      return 'Rp ' + Number(v || 0).toLocaleString('id-ID');
    }

    function closeReceipt() {
      document.getElementById('orderReceiptModal').classList.add('hidden');
      document.getElementById('receiptContent').innerHTML = '';
    }

    function printReceipt() {
      const content = document.getElementById('receiptContent').innerHTML;
      const w = window.open('', '_blank', 'width=420,height=520');
      w.document.write('<pre style="font-family:monospace;">' + content + '</pre>');
      w.document.close();
      w.print();
    }

    async function showOrderDetail(id, el) {
      const res = await fetch("{{ url('/orders') }}/" + id);
      if (!res.ok) {
        alert('Gagal memuat detail');
        return;
      }
      const j = await res.json();

      const itemsHtml = j.items.map(i => {
        return `<div class="flex justify-between"><div>${i.qty} × ${i.name}</div><div>${money(i.price)}</div></div>`;
      }).join('');

      const header = `
        <div class="text-center font-bold text-lg mb-2 text-gray-800 dark:text-gray-200">RECEIPT</div>
        <div class="text-xs text-gray-600 dark:text-gray-400 mb-2">Order #${j.id} • ${j.type} • ${j.status}</div>
        <div class="text-xs text-gray-700 dark:text-gray-300 mb-2">Customer: ${j.customer ? `<a href="{{ url('/customers') }}/${j.customer.id}" class="underline text-indigo-600 dark:text-indigo-300">${j.customer.name}</a>` : 'Walk-in'}</div>
        <div class="text-xs text-gray-700 dark:text-gray-300 mb-2">Time: ${new Date(j.order_time).toLocaleString()}</div>
        <div class="border-t border-dashed my-2 pt-2">${itemsHtml}</div>
      `;

      const totals = `<div class="mt-2 border-t pt-2 text-right font-semibold text-gray-800 dark:text-gray-200">${money(j.total)}</div>`;

      document.getElementById('receiptContent').innerHTML = header + totals;
      document.getElementById('orderReceiptModal').classList.remove('hidden');
    }
  </script>
</x-app-layout>