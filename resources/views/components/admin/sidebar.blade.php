<aside class="w-64 flex-shrink-0 bg-primary text-white flex flex-col">
    {{-- Logo Area --}}
    <div class="h-16 flex items-center justify-center bg-header shadow-md px-4">
        <a
            href="{{ route('admin.dashboard') }}"
            class="text-center"
        >
            <h1 class="text-xl font-bold tracking-wider text-text-button block">MOIST PIXELS</h1>
            <span class="text-xs text-gray-400 block -mt-1">content management system</span>
        </a>
    </div>

    {{-- Menu Links --}}
    <nav class="mt-6 flex-grow space-y-1">
        {{-- Dashboard Link --}}
        <x-admin.nav-link
            :href="route('admin.dashboard')"
            :active="request()->routeIs('admin.dashboard')"
        >
            <svg
                class="h-5 w-5 mr-3"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25a2.25 2.25 0 01-2.25-2.25v-2.25z"
                />
            </svg>
            <span>Dashboard</span>
        </x-admin.nav-link>

        {{-- Access Control Dropdown Menu --}}
        @canany(['users.view', 'roles.view', 'permissions.view'])
            @php
                $isAccessControlActive = request()->routeIs('admin.users.*', 'admin.roles.*', 'admin.permissions.*');
            @endphp

            <div x-data="{ open: {{ $isAccessControlActive ? 'true' : 'false' }} }">
                {{-- Menu Trigger --}}
                <button
                    @click="open = !open"
                    class="w-full flex items-center justify-between px-6 py-3 text-left focus:outline-none transition-colors duration-200 {{ $isAccessControlActive ? 'bg-menu-hover text-white font-semibold' : 'text-gray-300 hover:text-white hover:bg-menu-hover' }}"
                >
                    <span class="flex items-center">
                        <svg
                            class="h-5 w-5 mr-3"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.602-3.751m-14.254-2.318a12.016 12.016 0 0114.254 0"
                            />
                        </svg>
                        <span>Access Control</span>
                    </span>
                    <span x-show="!open">
                        <svg
                            class="h-5 w-5"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M15.75 19.5L8.25 12l7.5-7.5"
                            />
                        </svg>
                    </span>
                    <span
                        x-show="open"
                        style="display: none"
                    >
                        <svg
                            class="h-5 w-5"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M19.5 8.25l-7.5 7.5-7.5-7.5"
                            />
                        </svg>
                    </span>
                </button>

                {{-- Submenu --}}
                <div
                    x-show="open"
                    x-transition
                    class="mt-1 space-y-1"
                >
                    @can('users.view')
                        <x-admin.nav-link
                            :href="route('admin.users.index')"
                            :active="request()->routeIs('admin.users.*')"
                        >
                            {{-- Users Icon --}}
                            <svg
                                class="h-5 w-5 mr-3"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-4.67c.12-.24.232-.483.336-.728M9 12.75a3 3 0 11-6 0 3 3 0 016 0z"
                                />
                            </svg>
                            <span>Manage Users</span>
                        </x-admin.nav-link>
                    @endcan

                    @can('roles.view')
                        <x-admin.nav-link
                            :href="route('admin.roles.index')"
                            :active="request()->routeIs('admin.roles.*')"
                        >
                            {{-- Tags Icon for Roles --}}
                            <svg
                                class="h-5 w-5 mr-3"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"
                                />
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M6 6h.008v.008H6V6z"
                                />
                            </svg>
                            <span>Manage Roles</span>
                        </x-admin.nav-link>
                    @endcan

                    @can('permissions.view')
                        <x-admin.nav-link
                            :href="route('admin.permissions.index')"
                            :active="request()->routeIs('admin.permissions.*')"
                        >
                            {{-- Key Icon for Permissions --}}
                            <svg
                                class="h-5 w-5 mr-3"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"
                                />
                            </svg>
                            <span>Manage Permissions</span>
                        </x-admin.nav-link>
                    @endcan
                </div>
            </div>
        @endcanany
    </nav>
</aside>
