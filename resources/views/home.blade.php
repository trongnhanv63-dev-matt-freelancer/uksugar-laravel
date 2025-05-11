@extends('layouts.app')

@section('content')
    <div>
        <h1>Home</h1>
        @if (Route::has('login'))
            <nav>
                @auth
                    @livewire('auth.logout')
                @else
                    <a href="{{ route('login') }}">
                        Log in
                    </a>
                @endauth
            </nav>
        @endif
    </div>
@endsection
