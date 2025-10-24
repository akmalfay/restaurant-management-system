<x-app-layout>
  <x-slot name="header">
    <div class="flex justify-between items-center">
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Edit Customer') }}
      </h2>
      <a href="{{ route('customer.show', $user) }}"
        class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
        ← Kembali
      </a>
    </div>
  </x-slot>

  <div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
      @if(session('error'))
      <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 rounded">
        {{ session('error') }}
      </div>
      @endif

      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
          <form action="{{ route('customer.update', $user) }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
              <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                Informasi Customer
              </h3>
              <div class="space-y-3">
                <div>
                  <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Poin</label>
                  <p class="text-xl font-bold text-yellow-600 dark:text-yellow-400">
                    {{ number_format($user->customerDetail?->points ?? 0) }} pts
                  </p>
                </div>
              </div>
            </div>

            <div class="mb-6">
              <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Nama
              </label>
              <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" />
              @error('name')
              <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
              @enderror
            </div>

            <div class="mb-6">
              <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Email
              </label>
              <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" />
              @error('email')
              <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
              @enderror
            </div>

            <div class="flex items-center gap-4">
              <button type="submit"
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-md">
                Simpan
              </button>
              <a href="{{ route('customer.show', $user) }}"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-md">
                Batal
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>