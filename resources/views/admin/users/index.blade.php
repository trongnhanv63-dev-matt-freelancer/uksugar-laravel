@extends('admin.layouts.app')

@section('title', 'Manage Users')

@section('content')
    <div class="content-header">
        <h1>User Management</h1>
        <a
            href="{{ route('admin.users.create') }}"
            class="btn btn-primary"
        >
            Create New User
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Roles</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td>
                        {{ $user->name }}
                        @if ($user->is_super_admin)
                            ⭐
                        @endif
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @foreach ($user->roles as $role)
                            <span
                                style="
                                    background-color: #007bff;
                                    color: white;
                                    padding: 2px 5px;
                                    border-radius: 4px;
                                    font-size: 0.8em;
                                    white-space: nowrap;
                                "
                            >
                                {{ $role->display_name }}
                            </span>
                        @endforeach
                    </td>
                    <td>
                        <span
                            style="
                                color: {{ $user->status === config('rbac.user_statuses.active') ? 'green' : 'red' }};
                            "
                        >
                            ● {{ ucfirst($user->status) }}
                        </span>
                    </td>
                    <td class="action-links">
                        @if (! $user->is_super_admin)
                            <a href="{{ route('admin.users.edit', $user->id) }}">Edit</a>
                        @else
                            <span style="color: #6c757d">(Protected)</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">No users found.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection
