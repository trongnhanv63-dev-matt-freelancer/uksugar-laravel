@extends('admin.layouts.app')
@section('title', 'Edit User')
@section('content')
    <form
        action="{{ route('admin.users.update', $user->id) }}"
        method="POST"
    >
        @method('PUT')
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="border-b border-gray-900/10 pb-6 mb-6">
                <h2 class="text-base font-semibold leading-7 text-gray-900">
                    Edit User:
                    <span class="text-indigo-600">{{ $user->username }}</span>
                </h2>
                <p class="mt-1 text-sm leading-6 text-gray-600">Update the user's details and assigned roles.</p>
            </div>
            @include('admin.users._form', ['submitButtonText' => 'Update User', 'user' => $user])
        </div>
    </form>
@endsection
