<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    @php
    $defaultAvatar = 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&size=64&background=6366f1&color=fff';
    $avatar = Auth::user()->image ? asset('storage/' . Auth::user()->image) : $defaultAvatar;

    // Tentukan role label + warna
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
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @if(Auth::user()->user_type === "admin")
                    <x-nav-link :href="route('staff.index')" :active="request()->routeIs('staff.*')">
                        {{ __('Staff') }}
                    </x-nav-link>
                    @endif

                    @if(Auth::user()->user_type === 'admin')
                    <x-nav-link :href="route('customer.index')" :active="request()->routeIs('customer.*')">
                        {{ __('Customer') }}
                    </x-nav-link>
                    @endif

                    @if(in_array(Auth::user()->user_type, ['admin', 'staff']))
                    <x-nav-link
                        :href="route('inventory.index')"
                        :active="request()->routeIs(['inventory.*', 'stockMovement.*'])">
                        {{ __('Inventory') }}
                    </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150 gap-3">
                            <!-- Role -->
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $roleClass }}">{{ $roleLabel }}</span>
                            <!-- Avatar -->
                            <img src="{{ $avatar }}" alt="{{ Auth::user()->name }}"
                                class="h-10 w-10 rounded-full object-cover border border-gray-200 dark:border-gray-700"
                                onerror="this.onerror=null;this.src='{{ $defaultAvatar }}';">
                            <!-- Name -->
                            <div class="font-medium text-base text-gray-800 dark:text-gray-200 truncate">
                                {{ Auth::user()->name }}
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @if(Auth::user()->user_type === 'admin')
            <x-responsive-nav-link :href="route('staff.index')" :active="request()->routeIs('staff.*')">
                {{ __('Staff') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('customer.index')" :active="request()->routeIs('customer.*')">
                {{ __('Customer') }}
            </x-responsive-nav-link>
            @endif

            @if(in_array(Auth::user()->user_type, ['admin', 'staff']))
            <x-responsive-nav-link
                :href="route('inventory.index')"
                :active="request()->routeIs(['inventory.*', 'stockMovement.*'])">
                {{ __('Inventory') }}
            </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="flex items-center gap-2">
                    <!-- Role -->
                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $roleClass }}">{{ $roleLabel }}</span>
                    <!-- Avatar -->
                    <img src="{{ $avatar }}" alt="{{ Auth::user()->name }}"
                        class="h-10 w-10 rounded-full object-cover border border-gray-200 dark:border-gray-700"
                        onerror="this.onerror=null;this.src='{{ $defaultAvatar }}';">
                    <!-- Name -->
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200 truncate">
                        {{ Auth::user()->name }}
                    </div>
                </div>
                <div class="mt-1 ms-14 font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>