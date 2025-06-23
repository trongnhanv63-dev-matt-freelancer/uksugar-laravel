@extends('admin.layouts.app')

@use(App\Enums\StatusEnum)

@section('title', 'Manage Roles')

@section('content')
    <div class="content-header">
        <h1>Roles and Permissions</h1>
        <a
            href="{{ route('admin.roles.create') }}"
            class="btn btn-primary"
        >
            Create New Role
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
                <th>Role Name</th>
                <th>Display Name</th>
                <th>Status</th>
                <th>Permissions</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($roles as $role)
                <tr>
                    <td>{{ $role->name }}</td>
                    <td>{{ $role->display_name }}</td>
                    <td>
                        <span style="color: {{ $role->status === StatusEnum::Active ? 'green' : 'red' }}">
                            ● {{ ucfirst($role->status->value) }}
                        </span>
                    </td>
                    <td>
                        @if ($role->name === 'super-admin')
                            <span style="font-style: italic; color: #6c757d">All Permissions</span>
                        @else
                            @foreach ($role->permissions->take(5) as $permission)
                                <span
                                    style="
                                        background-color: #e0e0e0;
                                        padding: 2px 5px;
                                        border-radius: 4px;
                                        font-size: 0.8em;
                                        margin-right: 5px;
                                        white-space: nowrap;
                                    "
                                >
                                    {{ $permission->slug }}
                                </span>
                            @endforeach

                            @if ($role->permissions->count() > 5)
                                <span
                                    style="
                                        background-color: #e0e0e0;
                                        padding: 2px 5px;
                                        border-radius: 4px;
                                        font-size: 0.8em;
                                    "
                                >
                                    ...
                                </span>
                            @endif
                        @endif
                    </td>
                    <td class="action-links">
                        @if ($role->name !== 'super-admin')
                            <a href="{{ route('admin.roles.edit', $role->id) }}">Edit</a>
                            @if ($role->name !== 'super-admin')
                                <form
                                    method="POST"
                                    action="{{ route('admin.roles.toggleStatus', $role->id) }}"
                                    style="display: inline"
                                    onsubmit="return confirm('Are you sure you want to change this role\'s status?');"
                                >
                                    @csrf
                                    @method('PATCH')
                                    <button
                                        type="submit"
                                        class="delete-btn"
                                        style="color: {{ $role->status === StatusEnum::Active ? 'orange' : 'green' }}"
                                    >
                                        {{ $role->status === StatusEnum::Active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                            @endif
                        @else
                            <span style="color: #6c757d">(Protected)</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No roles found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
