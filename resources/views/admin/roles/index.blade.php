@extends('admin.layouts.app')

@section('title', 'Manage Roles')

@section('content')
    <div class="content-header">
        <h1>Roles and Permissions</h1>
        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">Create New Role</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Role Name</th>
                <th>Display Name</th>
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
                        @if($role->name === 'super-admin')
                            <span style="background-color: #e0e0e0; padding: 2px 5px; border-radius: 4px; font-size: 0.8em; margin-right: 5px;">
                                Full Permission
                            </span>
                        @else
                            @foreach($role->permissions as $permission)
                                <span style="background-color: #e0e0e0; padding: 2px 5px; border-radius: 4px; font-size: 0.8em; margin-right: 5px;">
                                    {{ $permission->slug }}
                                </span>
                            @endforeach
                        @endif
                    </td>
                    <td class="action-links">
                        <a href="{{ route('admin.roles.edit', $role->id) }}">Edit</a>
                        @if($role->name !== 'super-admin')
                            <form method="POST" action="{{ route('admin.roles.destroy', $role->id) }}" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this role?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-btn">Delete</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No roles found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection