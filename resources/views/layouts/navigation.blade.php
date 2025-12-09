<nav x-data="{ open: false }" class="h-full flex flex-col justify-between bg-[#0F3D3E] text-white">
    @php
    $defaultAvatar = 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&size=64&background=6366f1&color=fff';
    $avatar = Auth::user()->image ? asset('storage/' . Auth::user()->image) : $defaultAvatar;

    $rawRole = strtolower(Auth::user()->user_type === 'staff'
    ? (optional(Auth::user()->staffDetail)->role ?? 'staff')
    : Auth::user()->user_type);

    $roleMap = [
    'admin' => ['Admin', 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'],
    'chef' => ['Chef', 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200'],
    'waiter' => ['Waiter', 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200'],
    'cashier' => ['Cashier', 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'],
    'customer' => ['Customer', 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'],
    ];
    [$roleLabel, $roleClass] = $roleMap[$rawRole] ?? ['User', 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200'];
    @endphp

    {{-- TOP SECTION --}}
    <div>
        {{-- Logo --}}
        <div class="px-6 py-6">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                <x-application-logo class="h-10 w-auto fill-current text-white" />
                <span class="text-lg font-semibold tracking-wide">
                    {{ config('app.name', 'App') }}
                </span>
            </a>
        </div>

        {{-- MENU LIST --}}
        <ul class="mt-4 space-y-1 px-4">

            {{-- Helper to detect active --}}
            @php
                function isActive($route) {
                    return request()->routeIs($route) ? 'bg-white/10 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white';
                }
            @endphp

            {{-- Dashboard --}}
            <li>
                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ isActive('dashboard') }}">
                    <span class="material-icons text-lg">Dashboard</span>
                </a>
            </li>

            {{-- Menu Items --}}
            <li>
                <a href="{{ route('menu-items.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ isActive('menu-items.*') }}">
                    <span class="material-icons text-lg">Menu</span>
                </a>
            </li>

            {{-- Cart --}}
            <li>
                <a href="{{ route('cart.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ isActive('cart.*') }}">
                    <span class="material-icons text-lg">keranjang</span>
                </a>
            </li>

            {{-- Tracking (Customer only) --}}
            @if(Auth::user()->user_type === 'customer')
            <li>
                <a href="{{ route('orders.track') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ isActive('orders.track') }}">
                    <span class="material-icons text-lg">Lacak pesanan</span>
                </a>
            </li>
            @endif

            {{-- Staff (Admin only) --}}
            @if(Auth::user()->user_type === "admin")
            <li>
                <a href="{{ route('staff.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ isActive('staff.*') }}">
                    <span class="material-icons text-lg">Staff</span>
                </a>
            </li>
            @endif

            {{-- Customer (Admin only) --}}
            @if(Auth::user()->user_type === "admin")
            <li>
                <a href="{{ route('customer.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ isActive('customer.*') }}">
                    <span class="material-icons text-lg">Customer</span>
                </a>
            </li>
            @endif

            {{-- Inventory --}}
            @if(in_array(Auth::user()->user_type, ['admin', 'staff']))
            <li>
                <a href="{{ route('inventory.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ isActive(['inventory.*','stockMovement.*']) }}">
                    <span class="material-icons text-lg">Inventory</span>
                </a>
            </li>
            @endif

            {{-- Schedule --}}
            @if(in_array(Auth::user()->user_type, ['admin','staff']))
            <li>
                <a href="{{ route('schedules.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ isActive('schedules.*') }}">
                    <span class="material-icons text-lg">Schedule</span>
                </a>
            </li>
            @endif

            {{-- Reservations --}}
            @if(
                Auth::user()->user_type === 'admin' ||
                (Auth::user()->user_type === 'staff' && optional(Auth::user()->staffDetail)->role === 'cashier') ||
                Auth::user()->user_type === 'customer'
            )
            <li>
                <a href="{{ route('reservations.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ isActive('reservations.*') }}">
                    <span class="material-icons text-lg">Reservations</span>
                </a>
            </li>
            @endif

            {{-- Tables & Orders --}}
            @if(
                Auth::user()->user_type === 'admin' ||
                (Auth::user()->user_type === 'staff' && optional(Auth::user()->staffDetail)->role === 'cashier')
            )
            <li>
                <a href="{{ route('tables.grid') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ isActive('tables.*') }}">
                    <span class="material-icons text-lg">Tables</span>
                </a>
            </li>

            <li>
                <a href="{{ route('orders.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ isActive('orders.*') }}">
                    <span class="material-icons text-lg">Orders</span>
                </a>
            </li>
            @endif
        </ul>
    </div>

    {{-- BOTTOM PROFILE AREA --}}
    <div class="border-t border-white/20 px-4 py-6 flex items-center gap-3">
        <img src="{{ $avatar }}" class="h-10 w-10 rounded-full border border-white/30 object-cover" />

        <div>
            <div class="font-semibold">{{ Auth::user()->name }}</div>
            <div class="text-xs text-white/70">{{ $roleLabel }}</div>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit"
                        class="text-xs text-white/60 hover:text-white transition">
                    Logout
                </button>
            </form>
        </div>
    </div>
</nav>