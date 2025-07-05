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
    $isEditMode = isset($permission);
@endphp

<div class="space-y-6">
    <div>
        <label
            for="name"
            class="block text-sm font-medium leading-6 text-gray-900"
        >
            Permission Name
        </label>
        <div class="mt-2">
            <input
                type="text"
                name="name"
                id="name"
                value="{{ old('name', $permission->name ?? '') }}"
                @if($isEditMode) readonly @endif
                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 @if($isEditMode) bg-gray-100 cursor-not-allowed @endif"
            />
            <p class="mt-2 text-xs text-gray-500">
                Use dot notation (e.g., `posts.create`). Cannot be changed after creation.
            </p>
        </div>
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
                value="{{ old('description', $permission->description ?? '') }}"
                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
            />
            <p class="mt-2 text-xs text-gray-500">
                A brief, human-readable explanation of what this permission allows.
            </p>
        </div>
    </div>

    <div>
        <label
            for="status"
            class="block text-sm font-medium leading-6 text-gray-900"
        >
            Status
        </label>
        <div class="mt-2">
            <select
                id="status"
                name="status"
                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
            >
                <option
                    value="active"
                    @selected(old('status', $permission->status ?? 'active') == 'active')
                >
                    Active
                </option>
                <option
                    value="inactive"
                    @selected(old('status', $permission->status ?? '') == 'inactive')
                >
                    Inactive
                </option>
            </select>
        </div>
    </div>
</div>

<div class="mt-8 pt-5 border-t border-gray-200">
    <div class="flex justify-end gap-x-3">
        <a
            href="{{ route('admin.permissions.index') }}"
            class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
        >
            Cancel
        </a>
        <button
            type="submit"
            class="inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
        >
            {{ $submitButtonText ?? 'Save' }}
        </button>
    </div>
</div>
