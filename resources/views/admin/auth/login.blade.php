<!DOCTYPE html>
<html
  lang="{{ str_replace('_', '-', app()->getLocale()) }}"
  class="h-full"
>
  <head>
    <meta charset="UTF-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0"
    />
    <title>Admin Login</title>

    {{-- Include Vite assets for Tailwind CSS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body class="antialiased bg-gray-100 dark:bg-gray-900 flex items-center justify-center min-h-screen p-4">
    <div class="w-full max-w-md mx-auto">
      {{-- Login Card --}}
      <div class="bg-white dark:bg-black rounded-2xl shadow-xl p-8 sm:p-10">
        <div class="text-center mb-8">
          <h1 class="text-3xl font-bold text-black dark:text-white">Admin Panel</h1>
          <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Please sign in to continue</p>
        </div>

        <form
          action="{{ route('admin.login') }}"
          method="POST"
          class="space-y-6"
        >
          @csrf

          {{-- General Error Message Display --}}
          @if ($errors->any())
            <div
              class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-400 dark:border-red-600 text-red-700 dark:text-red-300 p-4 rounded-md"
              role="alert"
            >
              <p class="text-sm">{{ $errors->first() }}</p>
            </div>
          @endif

          {{-- Email Input --}}
          <div>
            <label
              for="email"
              class="sr-only"
            >
              Email address
            </label>
            <input
              id="email"
              name="email"
              type="email"
              autocomplete="off"
              required
              value="{{ old('email') }}"
              placeholder="Email address"
              class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-lg placeholder-gray-500 text-black dark:text-white focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent transition-colors"
            />
          </div>

          {{-- Password Input --}}
          <div>
            <label
              for="password"
              class="sr-only"
            >
              Password
            </label>
            <input
              id="password"
              name="password"
              type="password"
              autocomplete="current-password"
              required
              placeholder="Password"
              class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-lg placeholder-gray-500 text-black dark:text-white focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent transition-colors"
            />
          </div>

          <div class="flex items-center justify-between flex-wrap gap-4">
            {{-- Remember Me Checkbox --}}
            <div class="flex items-center">
              <input
                id="remember"
                name="remember"
                type="checkbox"
                class="h-4 w-4 text-black dark:text-white border-gray-300 dark:border-gray-600 rounded focus:ring-black dark:focus:ring-offset-gray-100 dark:focus:ring-offset-black"
              />
              <label
                for="remember"
                class="ml-2 block text-sm text-gray-700 dark:text-gray-300"
              >
                Remember me
              </label>
            </div>

            {{-- Forgot Password Link --}}
            <div class="text-sm">
              <a
                href="#"
                class="font-medium text-gray-600 dark:text-gray-400 hover:text-black dark:hover:text-white transition-colors"
              >
                Forgot password?
              </a>
            </div>
          </div>

          {{-- Submit Button --}}
          <div>
            <button
              type="submit"
              class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-black dark:text-black dark:bg-white hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black dark:focus:ring-white transition-opacity"
            >
              SIGN IN
            </button>
          </div>
        </form>
      </div>

      <p class="mt-8 text-center text-sm text-gray-500 dark:text-gray-400">
        © {{ date('Y') }} Moist Pixels. All rights reserved.
      </p>
    </div>
  </body>
</html>
