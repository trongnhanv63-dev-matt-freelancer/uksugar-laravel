@extends('layouts.app')

@section('content')
    <div>
        <h1>Login</h1>
        @livewire('auth.login-form')
    </div>
@endsection