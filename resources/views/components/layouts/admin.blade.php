<!DOCTYPE html>
<html
  lang="{{ str_replace('_', '-', app()->getLocale()) }}"
  class="h-full bg-gray-100"
>
  <head>
    <meta charset="UTF-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0"
    />
    <title>{{ $title ?? 'Admin Dashboard' }} - Moist Pixels</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body class="h-full font-sans antialiased text-gray-800">
    <div
      x-data="{ sidebarOpen: false }"
      class="flex h-screen bg-gray-100"
    >
      <x-admin.sidebar />

      <div class="flex flex-col flex-1 overflow-hidden">
        <x-admin.header />

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
          <div class="container mx-auto">
            {{ $slot }}
          </div>
        </main>
      </div>
    </div>
  </body>
</html>
