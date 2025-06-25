@extends('admin.layouts.app')

@section('title', 'Edit Role')

@section('content')
    <form
        action="{{ route('admin.roles.update', $role->id) }}"
        method="POST"
    >
        @method('PUT')
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="border-b border-gray-900/10 pb-6 mb-6">
                <h2 class="text-base font-semibold leading-7 text-gray-900">
                    Edit Role:
                    <span class="text-indigo-600">{{ $role->name }}</span>
                </h2>
                <p class="mt-1 text-sm leading-6 text-gray-600">Update the role's details and permissions.</p>
            </div>

            @include('admin.roles._form', ['submitButtonText' => 'Update Role', 'role' => $role])
        </div>
    </form>
@endsection
