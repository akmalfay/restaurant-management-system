<x-app-layout>
  <x-slot name="header">
    <div class="flex items-center justify-between">
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Profil Saya</h2>
    </div>
  </x-slot>

  <div class="py-8">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
      <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
        <div class="flex items-center gap-4">
          <img src="{{ $user->image ?? asset('images/default-avatar.png') }}" class="h-20 w-20 rounded-full object-cover border" />
          <div>
            <div class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ $user->name }}</div>
            <div class="text-sm text-gray-600 dark:text-gray-300">{{ $user->email }}</div>
            @if(!empty($user->phone))<div class="text-sm text-gray-600 dark:text-gray-300">{{ $user->phone }}</div>@endif
            @if(!empty($user->address))<div class="text-sm text-gray-600 dark:text-gray-300">{{ $user->address }}</div>@endif
            <div class="mt-2">
              <a href="{{ route('profile.edit') }}" class="px-3 py-1 text-sm bg-indigo-600 text-white rounded">Edit Profil</a>
            </div>
          </div>

          <div class="ml-auto text-right">
            <div class="text-xs text-gray-500">Tipe Akun</div>
            <div class="font-medium">{{ ucfirst($user->user_type) }}</div>
          </div>
        </div>
      </div>

      <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
        <div class="flex items-center justify-between mb-3">
          <div class="font-semibold text-gray-800 dark:text-gray-200">Poin Loyalti</div>
          <div class="text-sm text-gray-500">Terakhir diperbarui: {{ optional(optional($customer)->updated_at)->diffForHumans() ?? '-' }}</div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
          <div class="p-4 border rounded text-center">
            <div class="text-xs text-gray-500">Total Poin</div>
            <div class="text-2xl font-bold text-indigo-600">{{ optional($customer)->points ?? 0 }}</div>
          </div>

          <div class="p-4 border rounded col-span-2">
            <div class="text-sm text-gray-700 dark:text-gray-200 font-medium mb-2">Riwayat Poin</div>
            @if($loyaltyPoints && $loyaltyPoints->count() > 0)
            <div class="space-y-2 max-h-48 overflow-y-auto">
              @foreach($loyaltyPoints as $lp)
              <div class="flex items-start justify-between border-b py-2">
                <div>
                  <div class="text-sm font-semibold">{{ $lp->description ?? ucfirst($lp->type) }}</div>
                  <div class="text-xs text-gray-500">{{ $lp->created_at->format('d M Y H:i') }}</div>
                </div>
                <div class="text-right">
                  <div class="text-sm font-medium {{ $lp->points > 0 ? 'text-emerald-600' : 'text-red-600' }}">{{ $lp->points > 0 ? '+' . $lp->points : $lp->points }}</div>
                </div>
              </div>
              @endforeach
            </div>
            @else
            <div class="text-sm text-gray-500">Belum ada aktivitas poin.</div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
