{{-- MODIFIED: Added z-50 class to ensure the sidebar is on top of all other content --}}
<div
  x-show="sidebarOpen"
  class="relative z-50 lg:hidden"
  x-cloak
>
  <div
    x-show="sidebarOpen"
    x-transition:enter="transition-opacity ease-linear duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 bg-black/80"
  ></div>

  <div class="fixed inset-0 flex">
    <div
      x-show="sidebarOpen"
      x-transition:enter="transition ease-in-out duration-300 transform"
      x-transition:enter-start="-translate-x-full"
      x-transition:enter-end="translate-x-0"
      x-transition:leave="transition ease-in-out duration-300 transform"
      x-transition:leave-start="translate-x-0"
      x-transition:leave-end="-translate-x-full"
      class="relative flex w-full flex-1"
    >
      @include('components.admin.partials.sidebar-content')
    </div>
  </div>
</div>

<div class="hidden lg:fixed lg:inset-y-0 lg:z-40 lg:flex lg:w-64 lg:flex-col bg-black">
  @include('components.admin.partials.sidebar-content', ['isMobile' => false])
</div>

<div class="hidden lg:block lg:w-64 lg:flex-shrink-0"></div>
