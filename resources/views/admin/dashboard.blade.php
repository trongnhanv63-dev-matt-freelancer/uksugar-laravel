@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    {{-- Page Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-primary">Dashboard</h1>
        <p class="mt-1 text-text-main">An overview of your application's data.</p>
    </div>

    {{-- Stat Cards Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-xl shadow-lg flex items-center justify-between">
            <div>
                <div class="text-sm font-medium text-gray-400">Total Users</div>
                <div class="text-3xl font-bold text-primary">{{ $userCount }}</div>
            </div>
            <div class="bg-blue-100 p-3 rounded-full">
                <svg
                    class="h-6 w-6 text-blue-500"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-4.67c.12-.24.232-.483.336-.728M9 12.75a3 3 0 11-6 0 3 3 0 016 0z"
                    />
                </svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-lg flex items-center justify-between">
            <div>
                <div class="text-sm font-medium text-gray-400">Total Roles</div>
                <div class="text-3xl font-bold text-primary">{{ $roleCount }}</div>
            </div>
            <div class="bg-purple-100 p-3 rounded-full">
                <svg
                    class="h-6 w-6 text-accent"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"
                    />
                </svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-lg flex items-center justify-between">
            <div>
                <div class="text-sm font-medium text-gray-400">Total Permissions</div>
                <div class="text-3xl font-bold text-primary">{{ $permissionCount }}</div>
            </div>
            <div class="bg-green-100 p-3 rounded-full">
                <svg
                    class="h-6 w-6 text-success"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                    />
                </svg>
            </div>
        </div>
    </div>
    <div class="mb-6">
        <h2 class="text-xl font-semibold mb-2">DaisyUI Component Test</h2>
        <div class="flex items-center gap-4">
            <button class="btn">Default</button>
            <button class="btn btn-primary">Primary Button</button>
            <button class="btn btn-secondary">Secondary Button</button>
            <button class="btn btn-accent">Accent Button</button>
            <button class="btn btn-success">Success</button>
            <button class="btn btn-error">Error</button>
        </div>
    </div>
@endsection
