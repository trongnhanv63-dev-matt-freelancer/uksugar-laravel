<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1.0"
        />
        <title>@yield('title', 'Admin Panel')</title>
        @vite('resources/css/app.css')
    </head>
    <body class="font-sans antialiased bg-slate-100 text-slate-900">
        <div class="flex min-h-screen">
            <aside class="w-64 bg-gray-800 text-white flex-shrink-0">
                <div class="p-6 text-2xl font-semibold text-center">
                    <a href="{{ route('admin.dashboard') }}">Admin Panel</a>
                </div>
                <nav class="mt-8">
                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="flex items-center px-6 py-3 hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-900' : '' }}"
                    >
                        Dashboard
                    </a>
                    <a
                        href="{{ route('admin.users.index') }}"
                        class="flex items-center px-6 py-3 hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-gray-900' : '' }}"
                    >
                        Manage Users
                    </a>
                    <a
                        href="{{ route('admin.roles.index') }}"
                        class="flex items-center px-6 py-3 hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('admin.roles.*') ? 'bg-gray-900' : '' }}"
                    >
                        Manage Roles
                    </a>
                    <a
                        href="{{ route('admin.permissions.index') }}"
                        class="flex items-center px-6 py-3 hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('admin.permissions.*') ? 'bg-gray-900' : '' }}"
                    >
                        Manage Permissions
                    </a>
                </nav>
            </aside>

            <div class="flex-grow flex flex-col">
                <header class="bg-white shadow-md p-4 flex justify-end items-center">
                    <div class="user-info mr-4">
                        <span class="text-gray-600">Welcome,</span>
                        <strong class="font-medium">{{ auth()->user()->username }}</strong>
                    </div>
                    <form
                        method="POST"
                        action="{{ route('admin.logout') }}"
                    >
                        @csrf
                        <button
                            type="submit"
                            class="text-sm text-gray-600 hover:text-indigo-600"
                        >
                            Logout
                        </button>
                    </form>
                </header>

                <main class="flex-grow p-6">
                    @yield('content')
                </main>
            </div>
        </div>
    </body>
</html>
