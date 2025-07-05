@extends('admin.layouts.app')

@section('title', 'User Management')

@section('content')
    <div
        x-data="userManagement(
                    {{ json_encode($users->items()) }},
                    {{ json_encode($users->toArray()) }},
                    {{ json_encode($roles) }},
                )"
        x-init="init()"
    >
        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-primary">User List</h1>
                <p class="mt-1 text-sm text-text-main">Manage all system users, their roles, and statuses.</p>
            </div>
            <div class="mt-4 sm:mt-0">
                @can('users.create')
                    <a
                        href="{{ route('admin.users.create') }}"
                        class="inline-flex items-center justify-center px-4 py-2 bg-header text-white text-sm font-semibold rounded-lg hover:opacity-90 shadow-sm"
                    >
                        <svg
                            class="w-5 h-5 mr-2"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 6v12m6-6H6"
                            />
                        </svg>
                        Add New User
                    </a>
                @endcan
            </div>
        </div>

        {{-- Filters and Search Section --}}
        <div class="bg-white p-4 rounded-xl shadow-lg mb-6">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                {{-- Search Input --}}
                <div class="md:col-span-2">
                    <label
                        for="search"
                        class="sr-only"
                    >
                        Search
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg
                                class="w-5 h-5 text-gray-400"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"
                                />
                            </svg>
                        </div>
                        <input
                            x-model.debounce.500ms="search"
                            @input="fetchData"
                            type="text"
                            id="search"
                            placeholder="Search by name or username..."
                            class="w-full rounded-lg border-input-border py-2 pl-10 pr-4 shadow-sm focus:ring-accent focus:border-accent"
                        />
                    </div>
                </div>
                {{-- Role Filter --}}
                <div>
                    <label
                        for="role_filter"
                        class="sr-only"
                    >
                        Filter by Role
                    </label>
                    <select
                        x-model="selectedRole"
                        @change="fetchData"
                        id="role_filter"
                        class="w-full rounded-lg border-input-border shadow-sm focus:ring-accent focus:border-accent"
                    >
                        <option value="">All Roles</option>
                        <template
                            x-for="role in roles"
                            :key="role.id"
                        >
                            <option
                                :value="role.name"
                                x-text="role.name"
                            ></option>
                        </template>
                    </select>
                </div>
                {{-- Status Filter --}}
                <div>
                    <label
                        for="status_filter"
                        class="sr-only"
                    >
                        Filter by Status
                    </label>
                    <select
                        x-model="selectedStatus"
                        @change="fetchData"
                        id="status_filter"
                        class="w-full rounded-lg border-input-border shadow-sm focus:ring-accent focus:border-accent"
                    >
                        <option value="">All Statuses</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="suspended">Suspended</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Users Table --}}
        <div class="bg-white shadow-md rounded-lg overflow-x-auto relative">
            <div
                x-show="loading"
                x-transition
                class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center z-10"
            >
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-accent"></div>
            </div>
            <table class="w-full text-sm text-left text-text-main">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50">
                    <tr>
                        <th
                            scope="col"
                            class="p-4"
                        >
                            <input
                                type="checkbox"
                                class="rounded border-gray-300"
                            />
                        </th>
                        <th
                            scope="col"
                            class="px-6 py-3 cursor-pointer"
                            @click="handleSort('name')"
                        >
                            <span class="flex items-center">
                                User
                                <span
                                    x-show="sortBy === 'name'"
                                    x-text="sortDirection === 'asc' ? '↑' : '↓'"
                                    class="ml-1"
                                ></span>
                            </span>
                        </th>
                        <th
                            scope="col"
                            class="px-6 py-3"
                        >
                            Roles
                        </th>
                        <th
                            scope="col"
                            class="px-6 py-3"
                        >
                            Status
                        </th>
                        <th
                            scope="col"
                            class="px-6 py-3"
                        >
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <template
                        x-for="user in users"
                        :key="user.id"
                    >
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="w-4 p-4">
                                <input
                                    type="checkbox"
                                    class="rounded border-gray-300"
                                />
                            </td>
                            <th
                                scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"
                            >
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-gray-200 mr-4 flex-shrink-0"></div>
                                    <div>
                                        <div x-text="user.name"></div>
                                        <div
                                            class="text-xs text-gray-500"
                                            x-text="user.email"
                                        ></div>
                                    </div>
                                </div>
                            </th>
                            <td class="px-6 py-4">
                                <template
                                    x-for="role in user.roles"
                                    :key="role.id"
                                >
                                    <span
                                        class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full"
                                        x-text="role.name"
                                    ></span>
                                </template>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center gap-x-1.5 rounded-md px-2 py-1 text-xs font-medium"
                                    :class="{
                                        'bg-green-100 text-green-700': user.status === 'active',
                                        'bg-yellow-100 text-yellow-800': user.status === 'inactive',
                                        'bg-red-100 text-red-800': user.status !== 'active' && user.status !== 'inactive'
                                      }"
                                >
                                    <svg
                                        class="h-1.5 w-1.5"
                                        viewBox="0 0 6 6"
                                        :class="{ 'fill-green-500': user.status === 'active', 'fill-yellow-500': user.status === 'inactive', 'fill-red-500': user.status !== 'active' && user.status !== 'inactive'}"
                                        aria-hidden="true"
                                    >
                                        <circle
                                            cx="3"
                                            cy="3"
                                            r="3"
                                        />
                                    </svg>
                                    <span x-text="user.status.charAt(0).toUpperCase() + user.status.slice(1)"></span>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <a
                                    :href="`/admin/users/${user.id}/edit`"
                                    class="font-medium text-accent hover:underline"
                                >
                                    Edit
                                </a>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="!users || users.length === 0">
                        <td
                            colspan="5"
                            class="px-6 py-4 text-center text-gray-500"
                        >
                            No users found matching your criteria.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        {{-- Pagination will be added here later --}}
    </div>
@endsection
