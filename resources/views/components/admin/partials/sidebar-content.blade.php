@props(['isMobile' => true])

<div class="flex grow flex-col gap-y-5 bg-black px-6 pb-4">
  <div class="flex h-16 shrink-0 items-center justify-between">
    <div class="flex flex-col">
      <h1 class="text-xl font-bold tracking-wider text-white">MOIST PIXELS</h1>
      <span class="text-xs text-gray-400 -mt-1">content management system</span>
    </div>

    @if ($isMobile)
      <button
        type="button"
        class="-m-2.5 p-2.5 text-gray-400 hover:text-white"
        @click="sidebarOpen = false"
      >
        <span class="sr-only">Close sidebar</span>
        <svg
          class="h-6 w-6"
          fill="none"
          viewBox="0 0 24 24"
          stroke-width="1.5"
          stroke="currentColor"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M6 18L18 6M6 6l12 12"
          />
        </svg>
      </button>
    @endif
  </div>

  <nav class="flex flex-1 flex-col">
    <ul
      role="list"
      class="flex flex-1 flex-col gap-y-7"
    >
      <li>
        <ul
          role="list"
          class="-mx-2 space-y-1"
        >
          {{-- Dashboard Link (Everyone can see) --}}
          <li>
            <a
              href="{{ route('admin.dashboard') }}"
              class="{{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold items-center"
            >
              <svg
                class="h-6 w-6 shrink-0"
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
              <span class="truncate">Dashboard</span>
            </a>
          </li>

          {{-- Access Control Dropdown (Visible if user has AT LEAST ONE of the sub-permissions) --}}
          @canany(['users.view', 'roles.view', 'permissions.view'])
            @php
              $isAccessControlActive = request()->routeIs('admin.users.*', 'admin.roles.*', 'admin.permissions.*');
            @endphp

            <li x-data="{ open: {{ $isAccessControlActive ? 'true' : 'false' }} }">
              <button
                @click="open = !open"
                class="{{ $isAccessControlActive ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} group flex items-center w-full text-left gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold"
              >
                <svg
                  class="h-6 w-6 shrink-0"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.6-3.752A11.959 11.959 0 0118 6c0 2.707-1.126 5.156-2.956 6.84A6.978 6.978 0 0112 15a6.978 6.978 0 01-3.044-1.16A11.958 11.958 0 019 6.014z"
                  />
                </svg>
                Access Control
                <svg
                  class="ml-auto h-5 w-5 shrink-0 transition-transform duration-200"
                  :class="{ 'rotate-90': open }"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M8.25 4.5l7.5 7.5-7.5 7.5"
                  />
                </svg>
              </button>

              <ul
                x-show="open"
                x-transition
                class="mt-1 px-2 space-y-1"
              >
                {{-- Users Link (Visible if user has 'users.view' permission) --}}
                @can('users.view')
                  <li>
                    <a
                      href="{{ route('admin.users.index') }}"
                      class="{{ request()->routeIs('admin.users.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold items-center"
                    >
                      <span class="w-6 h-6 shrink-0"></span>
                      {{-- Spacer for alignment --}}
                      Users
                    </a>
                  </li>
                @endcan

                {{-- Roles Link (Visible if user has 'roles.view' permission) --}}
                @can('roles.view')
                  <li>
                    <a
                      href="{{ route('admin.roles.index') }}"
                      class="{{ request()->routeIs('admin.roles.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold items-center"
                    >
                      <span class="w-6 h-6 shrink-0"></span>
                      {{-- Spacer for alignment --}}
                      Roles
                    </a>
                  </li>
                @endcan

                {{-- Permissions Link (Visible if user has 'permissions.view' permission) --}}
                @can('permissions.view')
                  <li>
                    <a
                      href="{{ route('admin.permissions.index') }}"
                      class="{{ request()->routeIs('admin.permissions.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold items-center"
                    >
                      <span class="w-6 h-6 shrink-0"></span>
                      {{-- Spacer for alignment --}}
                      Permissions
                    </a>
                  </li>
                @endcan
              </ul>
            </li>
          @endcanany

          {{-- Other links would follow the same pattern, e.g., @can('pages.view') ... @endcan --}}
        </ul>
      </li>
    </ul>
  </nav>
</div>
