<header class="flex items-center justify-between px-6 py-3 bg-white border-b border-gray-200 shadow-sm">
  <div class="flex items-center">
    <button
      @click="sidebarOpen = !sidebarOpen"
      class="text-gray-500 focus:outline-none lg:hidden"
    >
      <svg
        class="w-6 h-6"
        viewBox="0 0 24 24"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          d="M4 6H20M4 12H20M4 18H11"
          stroke="currentColor"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
        ></path>
      </svg>
    </button>
    <div class="relative ml-4">
      <span class="text-sm text-gray-500">Your Last Login: 18 Jun, 2025, 02:20 PM</span>
    </div>
  </div>

  <div class="hidden lg:flex items-center space-x-3">
    {{-- MODIFIED: Changed button colors --}}
    <a
      href="#"
      class="px-4 py-2 text-sm font-medium text-white bg-purple-600 border border-transparent rounded-md shadow-sm hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors"
    >
      Home
    </a>
    <form
      method="POST"
      action="{{ route('admin.logout') }}"
    >
      @csrf
      <button
        type="submit"
        class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
      >
        Log Out
      </button>
    </form>
  </div>

  <div
    x-data="{ dropdownOpen: false }"
    class="relative lg:hidden"
  >
    <button
      @click="dropdownOpen = !dropdownOpen"
      class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-200 text-gray-600 focus:outline-none"
    >
      <span class="text-sm font-bold">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
    </button>

    <div
      x-show="dropdownOpen"
      @click.away="dropdownOpen = false"
      x-transition
      class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50"
      x-cloak
    >
      {{-- MODIFIED: Changed button colors (text color for dropdown items) --}}
      <a
        href="#"
        class="block px-4 py-2 text-sm text-purple-700 hover:bg-gray-100"
      >
        Home
      </a>
      <form
        method="POST"
        action="{{ route('admin.logout') }}"
      >
        @csrf
        <button
          type="submit"
          class="w-full text-left block px-4 py-2 text-sm text-red-700 hover:bg-gray-100"
        >
          Log Out
        </button>
      </form>
    </div>
  </div>
</header>
