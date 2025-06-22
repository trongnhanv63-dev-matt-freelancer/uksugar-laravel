@extends('admin.layouts.app')

@section('title', 'Edit Permission')

@section('content')
    <div class="content-header">
        <h1>Edit Permission: {{ $permission->slug }}</h1>
        <a
            href="{{ route('admin.permissions.index') }}"
            class="btn btn-primary"
        >
            Back to List
        </a>
    </div>

    <form
        action="{{ route('admin.permissions.update', $permission->id) }}"
        method="POST"
    >
        @method('PUT')
        @include('admin.permissions._form', ['submitButtonText' => 'Update Permission'])
    </form>
@endsection
