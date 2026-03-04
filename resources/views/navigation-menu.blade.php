<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 w-full gap-2 sm:gap-4">
            <div class="flex flex-1 min-w-0">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" wire:navigate>
                        <x-application-mark class="block h-9 w-auto" />
                    </a>
                    <!-- Dark Mode Toggle -->
                    <div class="ms-3 relative">
                        <x-dark-mode-toggle />
                    </div>
                    @include('livewire.Inventoryfolder.tombol-full-screen')
                </div>

                <!-- Navigation Links -->
                <div class="hidden sm:-my-px sm:ms-4 sm:flex sm:flex-1 sm:overflow-x-auto sm:space-x-4 lg:space-x-8 lg:ms-8 lg:overflow-visible [&::-webkit-scrollbar]:hidden"
                    style="scrollbar-width: none; -ms-overflow-style: none;">
                    <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link href="{{ route('inventory') }}" :active="request()->routeIs('inventory')">
                        {{ __('Inventory') }}
                    </x-nav-link>
                    <x-nav-link href="{{ route('activity-log') }}" :active="request()->routeIs('activity-log')">
                        {{ __('Activity Log') }}
                    </x-nav-link>
                    <x-nav-link href="{{ route('mutasi-stok') }}"
                        :active="request()->request->get('routeIs') == 'mutasi-stok'">
                        {{ __('Mutasi Stok') }}
                    </x-nav-link>
                    @if (Auth::user()->currentTeam && (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin') || Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'finance') || \App\Models\AkunKas::where('user_id', Auth::id())->exists()))
                        @php
                            $user = Auth::user();
                            $isAdminOrFinance = $user->hasTeamRole($user->currentTeam, 'admin') || $user->hasTeamRole($user->currentTeam, 'finance');
                            $pendingCount = \App\Models\TransferKas::where('status', 'pending')
                                ->when(!$isAdminOrFinance, function ($q) use ($user) {
                                    $q->whereIn('penerima_akun_id', \App\Models\AkunKas::where('user_id', $user->id)->pluck('id'));
                                })->count();
                        @endphp
                        <x-nav-link href="{{ route('keuangan') }}" :active="request()->routeIs('keuangan')"
                            class="relative">
                            {{ __('Keuangan') }}
                            @if($pendingCount > 0)
                                <span class="absolute top-2 -right-1 flex h-2 w-2">
                                    <span
                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                                </span>
                            @endif
                        </x-nav-link>
                    @endif
                    <x-nav-link href="{{ route('panduan') }}" :active="request()->routeIs('panduan')">
                        {{ __('Panduan') }}
                    </x-nav-link>
                    <!-- Admin Only Links -->
                    @if (Auth::user()->currentTeam && Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin'))
                        <x-nav-link href="{{ route('user-management') }}" :active="request()->routeIs('user-management')">
                            {{ __('User Management') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-2 lg:ms-6 shrink-0">


                <!-- Teams Dropdown -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="ms-3 relative">
                        <x-dropdown align="right" width="60">
                            <x-slot name="trigger">
                                <span class="inline-flex rounded-md">
                                    <button type="button"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150 whitespace-nowrap shrink-0">
                                        {{ Auth::user()->currentTeam->name }}

                                        <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    </button>
                                </span>
                            </x-slot>

                            <x-slot name="content">
                                <div class="w-60">
                                    <!-- Team Management -->
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        {{ __('Manage Team') }}
                                    </div>

                                    <!-- Team/User Management -->
                                    @if(Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin'))
                                        <x-dropdown-link href="{{ route('user-management') }}">
                                            {{ __('Manajemen Tim (Staf & Akses)') }}
                                        </x-dropdown-link>
                                    @endif

                                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                        <x-dropdown-link href="{{ route('teams.create') }}">
                                            {{ __('Create New Team') }}
                                        </x-dropdown-link>
                                    @endcan

                                    <!-- Team Switcher -->
                                    @if (Auth::user()->allTeams()->count() > 1)
                                        <div class="border-t border-gray-200 dark:border-gray-600"></div>

                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            {{ __('Switch Teams') }}
                                        </div>

                                        @foreach (Auth::user()->allTeams() as $team)
                                            <x-switchable-team :team="$team" />
                                        @endforeach
                                    @endif
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endif

                <!-- Settings Dropdown -->
                <div class="ms-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button
                                    class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                    <img class="size-8 rounded-full object-cover"
                                        src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                </button>
                            @else
                                <span class="inline-flex rounded-md">
                                    <button type="button"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150 whitespace-nowrap shrink-0">
                                        {{ Auth::user()->name }}

                                        <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                </span>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Manage Account') }}
                            </div>

                            <x-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                    {{ __('API Tokens') }}
                                </x-dropdown-link>
                            @endif

                            <div class="border-t border-gray-200 dark:border-gray-600"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf

                                <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden shrink-0">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link href="{{ route('inventory') }}" :active="request()->routeIs('inventory')">
                {{ __('Inventory') }}
            </x-responsive-nav-link>
        </div>
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link href="{{ route('activity-log') }}" :active="request()->routeIs('activity-log')">
                {{ __('Activity Log') }}
            </x-responsive-nav-link>
        </div>
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link href="{{ route('mutasi-stok') }}" :active="request()->routeIs('mutasi-stok')">
                {{ __('Mutasi Stok') }}
            </x-responsive-nav-link>
        </div>
        @if (Auth::user()->currentTeam && (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin') || Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'finance') || \App\Models\AkunKas::where('user_id', Auth::id())->exists()))
            @php
                $user = Auth::user();
                $isAdminOrFinance = $user->hasTeamRole($user->currentTeam, 'admin') || $user->hasTeamRole($user->currentTeam, 'finance');
                $pendingCount = \App\Models\TransferKas::where('status', 'pending')
                    ->when(!$isAdminOrFinance, function ($q) use ($user) {
                        $q->whereIn('penerima_akun_id', \App\Models\AkunKas::where('user_id', $user->id)->pluck('id'));
                    })->count();
            @endphp
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link href="{{ route('keuangan') }}" :active="request()->routeIs('keuangan')"
                    class="flex items-center justify-between">
                    <span>{{ __('Keuangan') }}</span>
                    @if($pendingCount > 0)
                        <span
                            class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-rose-600 rounded-full">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </x-responsive-nav-link>
            </div>
        @endif
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link href="{{ route('panduan') }}" :active="request()->routeIs('panduan')">
                {{ __('Panduan') }}
            </x-responsive-nav-link>
        </div>
        @if (Auth::user()->currentTeam && Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin'))
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link href="{{ route('user-management') }}"
                    :active="request()->routeIs('user-management')">
                    {{ __('User Management') }}
                </x-responsive-nav-link>
            </div>
        @endif

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="flex items-center px-4">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div class="shrink-0 me-3">
                        <img class="size-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}"
                            alt="{{ Auth::user()->name }}" />
                    </div>
                @endif

                <div>
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Account Management -->
                <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <x-responsive-nav-link href="{{ route('api-tokens.index') }}"
                        :active="request()->routeIs('api-tokens.index')">
                        {{ __('API Tokens') }}
                    </x-responsive-nav-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf

                    <x-responsive-nav-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>

                <!-- Team Management -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="border-t border-gray-200 dark:border-gray-600"></div>

                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ __('Manage Team') }}
                    </div>

                    <!-- Team/User Management -->
                    @if(Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin'))
                        <x-responsive-nav-link href="{{ route('user-management') }}"
                            :active="request()->routeIs('user-management')">
                            {{ __('Manajemen Tim (Staf & Akses)') }}
                        </x-responsive-nav-link>
                    @endif

                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                        <x-responsive-nav-link href="{{ route('teams.create') }}" :active="request()->routeIs('teams.create')">
                            {{ __('Create New Team') }}
                        </x-responsive-nav-link>
                    @endcan

                    <!-- Team Switcher -->
                    @if (Auth::user()->allTeams()->count() > 1)
                        <div class="border-t border-gray-200 dark:border-gray-600"></div>

                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('Switch Teams') }}
                        </div>

                        @foreach (Auth::user()->allTeams() as $team)
                            <x-switchable-team :team="$team" component="responsive-nav-link" />
                        @endforeach
                    @endif
                @endif
            </div>
        </div>
    </div>
</nav>