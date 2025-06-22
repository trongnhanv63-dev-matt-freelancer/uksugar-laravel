@extends('admin.layouts.app')

@section('title', 'Create New Role')

@section('content')
    <div class="content-header">
        <h1>Create New Role</h1>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-primary">Back to List</a>
    </div>

    <form action="{{ route('admin.roles.store') }}" method="POST">
        @include('admin.roles._form', ['submitButtonText' => 'Create Role'])
    </form>
@endsection