@php
  // This logic remains as it is specific to this form
  $isEditMode = isset($user);
  $isProtectedUser = $isEditMode && $user->hasRole('super-admin');
@endphp

@csrf
{{-- Display validation errors --}}
@if ($errors->any())
  <div
    class="bg-red-50 border-l-4 border-red-400 text-red-700 p-4 mb-6 rounded-md"
    role="alert"
  >
    <p class="font-bold">Please correct the following errors:</p>
    <ul class="mt-2 list-disc list-inside text-sm">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

<div class="space-y-6">
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    {{-- Using the input component for Name and Username --}}
    <x-admin.form.input
      name="name"
      label="Full Name"
      :value="$user->name ?? ''"
      :disabled="$isProtectedUser"
      autocomplete="name"
      required
    />
    <x-admin.form.input
      name="username"
      label="Username"
      :value="$user->username ?? ''"
      :disabled="$isEditMode"
      autocomplete="username"
      required
    />
  </div>

  <x-admin.form.input
    name="email"
    label="Email Address"
    type="email"
    :value="$user->email ?? ''"
    :disabled="$isEditMode"
    autocomplete="email"
    required
  />

  {{-- Password with Visibility Toggle (This logic is specific to the user form, so it remains here) --}}
  <div x-data="{
    type: 'password',
    showPasswordFields: {{ $isEditMode ? 'false' : 'true' }},
  }">
    <label
      for="password"
      class="block text-sm font-medium leading-6 text-gray-900"
    >
      Password
    </label>
    @if ($isEditMode)
      <div
        class="mt-2"
        x-show="!showPasswordFields"
      >
        <button
          @click="showPasswordFields = true"
          type="button"
          class="text-sm font-semibold text-purple-600 hover:text-purple-500"
        >
          Change Password
        </button>
      </div>
    @endif

    <div
      class="mt-2 relative"
      x-show="showPasswordFields"
      @if($isEditMode) style="display: none;" @endif
    >
      <input
        :type="type"
        name="password"
        id="password"
        {{ ! $isEditMode ? 'required' : '' }}
        autocomplete="{{ $isEditMode ? 'new-password' : 'current-password' }}"
        @if($isProtectedUser) disabled @endif
        class="block w-full rounded-md border-0 px-3 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:outline-none focus:ring-1 focus:ring-inset focus:ring-purple-500 sm:text-sm sm:leading-6 disabled:cursor-not-allowed disabled:bg-gray-50"
        placeholder="{{ $isEditMode ? 'Enter new password' : 'Enter password' }}"
      />
      <button
        type="button"
        @click="type = type === 'password' ? 'text' : 'password'"
        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600"
      >
        <svg
          x-show="type === 'password'"
          class="w-5 h-5"
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 24 24"
          stroke-width="1.5"
          stroke="currentColor"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"
          />
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
          />
        </svg>
        <svg
          x-show="type === 'text'"
          class="w-5 h-5"
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 24 24"
          stroke-width="1.5"
          stroke="currentColor"
          style="display: none"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.243 4.243l-4.243-4.243"
          />
        </svg>
      </button>
    </div>
  </div>

  {{-- Using the select component --}}
  <x-admin.form.select
    name="status"
    id="status-select"
    label="Status"
    :disabled="$isProtectedUser"
  >
    @foreach (App\Enums\UserStatus::cases() as $status)
      <option
        value="{{ $status->value }}"
        @selected(old('status', $isEditMode ? $user->status->value : 'active') == $status->value)
      >
        {{ Str::headline($status->name) }}
      </option>
    @endforeach
  </x-admin.form.select>

  {{-- Role Selection (This is specific to users, so it stays here) --}}
  <div class="pt-6 border-t border-gray-200">
    <h3 class="text-base font-semibold leading-6 text-gray-900">Assign Roles</h3>
    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
      @foreach ($roles as $role)
        <div class="relative">
          <input
            id="role_{{ $role->id }}"
            name="roles[]"
            type="checkbox"
            value="{{ $role->id }}"
            @if(in_array($role->id, old('roles', $isEditMode ? $user->roles->pluck('id')->toArray() : []))) checked @endif
            @if($isProtectedUser) disabled @endif
            class="peer hidden"
          />
          <label
            for="role_{{ $role->id }}"
            class="flex flex-col justify-between p-4 h-full border rounded-lg cursor-pointer transition-all peer-checked:border-purple-500 peer-checked:bg-purple-50 peer-disabled:cursor-not-allowed peer-disabled:bg-gray-100 peer-disabled:text-gray-500"
          >
            <span class="font-semibold text-gray-900 peer-disabled:text-gray-500">{{ $role->name }}</span>
            <p class="mt-2 text-sm text-gray-500 peer-disabled:text-gray-400">
              {{ $role->description ?? 'No description provided.' }}
            </p>
          </label>
          <div
            class="absolute top-2 right-2 w-5 h-5 bg-green-500 rounded-full flex items-center justify-center opacity-0 peer-checked:opacity-100 transition-opacity"
          >
            <svg
              class="w-3 h-3 text-white"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="3"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M4.5 12.75l6 6 9-13.5"
              />
            </svg>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</div>

{{-- Using the actions component --}}
<x-admin.form.actions
  :cancel-url="route('admin.users.index')"
  :submit-button-text="$submitButtonText ?? 'Save'"
  :disabled="$isProtectedUser"
/>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    if (window.TomSelect) {
      const el = document.getElementById('status-select');
      if (el && !el.disabled) {
        new TomSelect(el, {
          placeholder: 'Select a status...',
        });
      }
    }
  });
</script>
