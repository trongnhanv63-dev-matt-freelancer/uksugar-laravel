@extends('admin.layouts.app')

@section('title', 'Create New Role')

@section('content')
    <form
        action="{{ route('admin.roles.store') }}"
        method="POST"
    >
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="border-b border-gray-900/10 pb-6 mb-6">
                <h2 class="text-base font-semibold leading-7 text-gray-900">Create a New Role</h2>
                <p class="mt-1 text-sm leading-6 text-gray-600">Define a new role and assign permissions to it.</p>
            </div>

            @include('admin.roles._form', ['submitButtonText' => 'Create Role'])
        </div>
    </form>
@endsection
