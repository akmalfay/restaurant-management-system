<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Staff Management') }}
    </h2>
  </x-slot>

  @php
  $openAdd = request()->boolean('add') || $errors->any();
  @endphp

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

      <div class="text-gray-900 dark:text-gray-100">
        @if($openAdd && Auth::user()->user_type === 'admin')
        <!-- Modal overlay (tanpa JS) -->
        <div class="fixed inset-0 z-50 flex items-center justify-center">
          <!-- backdrop -->
          <div class="absolute inset-0 bg-black/50"></div>

          <!-- dialog -->
          <div class="relative bg-white dark:bg-gray-800 w-full max-w-2xl mx-4 rounded-lg shadow-xl">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
              <h4 class="text-lg font-semibold">Tambah Staff</h4>
              <a href="{{ route('staff.index', request()->except('add')) }}"
                class="p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700" aria-label="Close">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                  viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd"
                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 
                           1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 
                           1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 
                           10 4.293 5.707a1 1 0 010-1.414z"
                    clip-rule="evenodd" />
                </svg>
              </a>
            </div>

            <div class="p-6">
              @if ($errors->any())
              <div class="mb-4 p-3 rounded bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-200">
                <ul class="list-disc ms-5">
                  @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
              @endif

              <form action="{{ route('staff.store') }}" method="POST" enctype="multipart/form-data"
                class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @csrf

                <div>
                  <label class="block text-sm font-medium mb-1">Nama</label>
                  <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200" />
                </div>

                <div>
                  <label class="block text-sm font-medium mb-1">Email</label>
                  <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200" />
                </div>

                <div>
                  <label class="block text-sm font-medium mb-1">Password</label>
                  <input type="password" name="password" required
                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200" />
                </div>

                <div>
                  <label class="block text-sm font-medium mb-1">Konfirmasi Password</label>
                  <input type="password" name="password_confirmation" required
                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200" />
                </div>

                <div>
                  <label class="block text-sm font-medium mb-1">Role</label>
                  <select name="role" required
                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
                    <option value="chef" {{ old('role')==='chef'?'selected':'' }}>Chef</option>
                    <option value="waiter" {{ old('role')==='waiter'?'selected':'' }}>Waiter</option>
                    <option value="cashier" {{ old('role')==='cashier'?'selected':'' }}>Cashier</option>
                  </select>
                </div>

                <div>
                  <label class="block text-sm font-medium mb-1">Status</label>
                  <select name="is_active" required
                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
                    <option value="1" {{ old('is_active','1')==='1'?'selected':'' }}>Active</option>
                    <option value="0" {{ old('is_active')==='0'?'selected':'' }}>Inactive</option>
                  </select>
                </div>

                <div class="md:col-span-2">
                  <label class="block text-sm font-medium mb-1">Foto (opsional)</label>
                  <input type="file" name="image" accept="image/*"
                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200" />
                </div>

                <div class="md:col-span-2 flex items-center gap-3 pt-2">
                  <button type="submit"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-md">
                    Simpan
                  </button>
                  <a href="{{ route('staff.index', request()->except('add')) }}"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-semibold rounded-md">
                    Batal
                  </a>
                </div>
              </form>
            </div>
          </div>
        </div>
        @endif

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900 dark:text-gray-100">
            <!-- UBAH: Header dengan Search -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
              <div>
                <h3 class="text-lg font-semibold mb-1">Daftar Staff</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                  Total: {{ $staffs->total() }} staff
                </p>
              </div>

              <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full sm:w-auto">
                <!-- Search Form -->
                <form method="GET" action="{{ route('staff.index') }}" class="flex-1 sm:flex-initial sm:min-w-[300px]">
                  <div class="relative flex gap-2">
                    <div class="relative flex-1">
                      <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama atau email..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                      </div>
                    </div>

                    <!-- Tombol Search -->
                    <button type="submit"
                      class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition inline-flex items-center">
                      <svg class="h-5 w-5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                      </svg>
                      <span class="hidden sm:inline">Cari</span>
                    </button>

                    @if(request('search'))
                    <!-- Tombol Reset -->
                    <a href="{{ route('staff.index') }}"
                      class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-lg transition inline-flex items-center">
                      <svg class="h-5 w-5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                      <span class="hidden sm:inline">Reset</span>
                    </a>
                    @endif
                  </div>
                </form>

                @if(Auth::user()->user_type === 'admin')
                <!-- Tombol Tambah Staff -->
                <a href="{{ route('staff.index', array_merge(request()->query(), ['add' => 1])) }}"
                  class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg whitespace-nowrap">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                  </svg>
                  <span class="hidden sm:inline">Tambah Staff</span>
                  <span class="sm:hidden">Tambah</span>
                </a>
                @endif
              </div>
            </div>

            <!-- TAMBAHKAN: Search Result Info -->
            @if(request('search'))
            <div class="mb-4 px-4 py-2 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
              <p class="text-sm text-blue-700 dark:text-blue-300">
                Menampilkan hasil pencarian untuk: <strong>"{{ request('search') }}"</strong>
                <span class="ml-2 text-blue-600 dark:text-blue-400">({{ $staffs->total() }} staff ditemukan)</span>
              </p>
            </div>
            @endif

            <!-- Modal Tambah Staff (existing code) -->
            @if($openAdd && Auth::user()->user_type === 'admin')
            <!-- ...existing modal code... -->
            @endif

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
                      <div class="flex items-center gap-3">
                        <a href="{{ route('staff.show', $staff) }}"
                          class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                          Detail
                        </a>
                        @if(Auth::user()->user_type === 'admin')
                        <a href="{{ route('staff.edit', $staff) }}"
                          class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300">
                          Edit
                        </a>

                        <form action="{{ route('staff.destroy', $staff) }}" method="POST"
                          onsubmit="return confirm('Yakin ingin menghapus staff {{ $staff->name }}?')">
                          @csrf
                          @method('DELETE')
                          <button type="submit"
                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                            Hapus
                          </button>
                        </form>
                        @endif
                      </div>
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
  </div>
</x-app-layout>