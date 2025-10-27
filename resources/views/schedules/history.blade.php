<x-app-layout>
  <x-slot name="header">
    <div class="flex items-center justify-between">
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('History Jadwal Kerja') }}
      </h2>
      <a href="{{ route('schedules.index') }}" class="px-3 py-1 rounded border text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
        ← Kembali
      </a>
    </div>
  </x-slot>

  <div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

      <!-- Year & Month Selector -->
      <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
        <form method="GET" action="{{ route('schedules.history') }}" class="flex items-center gap-4 flex-wrap">
          <div class="flex items-center gap-2">
            <label for="year-select" class="text-sm font-semibold text-gray-700 dark:text-gray-300">
              Tahun:
            </label>
            <select id="year-select" name="year" onchange="this.form.submit()"
              class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
              @foreach($availableYears as $year)
              <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                {{ $year }}
              </option>
              @endforeach
            </select>
          </div>

          <div class="flex items-center gap-2">
            <label for="month-select" class="text-sm font-semibold text-gray-700 dark:text-gray-300">
              Bulan:
            </label>
            <select id="month-select" name="month" onchange="this.form.submit()"
              class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
              @foreach($availableMonths as $monthNum => $monthName)
              <option value="{{ $monthNum }}" {{ $selectedMonth == $monthNum ? 'selected' : '' }}>
                {{ $monthName }}
              </option>
              @endforeach
            </select>
          </div>
        </form>
      </div>

      <!-- Monthly Calendar -->
      <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="p-6">
          <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">
            {{ $monthLabel }}
          </h3>

          <!-- Weekday Headers -->
          <div class="grid grid-cols-7 gap-2 mb-2">
            <div class="text-center text-xs font-semibold text-gray-600 dark:text-gray-400 py-2">Sen</div>
            <div class="text-center text-xs font-semibold text-gray-600 dark:text-gray-400 py-2">Sel</div>
            <div class="text-center text-xs font-semibold text-gray-600 dark:text-gray-400 py-2">Rab</div>
            <div class="text-center text-xs font-semibold text-gray-600 dark:text-gray-400 py-2">Kam</div>
            <div class="text-center text-xs font-semibold text-gray-600 dark:text-gray-400 py-2">Jum</div>
            <div class="text-center text-xs font-semibold text-gray-600 dark:text-gray-400 py-2">Sab</div>
            <div class="text-center text-xs font-semibold text-red-600 dark:text-red-400 py-2">Min</div>
          </div>

          <!-- Calendar Grid -->
          <div class="grid grid-cols-7 gap-2">
            @foreach($monthData as $day)
            <div class="border rounded-lg p-2 min-h-[180px] flex flex-col
              {{ $day['inMonth'] ? 'border-gray-200 dark:border-gray-700' : 'border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/30' }}
              {{ $day['isSunday'] ? 'bg-red-50 dark:bg-red-900/10' : '' }}">

              <!-- Date Header -->
              <div class="flex items-center justify-between mb-2">
                <div class="text-sm font-semibold {{ $day['inMonth'] ? 'text-gray-800 dark:text-gray-200' : 'text-gray-400 dark:text-gray-500' }} {{ $day['isSunday'] ? 'text-red-600 dark:text-red-400' : '' }}">
                  {{ $day['dayNumber'] }}
                </div>
                @if($day['isSunday'] && $day['inMonth'])
                <div class="text-[10px] px-2 py-0.5 rounded-full bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200">
                  LIBUR
                </div>
                @endif
              </div>

              @if($day['inMonth'] && !$day['isSunday'])
              <!-- Morning Shift -->
              <div class="mb-2 flex-1 overflow-auto">
                <div class="text-[10px] font-semibold text-amber-700 dark:text-amber-300 mb-1">Morning</div>
                <div class="space-y-1">
                  @forelse($day['morning'] as $s)
                  <div class="flex items-center justify-between gap-1">
                    <a href="{{ route('staff.show', $s['user_id']) }}"
                      class="text-[11px] text-indigo-600 dark:text-indigo-400 hover:underline truncate">
                      {{ $s['name'] }}
                    </a>
                    <span class="text-[9px] px-1 py-0.5 rounded
                      @switch($s['role'])
                        @case('chef') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200 @break
                        @case('waiter') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 @break
                        @default bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                      @endswitch">
                      {{ ucfirst($s['role']) }}
                    </span>
                  </div>
                  @empty
                  <div class="text-[10px] text-gray-400">-</div>
                  @endforelse
                </div>
              </div>

              <hr class="my-1 border-gray-200 dark:border-gray-700">

              <!-- Night Shift -->
              <div class="flex-1 overflow-auto">
                <div class="text-[10px] font-semibold text-sky-700 dark:text-sky-300 mb-1">Night</div>
                <div class="space-y-1">
                  @forelse($day['night'] as $s)
                  <div class="flex items-center justify-between gap-1">
                    <a href="{{ route('staff.show', $s['user_id']) }}"
                      class="text-[11px] text-indigo-600 dark:text-indigo-400 hover:underline truncate">
                      {{ $s['name'] }}
                    </a>
                    <span class="text-[9px] px-1 py-0.5 rounded
                      @switch($s['role'])
                        @case('chef') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200 @break
                        @case('waiter') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 @break
                        @default bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                      @endswitch">
                      {{ ucfirst($s['role']) }}
                    </span>
                  </div>
                  @empty
                  <div class="text-[10px] text-gray-400">-</div>
                  @endforelse
                </div>
              </div>
              @endif
            </div>
            @endforeach
          </div>

          <!-- Summary Stats -->
          <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-lg">
              <div class="text-xs text-gray-600 dark:text-gray-400 font-semibold">Total Shifts</div>
              <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                {{ $totalShifts }}
              </div>
            </div>
            <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg">
              <div class="text-xs text-purple-700 dark:text-purple-300 font-semibold">Chef</div>
              <div class="text-2xl font-bold text-purple-900 dark:text-purple-100">
                {{ $chefShifts }}
              </div>
            </div>
            <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
              <div class="text-xs text-blue-700 dark:text-blue-300 font-semibold">Waiter</div>
              <div class="text-2xl font-bold text-blue-900 dark:text-blue-100">
                {{ $waiterShifts }}
              </div>
            </div>
            <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
              <div class="text-xs text-green-700 dark:text-green-300 font-semibold">Cashier</div>
              <div class="text-2xl font-bold text-green-900 dark:text-green-100">
                {{ $cashierShifts }}
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</x-app-layout>