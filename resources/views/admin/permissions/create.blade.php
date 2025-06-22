@extends('admin.layouts.app')

@section('title', 'Create New Permission')

@section('content')
    <div class="content-header">
        <h1>Create New Permission</h1>
        <a
            href="{{ route('admin.permissions.index') }}"
            class="btn btn-primary"
        >
            Back to List
        </a>
    </div>

    <form
        action="{{ route('admin.permissions.store') }}"
        method="POST"
    >
        @include('admin.permissions._form', ['submitButtonText' => 'Create Permission'])
    </form>
@endsection
