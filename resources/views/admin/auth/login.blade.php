<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1.0"
        />
        <title>Admin Sign In</title>

        <link
            rel="preconnect"
            href="https://fonts.bunny.net"
        />
        <link
            href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap"
            rel="stylesheet"
        />

        @vite('resources/css/app.css')
    </head>
    <body class="font-sans antialiased flex flex-col min-h-screen bg-white">
        <header class="w-full h-32 bg-primary"></header>

        <main class="flex-grow flex items-center justify-center">
            <div class="w-full max-w-xs mx-auto">
                <h1 class="text-center text-2xl font-semibold text-gray-800 mb-6">Admin Sign In</h1>

                <form
                    method="POST"
                    action="{{ route('admin.login') }}"
                    class="space-y-4"
                >
                    @csrf

                    <div>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            placeholder="Login"
                            autocomplete="username"
                            class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary/80"
                        />
                    </div>

                    <div>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            placeholder="Password"
                            autocomplete="current-password"
                            class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary/80"
                        />
                    </div>

                    <div>
                        <button
                            type="submit"
                            class="w-full font-bold py-3 px-4 rounded-md text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all duration-200"
                        >
                            Let's Go!
                        </button>
                    </div>

                    <div class="text-center pt-2">
                        <a
                            href="#"
                            class="text-sm font-medium text-red-600 hover:text-red-500"
                        >
                            Forgot Password?
                        </a>
                    </div>
                </form>
            </div>
        </main>

        <footer class="w-full h-32 bg-primary"></footer>
    </body>
</html>
