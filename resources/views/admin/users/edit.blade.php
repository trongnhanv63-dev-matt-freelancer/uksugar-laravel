@extends('admin.layouts.app')
@section('title', 'Edit User')
@section('content')
    <div class="content-header">
        <h1>Edit User: {{ $user->name }}</h1>
        <a
            href="{{ route('admin.users.index') }}"
            class="btn btn-primary"
            style="text-decoration: none"
        >
            Back to List
        </a>
    </div>
    <form
        action="{{ route('admin.users.update', $user->id) }}"
        method="POST"
    >
        @method('PUT')
        @include('admin.users._form', ['submitButtonText' => 'Update User'])
    </form>
@endsection
