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
    $isEditMode = isset($role);
    $isSuperAdmin = $isEditMode && $role->name === 'super-admin';
@endphp

<div class="space-y-6">
    <div>
        <label
            for="name"
            class="block text-sm font-medium leading-6 text-gray-900"
        >
            Role Name
        </label>
        <div class="mt-2">
            <input
                type="text"
                name="name"
                id="name"
                value="{{ old('name', $role->name ?? '') }}"
                @if($isEditMode) readonly @endif
                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 @if($isEditMode) bg-gray-100 cursor-not-allowed @endif"
            />
            @if ($isEditMode)
                <p class="mt-2 text-xs text-gray-500">The role name cannot be changed after creation.</p>
            @endif
        </div>

        <div>
            <label
                for="description"
                class="block text-sm font-medium leading-6 text-gray-900"
            >
                Description
            </label>
            <div class="mt-2">
                <input
                    type="text"
                    name="description"
                    id="description"
                    value="{{ old('description', $role->description ?? '') }}"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                />
                <p class="mt-2 text-xs text-gray-500">
                    A brief, human-readable explanation of what this permission allows.
                </p>
            </div>
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium leading-6 text-gray-900">Permissions</label>
        @if ($isSuperAdmin)
            <p class="mt-2 text-sm text-green-600">Super Admin automatically has all permissions.</p>
        @endif

        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach ($permissions as $group => $permissionList)
                <div class="p-4 border rounded-lg">
                    <h3 class="font-semibold text-gray-700 mb-2 capitalize">{{ $group }}</h3>
                    <div class="space-y-2">
                        @foreach ($permissionList as $permission)
                            @php
                                $permissionIsActive = $permission->status === 'active';
                                $isChecked = isset($rolePermissions) && in_array($permission->id, $rolePermissions);
                            @endphp

                            <div class="relative flex items-start">
                                <div class="flex h-6 items-center">
                                    <input
                                        id="perm_{{ $permission->id }}"
                                        name="permissions[]"
                                        type="checkbox"
                                        value="{{ $permission->name }}"
                                        @if($isChecked || $isSuperAdmin) checked @endif
                                        @if($isSuperAdmin || !$permissionIsActive) disabled @endif
                                        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600"
                                    />
                                </div>
                                <div class="ml-3 text-sm leading-6">
                                    <label
                                        for="perm_{{ $permission->id }}"
                                        class="{{ ! $permissionIsActive ? 'text-gray-400 line-through' : 'text-gray-700' }}"
                                    >
                                        {{ $permission->name }}
                                        @if (! $permissionIsActive)
                                            (Inactive)
                                        @endif
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="mt-8 pt-5 border-t border-gray-200">
    <div class="flex justify-end gap-x-3">
        <a
            href="{{ route('admin.roles.index') }}"
            class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
        >
            Cancel
        </a>
        <button
            type="submit"
            @if($isSuperAdmin) disabled @endif
            class="inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 @if($isSuperAdmin) opacity-50 cursor-not-allowed @endif"
        >
            {{ $submitButtonText ?? 'Save' }}
        </button>
    </div>
</div>
