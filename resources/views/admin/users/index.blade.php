@extends('admin.layouts.app')
@use(App\Enums\UserStatus)

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
                <th>Username</th>
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
                        {{ $user->username }}
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
                        <span style="color: {{ $user->status === UserStatus::Active ? 'green' : 'red' }}">
                            ● {{ ucfirst($user->status->value) }}
                        </span>
                    </td>
                    <td class="action-links">
                        <a href="{{ route('admin.users.edit', $user->id) }}">Edit</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">No users found.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection
