<x-app-layout>
  <x-slot name="header">
    <div class="flex items-center justify-between">
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('History Reservasi') }}
      </h2>
      <a href="{{ route('reservations.index') }}" class="px-3 py-1 rounded border text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
        ← Kembali ke Booking
      </a>
    </div>
  </x-slot>

  <div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
      <!-- Filters -->
      <form method="GET" action="{{ route('reservations.history') }}" class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-4 flex flex-wrap gap-3 items-end">
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kategori</label>
          <select name="category" class="mt-1 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
            @foreach($categories as $cat)
            <option value="{{ $cat }}" {{ $category === $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal</label>
          <input type="date" name="date" value="{{ $date->toDateString() }}" max="{{ \Carbon\Carbon::yesterday()->toDateString() }}" class="mt-1 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Shift</label>
          <select name="shift" class="mt-1 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
            <option value="morning" {{ $shift==='morning'?'selected':'' }}>Morning (12:00)</option>
            <option value="night" {{ $shift==='night'?'selected':'' }}>Night (19:00)</option>
          </select>
        </div>
        <div>
          <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md">Terapkan</button>
        </div>
      </form>

      <!-- Grid (read-only) -->
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @forelse($grid as $item)
        @php
        $t = $item['table'];
        $r = $item['reservation'];
        $hasReservation = !is_null($r);
        @endphp
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 border {{ $hasReservation ? 'border-blue-300' : 'border-gray-300' }}">
          <div class="flex items-start justify-between">
            <div>
              <div class="text-sm text-gray-500 dark:text-gray-400">Meja</div>
              <div class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ $t->name }}</div>
            </div>
            <div class="text-right">
              <div class="text-xs text-gray-500 dark:text-gray-400">{{ $date->format('d M Y') }}</div>
              <div class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst($shift) }}</div>
            </div>
          </div>

          <div class="mt-3">
            @if($hasReservation)
            <div class="space-y-1">
              <div class="flex items-center justify-between">
                <span class="text-xs text-gray-500 dark:text-gray-400">Status:</span>
                <span class="text-xs px-2 py-0.5 rounded
                    @switch($r->status)
                      @case('completed') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 @break
                      @case('confirmed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 @break
                      @case('cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @break
                      @default bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                    @endswitch">
                  {{ ucfirst($r->status) }}
                </span>
              </div>
              <div class="flex items-center justify-between text-xs">
                <span class="text-gray-500 dark:text-gray-400">Tamu:</span>
                <span class="text-gray-800 dark:text-gray-200">{{ $r->guests }}</span>
              </div>
              <div class="flex items-center justify-between text-xs">
                <span class="text-gray-500 dark:text-gray-400">Order ID:</span>
                <span class="text-gray-800 dark:text-gray-200">#{{ $r->order_id }}</span>
              </div>
            </div>
            @else
            <div class="text-xs text-gray-400 dark:text-gray-500">Tidak ada reservasi</div>
            @endif
          </div>
        </div>
        @empty
        <div class="col-span-4 text-gray-500 dark:text-gray-400 text-center py-8">
          Tidak ada data.
        </div>
        @endforelse
      </div>
    </div>
  </div>
</x-app-layout>