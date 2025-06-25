@extends('admin.layouts.app')

@section('title', 'Edit Permission')

@section('content')
    <form
        action="{{ route('admin.permissions.update', $permission->id) }}"
        method="POST"
    >
        @method('PUT')
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="border-b border-gray-900/10 pb-6 mb-6">
                <h2 class="text-base font-semibold leading-7 text-gray-900">
                    Edit Permission:
                    <span class="font-mono text-indigo-600">{{ $permission->name }}</span>
                </h2>
                <p class="mt-1 text-sm leading-6 text-gray-600">Update the permission's details.</p>
            </div>

            @include('admin.permissions._form', ['submitButtonText' => 'Update Permission', 'permission' => $permission])
        </div>
    </form>
@endsection
