@extends('admin.layouts.app')

@section('title', 'Manage Permissions')

@section('content')
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Permissions Management</h1>
        <a
            href="{{ route('admin.permissions.create') }}"
            class="mt-4 sm:mt-0 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-500 transition-colors duration-200"
        >
            Create New Permission
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

    {{-- Permissions Table --}}
    <div class="bg-white shadow-md rounded-lg overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th
                        scope="col"
                        class="px-6 py-3"
                    >
                        Permission Name (Slug)
                    </th>
                    <th
                        scope="col"
                        class="px-6 py-3"
                    >
                        Description
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
                @forelse ($permissions as $permission)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-mono text-gray-700 whitespace-nowrap">
                            {{ $permission->name }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $permission->description ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4">
                            @if ($permission->status === 'active')
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
                            <a
                                href="{{ route('admin.permissions.edit', $permission->id) }}"
                                class="font-medium text-indigo-600 hover:underline"
                            >
                                Edit
                            </a>
                            <form
                                method="POST"
                                action="{{ route('admin.permissions.toggleStatus', $permission->id) }}"
                                onsubmit="return confirm('Are you sure?');"
                            >
                                @csrf
                                @method('PATCH')
                                <button
                                    type="submit"
                                    class="font-medium hover:underline {{ $permission->status === 'active' ? 'text-yellow-600' : 'text-green-600' }}"
                                >
                                    {{ $permission->status === 'active' ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr class="bg-white border-b">
                        <td
                            colspan="4"
                            class="px-6 py-4 text-center text-gray-500"
                        >
                            No permissions found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
