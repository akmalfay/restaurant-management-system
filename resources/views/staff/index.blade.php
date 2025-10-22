<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Staff Management') }}
    </h2>
  </x-slot>

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

      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2">Daftar Staff</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">
              Total: {{ $staffs->total() }} staff
            </p>
          </div>

          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
              <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    Nama
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    Email
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    Role
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    Status
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    Bergabung
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    Aksi
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($staffs as $staff)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 h-10 w-10">
                        @php
                        $defaultAvatar = 'https://ui-avatars.com/api/?name=' . urlencode($staff->name) . '&size=32&background=6366f1&color=fff';
                        @endphp
                        <img class="h-8 w-8 rounded-full object-cover"
                          src="{{ $staff->image ? asset('storage/' . $staff->image) : $defaultAvatar }}"
                          onerror="this.onerror=null; this.src='{{ $defaultAvatar }}';"
                          alt="{{ $staff->name }}">
                      </div>
                      <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                          {{ $staff->name }}
                        </div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ $staff->email }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                      @if($staff->staffDetail?->role === 'chef') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                      @elseif($staff->staffDetail?->role === 'waiter') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                      @else bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                      @endif">
                      {{ ucfirst($staff->staffDetail?->role ?? 'N/A') }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                      @if($staff->staffDetail?->is_active) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                      @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                      @endif">
                      {{ $staff->staffDetail?->is_active ? 'Active' : 'Inactive' }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                    {{ $staff->staffDetail?->joined_at?->format('d M Y') ?? '-' }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <a href="{{ route('staff.show', $staff) }}"
                      class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                      Detail
                    </a>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                    Belum ada data staff
                  </td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <div class="mt-4">
            {{ $staffs->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>