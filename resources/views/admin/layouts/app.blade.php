<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1.0"
        />
        <title>Admin Dashboard</title>

        {{--
            File: resources/views/layouts/admin.blade.php
            Description: Main admin layout file with a header, a toggleable sidebar, and a content area.
        --}}
        @vite('resources/css/app.css')
    </head>
    <body class="bg-bg-base font-sans text-sm antialiased">
        <div class="flex h-screen bg-bg-base">
            <aside
                id="sidebar"
                class="bg-primary text-text-button w-64 fixed inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 transition-transform duration-200 ease-in-out z-30"
            >
                <div class="h-16 flex items-center justify-center bg-secondary">
                    <h1 class="text-xl font-bold text-white">Admin Panel</h1>
                </div>

                <nav class="py-4">
                    {{--
                        Function: generateMenuLink
                        Description: A Blade component or include could be used here to avoid repetition.
                        Request: Current route to highlight the active link.
                        Response: An HTML anchor tag for the menu.
                    --}}
                    <a
                        href="#"
                        class="flex items-center px-6 py-3 text-white bg-secondary bg-opacity-25"
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
                                d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h7.5"
                            />
                        </svg>
                        <span>Dashboard</span>
                    </a>
                    <a
                        href="#"
                        class="flex items-center px-6 py-3 text-white hover:bg-secondary hover:bg-opacity-25 transition-colors duration-200"
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
                                d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-2.272M15 19.128v-3.86a2.25 2.25 0 01.9-1.755l.375-.25a2.25 2.25 0 012.652 3.512L15 19.128zM15 19.128v-3.86a2.25 2.25 0 00-.9-1.755l-.375-.25a2.25 2.25 0 00-2.652 3.512L15 19.128zM3 11.25a2.25 2.25 0 01.9-1.755l.375-.25a2.25 2.25 0 012.652 3.512L6 14.638V19.5a2.25 2.25 0 01-2.25 2.25h-1.5a2.25 2.25 0 01-2.25-2.25v-3.86m3.38-6.38a2.25 2.25 0 00-2.652-3.512L3 8.368v-3.86a2.25 2.25 0 012.25-2.25h1.5A2.25 2.25 0 019 4.5v3.86m-3.38 6.38a2.25 2.25 0 002.652 3.512l.375.25a2.25 2.25 0 00.9 1.755v3.86M3 11.25v2.25a2.25 2.25 0 002.25 2.25h1.5a2.25 2.25 0 002.25-2.25V11.25m-6 0a2.25 2.25 0 012.25-2.25h1.5a2.25 2.25 0 012.25 2.25m0 10.5a2.25 2.25 0 002.25-2.25v-3.86a2.25 2.25 0 00-2.25-2.25h-1.5a2.25 2.25 0 00-2.25 2.25v3.86a2.25 2.25 0 002.25 2.25z"
                            />
                        </svg>
                        <span>User Management</span>
                    </a>
                    <a
                        href="#"
                        class="flex items-center px-6 py-3 text-white hover:bg-secondary hover:bg-opacity-25 transition-colors duration-200"
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
                                d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.6-3.752A11.959 11.959 0 0118 6c0 2.707-1.126 5.156-2.956 6.84A6.978 6.978 0 0112 15a6.978 6.978 0 01-3.044-1.16A11.958 11.958 0 019 6.014z"
                            />
                        </svg>
                        <span>Role Management</span>
                    </a>
                    <a
                        href="#"
                        class="flex items-center px-6 py-3 text-white hover:bg-secondary hover:bg-opacity-25 transition-colors duration-200"
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
                                d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v-2.25l1.28-1.28c.328-.328.455-.834.36-1.317A6.002 6.002 0 0115.75 5.25z"
                            />
                        </svg>
                        <span>Permission Management</span>
                    </a>
                </nav>
            </aside>

            <div class="flex-1 flex flex-col overflow-hidden">
                <header class="bg-secondary text-text-button shadow">
                    <div class="flex items-center justify-between px-6 py-3">
                        <div class="flex items-center">
                            <button
                                id="sidebar-toggle"
                                class="md:hidden text-white focus:outline-none"
                            >
                                <svg
                                    class="h-6 w-6"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                </svg>
                            </button>
                            <div class="hidden md:block ml-4">Last login: {{ now()->format('D, M j, Y g:i A') }}</div>
                        </div>

                        <div class="flex items-center">
                            <span class="mr-4">Welcome, {{ Auth::user()->name ?? 'Admin' }}</span>
                            <a
                                href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                class="bg-danger text-text-button px-4 py-1.5 rounded-md hover:bg-opacity-80 transition-colors duration-200 text-xs font-bold uppercase"
                            >
                                Logout
                            </a>
                            <form
                                id="logout-form"
                                action="{{ route('logout') }}"
                                method="POST"
                                class="hidden"
                            >
                                @csrf
                            </form>
                        </div>
                    </div>
                </header>

                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-bg-base p-6">
                    <div class="container mx-auto">
                        <h2 class="text-2xl font-semibold text-secondary">Dashboard</h2>
                        <p class="text-text-main mt-2">Welcome to your admin dashboard. Your content goes here.</p>

                        <div class="mt-6 bg-white p-6 rounded-lg shadow-md">
                            <h3 class="text-lg font-medium text-secondary">Stats</h3>
                            <div class="border-t border-border-input mt-4 pt-4"></div>
                        </div>
                    </div>
                </main>
            </div>
        </div>

        <script>
            // --- Sidebar Toggle Script ---
            // Get the necessary elements from the DOM
            const sidebar = document.getElementById('sidebar');
            const toggleButton = document.getElementById('sidebar-toggle');

            // Function to toggle the sidebar's visibility
            const toggleSidebar = () => {
                // Important logic: Toggles the class that translates the sidebar off-screen
                sidebar.classList.toggle('-translate-x-full');
            };

            // Add click event listener to the toggle button
            if (toggleButton) {
                toggleButton.addEventListener('click', toggleSidebar);
            }

            // Optional: Close sidebar when clicking outside on mobile
            document.addEventListener('click', (event) => {
                // Check if the sidebar is open and the click is outside of the sidebar and the toggle button
                if (
                    !sidebar.classList.contains('-translate-x-full') &&
                    !sidebar.contains(event.target) &&
                    !toggleButton.contains(event.target)
                ) {
                    // This logic is for mobile view where sidebar is an overlay.
                    // We check if the screen is small enough for the sidebar to be in overlay mode.
                    if (window.innerWidth < 768) {
                        // 768px is the 'md' breakpoint in Tailwind
                        toggleSidebar();
                    }
                }
            });
        </script>
    </body>
</html>
