@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="content-header">
        <h1>Admin Dashboard</h1>
    </div>

    <div class="content-body">
        <p>
            Welcome back,
            <strong>{{ auth()->user()->username }}</strong>
            !
        </p>
        <p>From here, you can manage the application's roles, permissions, users, and other settings.</p>
        <p>Use the navigation menu on the left to get started.</p>
    </div>
@endsection
