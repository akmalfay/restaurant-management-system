<x-app-layout>
  <x-slot name="header">
    <div class="flex items-center justify-between">
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Reservasi (Per Jam)') }}
      </h2>
      <div class="flex items-center gap-2">
        <a href="{{ route('reservations.history') }}" class="px-3 py-1 rounded border text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">History</a>
        @php $nextWeek = min(($week ?? 0) + 1, 4); @endphp
        <a href="{{ route('reservations.index', ['category'=>$category,'week'=>$nextWeek,'day'=>0]) }}"
          class="px-3 py-1 rounded bg-indigo-600 text-white text-sm {{ ($week ?? 0) >= 4 ? 'pointer-events-none opacity-60' : '' }}">
          Minggu Depan
        </a>
        @if(($week ?? 0) > 0)
        <a href="{{ route('reservations.index', ['category'=>$category,'week'=>0,'day'=>0]) }}" class="px-3 py-1 rounded bg-gray-700 text-white text-sm">Minggu Ini</a>
        @endif
      </div>
    </div>
  </x-slot>

  <div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
      @if ($errors->any())
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        <ul class="list-disc pl-5">
          @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
      </div>
      @endif

      @if(session('status'))
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ session('status') }}
      </div>
      @endif

      <!-- Filters -->
      <form method="GET" action="{{ route('reservations.index') }}" class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-4 flex flex-wrap gap-3 items-end">
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kategori</label>
          <select name="category" class="mt-1 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
            @foreach($categories as $cat)
            @php
            $capacityInfo = match($cat) {
            'VIP' => '4 kursi',
            'Terrace' => '4 kursi',
            'Outdoor' => '3 kursi',
            'Indoor' => '2 kursi',
            default => '2 kursi'
            };
            @endphp
            <option value="{{ $cat }}" {{ $category === $cat ? 'selected' : '' }}>
              {{ $cat }} ({{ $capacityInfo }})
            </option>
            @endforeach
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Hari</label>
          <select name="day" class="mt-1 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 min-w-[200px]">
            @foreach($dayOptions as $option)
            <option value="{{ $option['offset'] }}" {{ $dayOffset === $option['offset'] ? 'selected' : '' }}>
              {{ $option['label'] }}
            </option>
            @endforeach
          </select>
        </div>

        <input type="hidden" name="week" value="{{ $week }}">

        <div class="text-sm text-gray-600 dark:text-gray-300">
          <div>Minggu: {{ $weekStart->format('d M') }} — {{ $weekStart->copy()->addDays(5)->format('d M Y') }}</div>
          <div>Jam: 10:00 — 24:00 (per 1 jam)</div>
        </div>

        <div>
          <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md">Terapkan</button>
        </div>
      </form>

      <!-- Single Day Table -->
      <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
          <div class="font-semibold text-lg text-gray-800 dark:text-gray-200">
            {{ \Illuminate\Support\Str::headline($selectedDate->locale('id')->dayName) }},
            {{ $selectedDate->format('d F Y') }}
          </div>
          @if($isPast)
          <span class="px-2 py-1 rounded bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300 text-xs">Sudah Lewat</span>
          @endif
        </div>

        <!-- Scroll Container -->
        <div class="overflow-auto max-h-[600px]">
          <table class="w-full text-xs border-collapse">
            <thead class="bg-gray-50 dark:bg-gray-700 sticky top-0 z-20">
              <tr>
                <th class="px-3 py-2 text-left font-semibold text-gray-700 dark:text-gray-200 border-b-2 border-gray-300 dark:border-gray-600 sticky left-0 bg-gray-50 dark:bg-gray-700 z-30 min-w-[100px]">Meja</th>
                <th class="px-2 py-2 text-center font-semibold text-gray-700 dark:text-gray-200 border-b-2 border-gray-300 dark:border-gray-600 min-w-[70px]">
                  <div>Kapasitas</div>
                  <div class="text-[9px] font-normal text-gray-500 dark:text-gray-400">(kursi)</div>
                </th>
                @foreach($hours as $h)
                <th class="px-2 py-2 text-center font-semibold text-gray-700 dark:text-gray-200 border-b-2 border-gray-300 dark:border-gray-600 min-w-[120px]">{{ str_pad($h,2,'0',STR_PAD_LEFT) }}:00</th>
                @endforeach
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
              @foreach($rows as $row)
              <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                <td class="px-3 py-2 text-gray-800 dark:text-gray-200 font-medium border-r border-gray-200 dark:border-gray-700 sticky left-0 bg-white dark:bg-gray-800 z-10">
                  {{ $row['table']->name }}
                </td>
                <td class="px-2 py-2 text-center border-r border-gray-200 dark:border-gray-700">
                  <div class="flex items-center justify-center gap-1">
                    <svg class="w-3 h-3 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span class="font-semibold text-indigo-600 dark:text-indigo-400">{{ $row['table']->capacity }}</span>
                  </div>
                </td>
                @foreach($row['slots'] as $idx => $slot)
                @php
                $hour = $hours[$idx];

                // Cari semua reservasi di slot ini (untuk konflik)
                $allSlots = collect($slot)->filter()->values();
                if (!is_array($slot) && $slot) {
                $allSlots = collect([$slot]);
                } elseif (is_array($slot)) {
                $allSlots = collect($slot);
                } else {
                $allSlots = collect([]);
                }

                $hasConflict = $allSlots->count() > 1;
                $bg = 'bg-green-50 dark:bg-green-900/20';

                if ($hasConflict) {
                $bg = 'bg-orange-100 dark:bg-orange-900/40 border-2 border-orange-500';
                } elseif ($allSlots->count() === 1) {
                $single = $allSlots->first();
                switch ($single->status) {
                case 'pending': $bg = 'bg-amber-100 dark:bg-amber-900/30'; break;
                case 'confirmed': $bg = 'bg-blue-100 dark:bg-blue-900/30'; break;
                case 'completed': $bg = 'bg-gray-200 dark:bg-gray-700'; break;
                case 'cancelled': $bg = 'bg-red-100 dark:bg-red-900/30'; break;
                case 'no_show': $bg = 'bg-rose-100 dark:bg-rose-900/30'; break;
                }
                }
                @endphp
                <td class="p-1">
                  <div class="rounded border {{ $bg }} border-gray-300 dark:border-gray-600 p-2 min-h-[80px] flex flex-col justify-between text-center">
                    @if($hasConflict)
                    {{-- Multiple Bookings --}}
                    <div class="text-[9px] font-bold text-orange-700 dark:text-orange-300 mb-1">
                      ⚠ {{ $allSlots->count() }} KONFLIK
                    </div>
                    <div class="space-y-1 max-h-[120px] overflow-y-auto">
                      @foreach($allSlots as $s)
                      <div class="border-b border-orange-300 dark:border-orange-700 pb-1 last:border-0">
                        <div class="font-semibold text-indigo-700 dark:text-indigo-400 text-[10px]">#{{ $s->order_id }}</div>
                        <div class="text-[9px] text-gray-700 dark:text-gray-300 truncate">{{ optional(optional($s->order)->customer)->user->name ?? 'Walk-in' }}</div>
                        <div class="text-[8px] uppercase {{ $s->status === 'pending' ? 'text-amber-700' : 'text-blue-700' }}">{{ $s->status }}</div>
                        @if($canManage && $s->status === 'pending')
                        <div class="flex items-center justify-center gap-1 mt-0.5">
                          <form method="POST" action="{{ route('reservations.approve', $s) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="p-0.5 rounded bg-green-600 hover:bg-green-700 text-white" title="Approve">
                              <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                              </svg>
                            </button>
                          </form>
                          <form method="POST" action="{{ route('reservations.cancel', $s) }}" onsubmit="return confirm('Tolak?')">
                            @csrf @method('PATCH')
                            <button type="submit" class="p-0.5 rounded bg-red-600 hover:bg-red-700 text-white" title="Tolak">
                              <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                              </svg>
                            </button>
                          </form>
                        </div>
                        @endif
                      </div>
                      @endforeach
                    </div>
                    @elseif($allSlots->count() === 1)
                    {{-- Single Booking --}}
                    @php $slot = $allSlots->first(); @endphp
                    <div class="space-y-0.5">
                      <div class="font-semibold text-indigo-700 dark:text-indigo-400">#{{ $slot->order_id }}</div>
                      <div class="text-[10px] text-gray-700 dark:text-gray-300 truncate" title="{{ optional(optional($slot->order)->customer)->user->name ?? 'Walk-in' }}">
                        {{ optional(optional($slot->order)->customer)->user->name ?? 'Walk-in' }}
                      </div>
                      <div class="text-[9px] uppercase font-medium {{ $slot->status === 'confirmed' ? 'text-blue-700 dark:text-blue-300' : ($slot->status === 'pending' ? 'text-amber-700 dark:text-amber-300' : 'text-gray-600 dark:text-gray-400') }}">
                        {{ $slot->status }}
                      </div>
                    </div>
                    @if($canManage && in_array($slot->status, ['pending','confirmed']))
                    <div class="mt-1 flex items-center justify-center gap-1">
                      @if($slot->status === 'pending')
                      <form method="POST" action="{{ route('reservations.approve', $slot) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="p-1 rounded bg-green-600 hover:bg-green-700 text-white" title="Approve">
                          <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                          </svg>
                        </button>
                      </form>
                      @endif
                      @if($slot->status === 'confirmed')
                      <form method="POST" action="{{ route('reservations.complete', $slot) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="p-1 rounded bg-blue-600 hover:bg-blue-700 text-white" title="Complete">
                          <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                          </svg>
                        </button>
                      </form>
                      @endif
                      <form method="POST" action="{{ route('reservations.cancel', $slot) }}" onsubmit="return confirm('Batalkan?')">
                        @csrf @method('PATCH')
                        <button type="submit" class="p-1 rounded bg-red-600 hover:bg-red-700 text-white" title="Cancel">
                          <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                          </svg>
                        </button>
                      </form>
                    </div>
                    @endif
                    @elseif($canManage && !$isPast)
                    {{-- Form Add --}}
                    <details class="cursor-pointer">
                      <summary class="text-emerald-700 dark:text-emerald-300 text-[10px] flex items-center justify-center gap-0.5">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span>Add</span>
                      </summary>
                      <form method="POST" action="{{ route('reservations.store') }}" class="mt-1 space-y-1">
                        @csrf
                        <input type="hidden" name="table_id" value="{{ $row['table']->id }}">
                        <input type="hidden" name="date" value="{{ $selectedDate->toDateString() }}">
                        <input type="hidden" name="hour" value="{{ $hour }}">
                        <input type="number" name="order_id" placeholder="Order ID" required class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 text-[10px] px-1 py-0.5">
                        <button type="submit" class="w-full rounded bg-indigo-600 hover:bg-indigo-700 text-white py-0.5 text-[10px]">Simpan</button>
                      </form>
                    </details>
                    @else
                    <span class="text-gray-400 dark:text-gray-500 text-[10px]">Tersedia</span>
                    @endif
                  </div>
                </td>
                @endforeach
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>