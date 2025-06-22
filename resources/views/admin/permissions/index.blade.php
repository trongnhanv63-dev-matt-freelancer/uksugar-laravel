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
                <th width="15%">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($permissions as $permission)
                <tr>
                    <td><code>{{ $permission->slug }}</code></td>
                    <td>{{ $permission->description }}</td>
                    <td class="action-links">
                        <a href="{{ route('admin.permissions.edit', $permission->id) }}">Edit</a>
                        <form
                            method="POST"
                            action="{{ route('admin.permissions.destroy', $permission->id) }}"
                            style="display: inline"
                            onsubmit="return confirm('Are you sure? This cannot be undone.');"
                        >
                            @csrf
                            @method('DELETE')
                            <button
                                type="submit"
                                class="delete-btn"
                            >
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">No permissions found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
