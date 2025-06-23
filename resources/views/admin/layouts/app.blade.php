<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1.0"
        />
        <title>@yield('title', 'Admin Panel')</title>
        {{-- A very simple styling for the admin panel --}}
        <style>
            body {
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
                background-color: #f8f9fa;
                color: #212529;
                margin: 0;
            }
            .admin-container {
                display: flex;
                min-height: 100vh;
            }
            .sidebar {
                width: 220px;
                background-color: #343a40;
                color: #fff;
                padding: 1rem;
                flex-shrink: 0;
            }
            .sidebar h2 {
                font-size: 1.5rem;
                text-align: center;
                margin-bottom: 2rem;
                color: #fff;
            }
            .sidebar a {
                display: block;
                color: #adb5bd;
                text-decoration: none;
                padding: 0.75rem 1rem;
                border-radius: 0.25rem;
                margin-bottom: 0.5rem;
            }
            .sidebar a:hover,
            .sidebar a.active {
                background-color: #495057;
                color: #fff;
            }

            .content-wrapper {
                flex-grow: 1;
                display: flex;
                flex-direction: column;
            }
            .header {
                background: #fff;
                padding: 1rem 2rem;
                border-bottom: 1px solid #dee2e6;
                display: flex;
                justify-content: flex-end;
                align-items: center;
            }
            .header .user-info {
                margin-right: 1rem;
                color: #6c757d;
            }
            .header .logout-btn {
                background: #dc3545;
                color: white;
                border: none;
                padding: 0.4rem 0.8rem;
                border-radius: 0.25rem;
                cursor: pointer;
                font-size: 0.875rem;
            }
            .header .logout-btn:hover {
                background: #c82333;
            }

            .main-content {
                flex-grow: 1;
                padding: 2rem;
            }
            .content-header {
                border-bottom: 1px solid #dee2e6;
                padding-bottom: 1rem;
                margin-bottom: 2rem;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            .btn {
                display: inline-block;
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
                font-weight: 600;
                text-align: center;
                white-space: nowrap;
                vertical-align: middle;
                user-select: none;
                border: 1px solid transparent;
                border-radius: 0.25rem;
                text-decoration: none;
            }
            .btn-primary {
                color: #fff;
                background-color: #007bff;
                border-color: #007bff;
            }
            .btn-primary:hover {
                background-color: #0069d9;
            }
            .alert {
                padding: 1rem;
                margin-bottom: 1rem;
                border: 1px solid transparent;
                border-radius: 0.25rem;
            }
            .alert-success {
                color: #155724;
                background-color: #d4edda;
                border-color: #c3e6cb;
            }
            .alert-danger {
                color: #721c24;
                background-color: #f8d7da;
                border-color: #f5c6cb;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 1rem;
            }
            th,
            td {
                padding: 0.75rem;
                text-align: left;
                border-bottom: 1px solid #dee2e6;
            }
            th {
                background-color: #e9ecef;
            }
            .action-links a,
            .action-links button {
                margin-right: 0.5rem;
                color: #007bff;
                text-decoration: none;
                background: none;
                border: none;
                cursor: pointer;
                padding: 0;
                font-size: inherit;
                font-family: inherit;
            }
            .action-links .delete-btn {
                color: #dc3545;
            }
        </style>
    </head>
    <body>
        <div class="admin-container">
            <aside class="sidebar">
                <h2>Admin Panel</h2>
                <nav>
                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                    >
                        Dashboard
                    </a>
                    <a
                        href="{{ route('admin.roles.index') }}"
                        class="{{ request()->routeIs('admin.roles.*') ? 'active' : '' }}"
                    >
                        Manage Roles
                    </a>
                    <a
                        href="{{ route('admin.permissions.index') }}"
                        class="{{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}"
                    >
                        Manage Permissions
                    </a>
                    <a
                        href="{{ route('admin.users.index') }}"
                        class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                    >
                        Manage Users
                    </a>
                </nav>
            </aside>

            <div class="content-wrapper">
                <header class="header">
                    <div class="user-info">
                        Logged in as:
                        <strong>{{ auth()->user()->username }}</strong>
                    </div>
                    <form
                        method="POST"
                        action="{{ route('admin.logout') }}"
                    >
                        @csrf
                        <button
                            type="submit"
                            class="logout-btn"
                        >
                            Logout
                        </button>
                    </form>
                </header>

                <main class="main-content">
                    @yield('content')
                </main>
            </div>
        </div>
    </body>
</html>
