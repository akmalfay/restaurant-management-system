<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-[#0F3D3E] leading-tight">
            Dashboard Overview
        </h2>
        <p class="text-sm text-gray-600">Welcome back! Here's what's happening today.</p>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-6">
            {{-- Card simple sesuai permintaan --}}
            <div class="bg-white shadow rounded-xl border border-[#E5E5E5] p-6">
                <div class="text-[#0F3D3E] font-semibold text-lg">
                    You're logged in!
                </div>
                <p class="text-gray-600 text-sm mt-1">
                    Everything looks good. Continue managing your restaurant system.
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
