<x-app-layout>
  <x-slot name="header">
    <div class="flex items-center justify-between">
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Jadwal Kerja') }}
      </h2>
      <a href="{{ route('schedules.history') }}" class="px-3 py-1 rounded border text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
        Lihat History
      </a>
    </div>
  </x-slot>

  <div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

      @if(session('status'))
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ session('status') }}
      </div>
      @endif

      <!-- Minggu Ini -->
      <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="p-6">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
            Minggu Ini: {{ $currentWeekLabel }}
          </h3>

          <div class="overflow-x-auto">
            <table class="min-w-full border-collapse border border-gray-300 dark:border-gray-700">
              <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                  <th class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-left text-sm font-semibold">Shift</th>
                  @foreach($currentWeek as $day)
                  @if(!$day['date']->isSunday())
                  <th class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-center text-sm font-semibold">
                    <div>{{ $day['dayName'] }}</div>
                    <div class="text-xs font-normal text-gray-600 dark:text-gray-400">{{ $day['date']->format('d M') }}</div>
                  </th>
                  @endif
                  @endforeach
                </tr>
              </thead>
              <tbody>
                <!-- Morning Shift -->
                <tr class="bg-amber-50 dark:bg-amber-900/20">
                  <td class="border border-gray-300 dark:border-gray-700 px-4 py-3 font-medium text-sm">
                    <div class="flex items-center gap-2">
                      <span>Pagi</span>
                    </div>
                  </td>
                  @foreach($currentWeek as $day)
                  @if(!$day['date']->isSunday())
                  <td class="border border-gray-300 dark:border-gray-700 px-2 py-3">
                    <div class="space-y-1">
                      @forelse($day['morning'] as $s)
                      <div class="flex items-center justify-between gap-2 bg-white dark:bg-gray-800 rounded px-2 py-1 text-xs">
                        <div>
                          <div class="font-medium">{{ $s['name'] }}</div>
                          <div class="text-[10px] text-gray-500 dark:text-gray-400">{{ ucfirst($s['role']) }}</div>
                        </div>
                        @if($canEdit)
                        <form method="POST" action="{{ route('schedules.destroy', $s['schedule_id']) }}" onsubmit="return confirm('Hapus shift ini?')">
                          @csrf @method('DELETE')
                          <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400">×</button>
                        </form>
                        @endif
                      </div>
                      @empty
                      <div class="text-center text-gray-400 dark:text-gray-500 text-xs">-</div>
                      @endforelse

                      @if($canEdit)
                      <form method="POST" action="{{ route('schedules.assign') }}" class="mt-2">
                        @csrf
                        <input type="hidden" name="schedule_date" value="{{ $day['dateStr'] }}">
                        <input type="hidden" name="shift" value="morning">
                        <select name="staff_id" class="w-full text-xs rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700" required>
                          <option value="">+ Staff</option>
                          @foreach($allStaff as $staff)
                          <option value="{{ $staff->staff_id }}">{{ $staff->name }} ({{ $staff->role }})</option>
                          @endforeach
                        </select>
                        <button type="submit" class="mt-1 w-full text-xs px-2 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700">Assign</button>
                      </form>
                      @endif
                    </div>
                  </td>
                  @endif
                  @endforeach
                </tr>

                <!-- Night Shift -->
                <tr class="bg-indigo-50 dark:bg-indigo-900/20">
                  <td class="border border-gray-300 dark:border-gray-700 px-4 py-3 font-medium text-sm">
                    <div class="flex items-center gap-2">
                      <span>Malam</span>
                    </div>
                  </td>
                  @foreach($currentWeek as $day)
                  @if(!$day['date']->isSunday())
                  <td class="border border-gray-300 dark:border-gray-700 px-2 py-3">
                    <div class="space-y-1">
                      @forelse($day['night'] as $s)
                      <div class="flex items-center justify-between gap-2 bg-white dark:bg-gray-800 rounded px-2 py-1 text-xs">
                        <div>
                          <div class="font-medium">{{ $s['name'] }}</div>
                          <div class="text-[10px] text-gray-500 dark:text-gray-400">{{ ucfirst($s['role']) }}</div>
                        </div>
                        @if($canEdit)
                        <form method="POST" action="{{ route('schedules.destroy', $s['schedule_id']) }}" onsubmit="return confirm('Hapus shift ini?')">
                          @csrf @method('DELETE')
                          <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400">×</button>
                        </form>
                        @endif
                      </div>
                      @empty
                      <div class="text-center text-gray-400 dark:text-gray-500 text-xs">-</div>
                      @endforelse

                      @if($canEdit)
                      <form method="POST" action="{{ route('schedules.assign') }}" class="mt-2">
                        @csrf
                        <input type="hidden" name="schedule_date" value="{{ $day['dateStr'] }}">
                        <input type="hidden" name="shift" value="night">
                        <select name="staff_id" class="w-full text-xs rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700" required>
                          <option value="">+ Staff</option>
                          @foreach($allStaff as $staff)
                          <option value="{{ $staff->staff_id }}">{{ $staff->name }} ({{ $staff->role }})</option>
                          @endforeach
                        </select>
                        <button type="submit" class="mt-1 w-full text-xs px-2 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700">Assign</button>
                      </form>
                      @endif
                    </div>
                  </td>
                  @endif
                  @endforeach
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Minggu Depan -->
      <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="p-6">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
            Minggu Depan: {{ $nextWeekLabel }}
          </h3>

          <div class="overflow-x-auto">
            <table class="min-w-full border-collapse border border-gray-300 dark:border-gray-700">
              <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                  <th class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-left text-sm font-semibold">Shift</th>
                  @foreach($nextWeek as $day)
                  @if(!$day['date']->isSunday())
                  <th class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-center text-sm font-semibold">
                    <div>{{ $day['dayName'] }}</div>
                    <div class="text-xs font-normal text-gray-600 dark:text-gray-400">{{ $day['date']->format('d M') }}</div>
                  </th>
                  @endif
                  @endforeach
                </tr>
              </thead>
              <tbody>
                <!-- Morning Shift -->
                <tr class="bg-amber-50 dark:bg-amber-900/20">
                  <td class="border border-gray-300 dark:border-gray-700 px-4 py-3 font-medium text-sm">
                    <div class="flex items-center gap-2">
                      <span>🌅</span>
                      <span>Morning</span>
                    </div>
                  </td>
                  @foreach($nextWeek as $day)
                  @if(!$day['date']->isSunday())
                  <td class="border border-gray-300 dark:border-gray-700 px-2 py-3">
                    <div class="space-y-1">
                      @forelse($day['morning'] as $s)
                      <div class="flex items-center justify-between gap-2 bg-white dark:bg-gray-800 rounded px-2 py-1 text-xs">
                        <div>
                          <div class="font-medium">{{ $s['name'] }}</div>
                          <div class="text-[10px] text-gray-500 dark:text-gray-400">{{ ucfirst($s['role']) }}</div>
                        </div>
                        @if($canEdit)
                        <form method="POST" action="{{ route('schedules.destroy', $s['schedule_id']) }}" onsubmit="return confirm('Hapus shift ini?')">
                          @csrf @method('DELETE')
                          <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400">×</button>
                        </form>
                        @endif
                      </div>
                      @empty
                      <div class="text-center text-gray-400 dark:text-gray-500 text-xs">-</div>
                      @endforelse

                      @if($canEdit)
                      <form method="POST" action="{{ route('schedules.assign') }}" class="mt-2">
                        @csrf
                        <input type="hidden" name="schedule_date" value="{{ $day['dateStr'] }}">
                        <input type="hidden" name="shift" value="morning">
                        <select name="staff_id" class="w-full text-xs rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700" required>
                          <option value="">+ Staff</option>
                          @foreach($allStaff as $staff)
                          <option value="{{ $staff->staff_id }}">{{ $staff->name }} ({{ $staff->role }})</option>
                          @endforeach
                        </select>
                        <button type="submit" class="mt-1 w-full text-xs px-2 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700">Assign</button>
                      </form>
                      @endif
                    </div>
                  </td>
                  @endif
                  @endforeach
                </tr>

                <!-- Night Shift -->
                <tr class="bg-indigo-50 dark:bg-indigo-900/20">
                  <td class="border border-gray-300 dark:border-gray-700 px-4 py-3 font-medium text-sm">
                    <div class="flex items-center gap-2">
                      <span>🌙</span>
                      <span>Night</span>
                    </div>
                  </td>
                  @foreach($nextWeek as $day)
                  @if(!$day['date']->isSunday())
                  <td class="border border-gray-300 dark:border-gray-700 px-2 py-3">
                    <div class="space-y-1">
                      @forelse($day['night'] as $s)
                      <div class="flex items-center justify-between gap-2 bg-white dark:bg-gray-800 rounded px-2 py-1 text-xs">
                        <div>
                          <div class="font-medium">{{ $s['name'] }}</div>
                          <div class="text-[10px] text-gray-500 dark:text-gray-400">{{ ucfirst($s['role']) }}</div>
                        </div>
                        @if($canEdit)
                        <form method="POST" action="{{ route('schedules.destroy', $s['schedule_id']) }}" onsubmit="return confirm('Hapus shift ini?')">
                          @csrf @method('DELETE')
                          <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400">×</button>
                        </form>
                        @endif
                      </div>
                      @empty
                      <div class="text-center text-gray-400 dark:text-gray-500 text-xs">-</div>
                      @endforelse

                      @if($canEdit)
                      <form method="POST" action="{{ route('schedules.assign') }}" class="mt-2">
                        @csrf
                        <input type="hidden" name="schedule_date" value="{{ $day['dateStr'] }}">
                        <input type="hidden" name="shift" value="night">
                        <select name="staff_id" class="w-full text-xs rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700" required>
                          <option value="">+ Staff</option>
                          @foreach($allStaff as $staff)
                          <option value="{{ $staff->staff_id }}">{{ $staff->name }} ({{ $staff->role }})</option>
                          @endforeach
                        </select>
                        <button type="submit" class="mt-1 w-full text-xs px-2 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700">Assign</button>
                      </form>
                      @endif
                    </div>
                  </td>
                  @endif
                  @endforeach
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</x-app-layout>