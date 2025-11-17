<x-app-layout>
  <x-slot name="header">
    <div class="flex items-center justify-between">
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">Manajemen Meja</h2>
    </div>
  </x-slot>

  <div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

      {{-- persistent maintenance info (now supports multiple tables) --}}
      @php $notes = session()->get('maintenance_notifications', []); @endphp
      @if(!empty($notes))
      <div class="space-y-3">
        @foreach($notes as $note)
        <div class="bg-yellow-50 dark:bg-yellow-900/30 border-l-4 border-yellow-400 p-4 rounded text-sm text-yellow-800 dark:text-yellow-200">
          <div class="flex items-start justify-between mb-2">
            <div>
              <strong class="text-gray-800 dark:text-gray-200">{{ $note['table']['name'] }}</strong>
              <div class="text-xs mt-1">Terdapat {{ $note['count'] }} reservasi (hari ini & masa depan) yang terpengaruh.</div>
              @if(!empty($note['reason']))<div class="text-xs text-gray-600 dark:text-gray-400 mt-1">Alasan: {{ $note['reason'] }}</div>@endif
            </div>
            <div class="flex items-center gap-2">
              <form method="POST" action="{{ route('tables.maintenance.delete') }}">
                @csrf
                <input type="hidden" name="table_id" value="{{ $note['table']['id'] }}">
                <button type="submit" class="px-2 py-1 text-xs bg-red-600 text-white rounded">Hapus Semua Reservasi Terdampak</button>
              </form>
              <form method="POST" action="{{ route('tables.maintenance.clear') }}">
                @csrf
                <input type="hidden" name="table_id" value="{{ $note['table']['id'] }}">
                <button type="submit" class="px-2 py-1 text-xs bg-gray-600 text-white rounded">Tutup Notifikasi</button>
              </form>
            </div>
          </div>

          <div class="overflow-auto max-h-40 border-t pt-2">
            <table class="w-full text-xs">
              <thead>
                <tr class="text-left">
                  <th class="pb-1">Tanggal</th>
                  <th class="pb-1">Jam</th>
                  <th class="pb-1">Order</th>
                  <th class="pb-1">Customer</th>
                  <th class="pb-1 text-right">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($note['affected'] as $a)
                <tr class="border-t">
                  <td class="py-1 text-gray-700 dark:text-gray-200">{{ $a['date'] }}</td>
                  <td class="py-1 text-gray-700 dark:text-gray-200">{{ $a['time'] }}</td>
                  <td class="py-1 text-gray-700 dark:text-gray-200">#{{ $a['order_id'] }}</td>
                  <td class="py-1 text-gray-700 dark:text-gray-200">{{ $a['customer'] }}</td>
                  <td class="py-1 text-right">
                    <form method="POST" action="{{ route('reservations.cancel', ['reservation' => $a['id']]) }}" onsubmit="return confirm('Hapus reservasi ini?')">
                      @csrf @method('PATCH')
                      <button type="submit" class="px-2 py-0.5 text-xs bg-red-600 text-white rounded">Hapus</button>
                    </form>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        @endforeach
      </div>
      @endif

      {{-- existing status / flash messages --}}
      @if(session('status'))
      <div class="bg-green-50 border-l-4 border-green-400 p-3 rounded text-sm text-green-800">
        {{ session('status') }}
      </div>
      @endif

      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        @foreach(['VIP','Terrace','Outdoor','Indoor'] as $cat)
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4">
          <div class="font-semibold mb-2 text-gray-800 dark:text-gray-200">{{ $cat }}</div>
          <div class="space-y-2">
            @foreach($tablesByCategory[$cat] ?? collect() as $t)
            <div class="flex items-center justify-between border p-2 rounded">
              <div>
                <div class="font-medium text-gray-800 dark:text-gray-200">
                  {{ $t->name }}
                  <span class="text-xs text-gray-500 dark:text-gray-400">({{ $t->capacity }})</span>
                </div>
                <div class="text-xs mt-0.5">
                  @if($t->status === 'available')
                  <span class="px-2 py-0.5 rounded bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Available</span>
                  @else
                  <span class="px-2 py-0.5 rounded bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200">Maintenance</span>
                  @endif
                </div>
              </div>

              <div class="flex items-center gap-2">
                @if($t->status === 'available')
                <form method="POST" action="{{ route('tables.maintenance', ['table' => $t->id]) }}">
                  @csrf @method('PATCH')
                  <input name="reason" type="hidden" value="Marked via admin grid">
                  <button type="submit" class="text-xs px-2 py-1 bg-amber-600 text-white rounded">Set Maintenance</button>
                </form>
                @else
                <form method="POST" action="{{ route('tables.available', ['table' => $t->id]) }}">
                  @csrf @method('PATCH')
                  <button type="submit" class="text-xs px-2 py-1 bg-gray-600 text-white rounded">Set Available</button>
                </form>
                @endif
              </div>
            </div>
            @endforeach
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</x-app-layout>