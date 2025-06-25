@csrf

@if ($errors->any())
    <div
        class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6"
        role="alert"
    >
        <p class="font-bold">Please fix the following errors:</p>
        <ul class="mt-2 list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@php
    // Determine if the user being edited is protected (is a super-admin)
    // We will handle the "self-edit" case via policy or in the controller if needed.
    $isProtectedUser = isset($user) && $user->hasRole('super-admin');
@endphp

<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label
                for="username"
                class="block text-sm font-medium leading-6 text-gray-900"
            >
                Username
            </label>
            <div class="mt-2">
                <input
                    type="text"
                    name="username"
                    id="username"
                    value="{{ old('username', $user->username ?? '') }}"
                    required
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                />
            </div>
        </div>

        <div>
            <label
                for="email"
                class="block text-sm font-medium leading-6 text-gray-900"
            >
                Email
            </label>
            <div class="mt-2">
                <input
                    type="email"
                    name="email"
                    id="email"
                    value="{{ old('email', $user->email ?? '') }}"
                    required
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                />
            </div>
        </div>
    </div>

    <div>
        @if (isset($user))
            <div id="password-wrapper">
                <label class="block text-sm font-medium leading-6 text-gray-900">Password</label>
                <div
                    id="password-placeholder"
                    class="mt-2"
                >
                    <input
                        type="text"
                        class="block w-full rounded-md border-0 py-1.5 bg-gray-100 cursor-not-allowed"
                        value="••••••••••"
                        readonly
                    />
                    <button
                        type="button"
                        onclick="showPasswordFields()"
                        class="mt-2 text-sm font-semibold text-indigo-600 hover:text-indigo-500"
                    >
                        Change Password
                    </button>
                </div>
                <div
                    id="password-fields"
                    style="display: none"
                    class="mt-2"
                >
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                        placeholder="Enter new password"
                    />
                    <p class="mt-2 text-xs text-gray-500">Leave blank to keep current password.</p>
                </div>
            </div>
        @else
            <div>
                <label
                    for="password"
                    class="block text-sm font-medium leading-6 text-gray-900"
                >
                    Password
                </label>
                <div class="mt-2">
                    <input
                        type="password"
                        name="password"
                        id="password"
                        required
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                    />
                </div>
            </div>
        @endif
    </div>

    <div>
        <label
            for="status"
            class="block text-sm font-medium leading-6 text-gray-900"
        >
            Status
        </label>
        <div class="mt-2">
            @if ($isProtectedUser)
                <input
                    type="text"
                    class="block w-full rounded-md border-0 py-1.5 bg-gray-100 cursor-not-allowed"
                    value="{{ ucfirst($user->status) }}"
                    readonly
                />
                <input
                    type="hidden"
                    name="status"
                    value="{{ $user->status }}"
                />
            @else
                <select
                    id="status"
                    name="status"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                >
                    <option
                        value="active"
                        @selected(old('status', $user->status ?? 'active') == 'active')
                    >
                        Active
                    </option>
                    <option
                        value="inactive"
                        @selected(old('status', $user->status ?? '') == 'inactive')
                    >
                        Inactive
                    </option>
                    <option
                        value="suspended"
                        @selected(old('status', $user->status ?? '') == 'suspended')
                    >
                        Suspended
                    </option>
                </select>
            @endif
        </div>
    </div>

    <div class="pt-6 border-t border-gray-200">
        <label class="block text-base font-medium leading-6 text-gray-900">Assign Roles</label>
        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            @foreach ($roles as $role)
                @php
                    $userHasRole = isset($user) && $user->hasRole($role->name);
                @endphp

                <div class="relative flex items-start">
                    <div class="flex h-6 items-center">
                        <input
                            id="role_{{ $role->id }}"
                            name="roles[]"
                            type="checkbox"
                            value="{{ $role->name }}"
                            @if($userHasRole) checked @endif
                            @if($role->name === 'super-admin') disabled @endif
                            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600"
                        />
                    </div>
                    <div class="ml-3 text-sm leading-6">
                        <label
                            for="role_{{ $role->id }}"
                            class="{{ $role->name === 'super-admin' ? 'text-gray-500' : 'text-gray-900' }}"
                        >
                            {{ $role->name }}
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="mt-8 pt-5 border-t border-gray-200">
    <div class="flex justify-end gap-x-3">
        <a
            href="{{ route('admin.users.index') }}"
            class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
        >
            Cancel
        </a>
        <button
            type="submit"
            @if($isProtectedUser) disabled @endif
            class="inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 @if($isProtectedUser) opacity-50 cursor-not-allowed @endif"
        >
            {{ $submitButtonText ?? 'Save' }}
        </button>
    </div>
</div>

@if (isset($user))
    <script>
        function showPasswordFields() {
            document.getElementById('password-placeholder').style.display = 'none';
            document.getElementById('password-fields').style.display = 'block';
            document.getElementById('password').focus();
        }
    </script>
@endif
