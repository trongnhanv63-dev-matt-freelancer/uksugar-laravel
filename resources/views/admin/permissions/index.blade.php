@extends('admin.layouts.app')

@section('title', 'Manage Permissions')

@section('content')
    <div class="content-header">
        <h1>System Permissions</h1>
        <a
            href="{{ route('admin.permissions.create') }}"
            class="btn btn-primary"
        >
            Create New Permission
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Slug (Used in Code)</th>
                <th>Description</th>
                <th>Status</th>
                <th width="20%">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($permissions as $permission)
                <tr>
                    <td><code>{{ $permission->slug }}</code></td>
                    <td>{{ $permission->description }}</td>
                    <td>
                        <span
                            style="
                                color: {{ $permission->status === config('rbac.permission_statuses.active') ? 'green' : 'red' }};
                            "
                        >
                            ● {{ ucfirst($permission->status) }}
                        </span>
                    </td>
                    <td class="action-links">
                        <a href="{{ route('admin.permissions.edit', $permission->id) }}">Edit</a>
                        <form
                            method="POST"
                            action="{{ route('admin.permissions.toggleStatus', $permission->id) }}"
                            style="display: inline"
                            onsubmit="return confirm('Are you sure you want to change this permission\'s status?');"
                        >
                            @csrf
                            @method('PATCH')
                            <button
                                type="submit"
                                class="delete-btn"
                                style="
                                    color: {{ $permission->status === config('rbac.permission_statuses.active') ? 'orange' : 'green' }};
                                "
                            >
                                {{ $permission->status === config('rbac.permission_statuses.active') ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No permissions found. You can create them or run the seeder.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
