@extends('admin.layouts.app')
@section('title', 'Create New User')
@section('content')
    <form
        action="{{ route('admin.users.store') }}"
        method="POST"
    >
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="border-b border-gray-900/10 pb-6 mb-6">
                <h2 class="text-base font-semibold leading-7 text-gray-900">Create a New User</h2>
                <p class="mt-1 text-sm leading-6 text-gray-600">Provide the user details and assign roles.</p>
            </div>
            @include('admin.users._form', ['submitButtonText' => 'Create User'])
        </div>
    </form>
@endsection
