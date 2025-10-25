{{-- filepath: c:\Workspace\pemrograman-web\resources\views\menu-items\create.blade.php --}}
<x-app-layout>
  <x-slot name="header">
    <div class="flex justify-between items-center">
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Tambah Menu Baru
      </h2>
      <a href="{{ route('menu-items.index') }}"
        class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
        ← Kembali ke Menu
      </a>
    </div>
  </x-slot>

  <div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
          <form action="{{ route('menu-items.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Name --}}
            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Nama Menu <span class="text-red-500">*</span>
              </label>
              <input type="text" name="name" value="{{ old('name') }}" required
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white @error('name') @enderror">
              @error('name')
              <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
              @enderror
            </div>

            {{-- Description --}}
            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Deskripsi
              </label>
              <textarea name="description" rows="4"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white @error('description') @enderror">{{ old('description') }}</textarea>
              @error('description')
              <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
              @enderror
            </div>

            {{-- Category --}}
            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Kategori <span class="text-red-500">*</span>
              </label>
              <select name="category_id" required
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white @error('category_id') @enderror">
                <option value="">-- Pilih Kategori --</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                  {{ $category->name }}
                </option>
                @endforeach
              </select>
              @error('category_id')
              <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
              @enderror
            </div>

            {{-- Price --}}
            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Harga (Rp) <span class="text-red-500">*</span>
              </label>
              <input type="number" name="price" value="{{ old('price') }}" required min="0" step="0.01"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white @error('price') @enderror">
              @error('price')
              <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
              @enderror
            </div>

            {{-- Image --}}
            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Gambar Menu
              </label>
              <input type="file" name="image" accept="image/*"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white @error('image') @enderror">
              <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Max: 2MB (JPG, PNG, GIF)</p>
              @error('image')
              <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
              @enderror
            </div>

            {{-- Availability --}}
            <div class="mb-6">
              <label class="flex items-center">
                <input type="checkbox" name="is_available" value="1" {{ old('is_available', true) ? 'checked' : '' }}
                  class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600">
                <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                  Menu tersedia untuk dijual
                </span>
              </label>
            </div>

            {{-- Buttons --}}
            <div class="flex gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
              <button type="submit"
                class="flex-1 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-md transition">
                Tambah Menu
              </button>
              <a href="{{ route('menu-items.index') }}"
                class="flex-1 px-6 py-3 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold rounded-md transition text-center">
                Batal
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>