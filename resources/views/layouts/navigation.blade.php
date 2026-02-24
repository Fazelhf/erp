<nav x-data="{ open: false }"
     class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm">

    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <!-- Left -->
            <div class="flex items-center space-x-8">

                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                        <x-application-logo class="block h-8 w-auto fill-current text-gray-800 dark:text-gray-200" />
                        <span class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                            ERP
                        </span>
                    </a>
                </div>

                <div class="hidden sm:flex sm:items-center space-x-6">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        داشبورد
                    </x-nav-link>
                </div>
            </div>

            <!-- Right -->
            <div class="hidden sm:flex sm:items-center space-x-4">

                <!-- User Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center space-x-3 bg-gray-50 dark:bg-gray-700 px-3 py-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-600 transition">

                            <div class="w-8 h-8 rounded-full bg-indigo-500 text-white flex items-center justify-center font-semibold text-sm">
                                {{ strtoupper(substr(Auth::user()->name,0,1)) }}
                            </div>

                            <div class="text-sm text-gray-700 dark:text-gray-200">
                                {{ Auth::user()->name }}
                            </div>

                            <svg class="fill-current h-4 w-4 text-gray-500"
                                 xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            پروفایل
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                خروج
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>

            </div>

            <!-- Hamburger -->
            <div class="flex items-center sm:hidden">
                <button @click="open = ! open"
                        class="p-2 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }"
                              class="inline-flex"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }"
                              class="hidden"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <!-- Mobile -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">

        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                داشبورد
            </x-responsive-nav-link>
        </div>

        <div class="pt-4 pb-3 border-t border-gray-200 dark:border-gray-600 px-4">
            <div class="font-medium text-base text-gray-800 dark:text-gray-200">
                {{ Auth::user()->name }}
            </div>
            <div class="font-medium text-sm text-gray-500">
                {{ Auth::user()->email }}
            </div>
        </div>

    </div>
</nav>