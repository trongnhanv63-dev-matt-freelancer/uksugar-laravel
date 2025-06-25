@extends('admin.layouts.app')

@section('title', 'Manage Users')

@section('content')
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">User Management</h1>
        <a
            href="{{ route('admin.users.create') }}"
            class="mt-4 sm:mt-0 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-500 transition-colors duration-200"
        >
            Create New User
        </a>
    </div>

    {{-- Session Messages --}}
    @if (session('success'))
        <div
            class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4"
            role="alert"
        >
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if (session('error'))
        <div
            class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4"
            role="alert"
        >
            <p>{{ session('error') }}</p>
        </div>
    @endif

    {{-- Users Table --}}
    <div class="bg-white shadow-md rounded-lg overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th
                        scope="col"
                        class="px-6 py-3"
                    >
                        Username
                    </th>
                    <th
                        scope="col"
                        class="px-6 py-3"
                    >
                        Email
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
                @forelse ($users as $user)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            {{ $user->username }}
                            {{-- Add a star for super admins for easy identification --}}
                            @if ($user->hasRole('super-admin'))
                                <span title="Super Admin">⭐</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            {{-- Display roles as badges --}}
                            @foreach ($user->roles as $role)
                                <span
                                    class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full"
                                >
                                    {{ $role->name }}
                                </span>
                            @endforeach
                        </td>
                        <td class="px-6 py-4">
                            @if ($user->status === \App\Enums\UserStatus::Active)
                                <span
                                    class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full"
                                >
                                    Active
                                </span>
                            @else
                                <span
                                    class="bg-yellow-100 text-yellow-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full"
                                >
                                    {{ ucfirst($user->status->value) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 flex items-center space-x-4">
                            {{-- A user cannot edit a super-admin or themselves --}}
                            @if (! $user->hasRole('super-admin') && auth()->id() !== $user->id)
                                <a
                                    href="{{ route('admin.users.edit', $user->id) }}"
                                    class="font-medium text-indigo-600 hover:underline"
                                >
                                    Edit
                                </a>
                                {{-- The status toggle is now handled within the edit form --}}
                            @else
                                <span class="text-gray-400">Protected</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr class="bg-white border-b">
                        <td
                            colspan="5"
                            class="px-6 py-4 text-center text-gray-500"
                        >
                            No users found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
