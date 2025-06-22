@extends('admin.layouts.app')

@section('title', 'Edit Role')

@section('content')
    <div class="content-header">
        <h1>Edit Role: {{ $role->display_name }}</h1>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-primary">Back to List</a>
    </div>

    <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
        @method('PUT')
        @include('admin.roles._form', ['submitButtonText' => 'Update Role'])
    </form>
@endsection