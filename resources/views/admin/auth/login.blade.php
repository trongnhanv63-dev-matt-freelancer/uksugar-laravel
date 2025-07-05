<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1.0"
        />
        <title>Login</title>

        {{-- This directive includes the compiled CSS file from Vite --}}
        @vite('resources/css/app.css')
    </head>
    <body class="bg-primary bg-opacity-70 font-sans antialiased text-text-main">
        <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-md w-full p-8 md:p-10 bg-white rounded-xl shadow-lg space-y-8">
                <div>
                    <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-secondary">
                        Sign in to your account
                    </h2>
                </div>

                {{-- Form now points to the 'admin.login' route and includes CSRF protection --}}
                <form
                    class="mt-8 space-y-6"
                    action="{{ route('admin.login') }}"
                    method="POST"
                >
                    {{-- CSRF Token: Essential for security in Laravel POST forms --}}
                    @csrf

                    <input
                        type="hidden"
                        name="remember"
                        value="true"
                    />
                    <div class="space-y-4">
                        <div>
                            <label
                                for="email-address"
                                class="sr-only"
                            >
                                Email address
                            </label>
                            <input
                                id="email-address"
                                name="email"
                                type="email"
                                autocomplete="email"
                                required
                                class="relative block w-full appearance-none rounded-md border border-border-input bg-bg-input px-3 py-2.5 text-secondary placeholder-gray-500 focus:z-10 focus:border-accent focus:outline-none focus:ring-accent sm:text-sm"
                                placeholder="Email address"
                            />
                        </div>
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
                                class="relative block w-full appearance-none rounded-md border border-border-input bg-bg-input px-3 py-2.5 text-secondary placeholder-gray-500 focus:z-10 focus:border-accent focus:outline-none focus:ring-accent sm:text-sm"
                                placeholder="Password"
                            />
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input
                                id="remember-me"
                                name="remember-me"
                                type="checkbox"
                                class="h-4 w-4 rounded border-gray-300 text-accent focus:ring-accent"
                            />
                            <label
                                for="remember-me"
                                class="ml-2 block text-sm text-text-main"
                            >
                                Remember me
                            </label>
                        </div>

                        <div class="text-sm">
                            <a
                                href="#"
                                class="font-medium text-accent hover:text-opacity-80"
                            >
                                Forgot your password?
                            </a>
                        </div>
                    </div>

                    <div>
                        <button
                            type="submit"
                            class="group relative flex w-full justify-center rounded-md border border-transparent bg-primary py-2.5 px-4 text-sm font-semibold text-text-button hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2"
                        >
                            Sign in
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
