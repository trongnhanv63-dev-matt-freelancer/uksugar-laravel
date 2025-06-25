@extends('admin.layouts.app')

@section('title', 'Manage Roles')

@section('content')
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Roles Management</h1>
        <a
            href="{{ route('admin.roles.create') }}"
            class="mt-4 sm:mt-0 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-500 transition-colors duration-200"
        >
            Create New Role
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

    {{-- Roles Table --}}
    <div class="bg-white shadow-md rounded-lg overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th
                        scope="col"
                        class="px-6 py-3"
                    >
                        Role Name
                    </th>
                    <th
                        scope="col"
                        class="px-6 py-3"
                    >
                        Permissions Count
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
                @forelse ($roles as $role)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            {{ $role->name }}
                            @if ($role->name === 'super-admin')
                                <span title="Super Admin">⭐</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">
                                {{ $role->permissions->count() }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if ($role->status === 'active')
                                <span
                                    class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full"
                                >
                                    Active
                                </span>
                            @else
                                <span
                                    class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full"
                                >
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 flex items-center space-x-4">
                            @if ($role->name !== 'super-admin')
                                <a
                                    href="{{ route('admin.roles.edit', $role->id) }}"
                                    class="font-medium text-indigo-600 hover:underline"
                                >
                                    Edit
                                </a>
                                <form
                                    method="POST"
                                    action="{{ route('admin.roles.toggleStatus', $role->id) }}"
                                    onsubmit="return confirm('Are you sure you want to change this role\'s status?');"
                                >
                                    @csrf
                                    @method('PATCH')
                                    <button
                                        type="submit"
                                        class="font-medium hover:underline {{ $role->status === 'active' ? 'text-yellow-600' : 'text-green-600' }}"
                                    >
                                        {{ $role->status === 'active' ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                            @else
                                <span class="text-gray-400">Protected</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr class="bg-white border-b">
                        <td
                            colspan="4"
                            class="px-6 py-4 text-center text-gray-500"
                        >
                            No roles found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
