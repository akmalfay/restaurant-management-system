<x-app-layout>
  <x-slot name="header">
    <div class="flex justify-between items-center">
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Detail Staff') }}
      </h2>
      <a href="{{ route('staff.index') }}"
        class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
        ← Kembali
      </a>
    </div>
  </x-slot>

  <div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
          <div class="flex items-start gap-6">
            <!-- Profile Image -->
            <div class="">
              @php
              $defaultAvatar = 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=256&background=6366f1&color=fff';
              @endphp
              <img class="h-64 w-64 rounded-full object-cover border-4 border-gray-200 dark:border-gray-700"
                style="max-width: 256px; max-height: 256px;"
                src="{{ $user->image ? asset('storage/' . $user->image) : $defaultAvatar }}"
                alt="{{ $user->name }}">
            </div>

            <!-- User Info -->
            <div class="flex-1 px-6 flex flex-col gap-4">
              <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama</label>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                  {{ $user->name }}
                </h3>
              </div>

              <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</label>
                <p class="text-gray-600 dark:text-gray-400 mt-1">
                  {{ $user->email }}
                </p>
              </div>

              <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Telepon</label>
                <p class="text-gray-600 dark:text-gray-400 mt-1">
                  {{ $user->phone ?? '-' }}
                </p>
              </div>

              <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Alamat</label>
                <p class="text-gray-600 dark:text-gray-400 mt-1">
                  {{ $user->address ?? '-' }}
                </p>
              </div>

              <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Role -->
                <div>
                  <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Role</label>
                  <div class="mt-1">
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                      @if($user->staffDetail?->role === 'chef') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                      @elseif($user->staffDetail?->role === 'waiter') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                      @else bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                      @endif">
                      {{ ucfirst($user->staffDetail?->role ?? 'N/A') }}
                    </span>
                  </div>
                </div>

                <!-- Status -->
                <div>
                  <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                  <div class="mt-1">
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                      @if($user->staffDetail?->is_active) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                      @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                      @endif">
                      {{ $user->staffDetail?->is_active ? 'Active' : 'Inactive' }}
                    </span>
                  </div>
                </div>

                <!-- Joined Date -->
                <div>
                  <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Bergabung</label>
                  <p class="mt-1 text-gray-900 dark:text-gray-100">
                    {{ $user->staffDetail?->joined_at?->format('d F Y') ?? '-' }}
                  </p>
                </div>

                <!-- Left Date -->
                <div>
                  <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Keluar</label>
                  <p class="mt-1 text-gray-900 dark:text-gray-100">
                    {{ $user->staffDetail?->left_at?->format('d F Y') ?? '-' }}
                  </p>
                </div>

                <!-- Email Verified -->
                <div>
                  <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Email Verified</label>
                  <p class="mt-1 text-gray-900 dark:text-gray-100">
                    {{ $user->email_verified_at ? $user->email_verified_at->format('d F Y') : 'Belum diverifikasi' }}
                  </p>
                </div>

                <!-- Member Since -->
                <div>
                  <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Terdaftar Sejak</label>
                  <p class="mt-1 text-gray-900 dark:text-gray-100">
                    {{ $user->created_at->format('d F Y') }}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    @if(Auth::user()->user_type === 'admin')
    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700 flex gap-4 justify-between max-w-4xl mx-auto sm:px-6 lg:px-8">
      <a href="{{ route('staff.edit', $user) }}"
        class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold rounded-md">
        Edit
      </a>

      <form action="{{ route('staff.destroy', $user) }}" method="POST"
        onsubmit="return confirm('Yakin ingin menghapus staff {{ $user->name }}?')">
        @csrf
        @method('DELETE')
        <button type="submit"
          class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-md">
          Hapus
        </button>
      </form>
    </div>
    @endif
  </div>
</x-app-layout>