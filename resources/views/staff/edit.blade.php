<x-app-layout>
  <x-slot name="header">
    <div class="flex justify-between items-center">
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Edit Staff') }}
      </h2>
      <a href="{{ route('staff.show', $user) }}"
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
          <form action="{{ route('staff.update', $user) }}" method="POST">
            @csrf
            @method('PATCH')

            <!-- User Info (Read Only) -->
            <div class="mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
              <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                Informasi Staff
              </h3>
              <div class="space-y-3">
                <div>
                  <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama</label>
                  <p class="text-gray-900 dark:text-gray-100">{{ $user->name }}</p>
                </div>
                <div>
                  <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</label>
                  <p class="text-gray-900 dark:text-gray-100">{{ $user->email }}</p>
                </div>
              </div>
            </div>

            <!-- Role -->
            <div class="mb-6">
              <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Role
              </label>
              <select id="role" name="role" required
                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                <option value="chef" {{ $user->staffDetail?->role === 'chef' ? 'selected' : '' }}>Chef</option>
                <option value="waiter" {{ $user->staffDetail?->role === 'waiter' ? 'selected' : '' }}>Waiter</option>
                <option value="cashier" {{ $user->staffDetail?->role === 'cashier' ? 'selected' : '' }}>Cashier</option>
              </select>
              @error('role')
              <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
              @enderror
            </div>

            <!-- Status -->
            <div class="mb-6">
              <label for="is_active" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Status
              </label>
              <select id="is_active" name="is_active" required
                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                <option value="1" {{ $user->staffDetail?->is_active ? 'selected' : '' }}>Active</option>
                <option value="0" {{ !$user->staffDetail?->is_active ? 'selected' : '' }}>Inactive</option>
              </select>
              @error('is_active')
              <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
              @enderror
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-around">
              <a href="{{ route('staff.show', $user) }}"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-md">
                Cancel
              </a>
              <button type="submit"
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 font-semibold text-white rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Save
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>