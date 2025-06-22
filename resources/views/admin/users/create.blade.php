@extends('admin.layouts.app')
@section('title', 'Create New User')
@section('content')
    <div class="content-header">
        <h1>Create New User</h1>
        <a
            href="{{ route('admin.users.index') }}"
            class="btn btn-primary"
            style="text-decoration: none"
        >
            Back to List
        </a>
    </div>
    <form
        action="{{ route('admin.users.store') }}"
        method="POST"
    >
        @include('admin.users._form', ['submitButtonText' => 'Create User'])
    </form>
@endsection
