@extends('admin.layouts.app')

@section('title', 'Create New Permission')

@section('content')
    <form
        action="{{ route('admin.permissions.store') }}"
        method="POST"
    >
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="border-b border-gray-900/10 pb-6 mb-6">
                <h2 class="text-base font-semibold leading-7 text-gray-900">Create a New Permission</h2>
                <p class="mt-1 text-sm leading-6 text-gray-600">
                    Define a new system permission that can be assigned to roles.
                </p>
            </div>

            @include('admin.permissions._form', ['submitButtonText' => 'Create Permission'])
        </div>
    </form>
@endsection
