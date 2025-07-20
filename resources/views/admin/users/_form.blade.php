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

@php
  $isEditMode = isset($user);
  $isProtectedUser = $isEditMode && $user->hasRole('super-admin');
@endphp

<div class="space-y-6">
  {{-- Name & Username --}}
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
      <label
        for="name"
        class="block text-sm font-medium leading-6 text-gray-900"
      >
        Full Name
      </label>
      <div class="mt-2">
        <input
          type="text"
          name="name"
          id="name"
          value="{{ old('name', $user->name ?? '') }}"
          required
          @if($isProtectedUser) disabled @endif
          class="block w-full rounded-md border-0 px-3 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-black sm:text-sm sm:leading-6 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500"
        />
      </div>
    </div>
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
          @if($isProtectedUser) disabled @endif
          class="block w-full rounded-md border-0 px-3 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-black sm:text-sm sm:leading-6 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500"
        />
      </div>
    </div>
  </div>

  {{-- Email --}}
  <div>
    <label
      for="email"
      class="block text-sm font-medium leading-6 text-gray-900"
    >
      Email Address
    </label>
    <div class="mt-2">
      <input
        type="email"
        name="email"
        id="email"
        value="{{ old('email', $user->email ?? '') }}"
        required
        @if($isProtectedUser) disabled @endif
        class="block w-full rounded-md border-0 px-3 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-black sm:text-sm sm:leading-6 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500"
      />
    </div>
  </div>

  {{-- Password with Visibility Toggle --}}
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
        @if($isProtectedUser) disabled @endif
        class="block w-full rounded-md border-0 px-3 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-black sm:text-sm sm:leading-6 disabled:cursor-not-allowed disabled:bg-gray-50"
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

  {{-- Searchable Select for Status --}}
  <div>
    <label
      for="status-select"
      class="block text-sm font-medium leading-6 text-gray-900"
    >
      Status
    </label>
    <div class="mt-2">
      {{-- Thẻ select giờ đây cực kỳ đơn giản, chỉ có một id --}}
      <select
        id="status-select"
        name="status"
        @if($isProtectedUser) disabled @endif
      >
        @foreach (App\Enums\UserStatus::cases() as $status)
          <option
            value="{{ $status->value }}"
            @selected(old('status', $isEditMode ? $user->status->value : 'active') == $status->value)
          >
            {{ ucfirst(str_replace('_', ' ', $status->name)) }}
          </option>
        @endforeach
      </select>
    </div>
  </div>

  {{-- Improved UI for Role Selection --}}
  <div class="pt-6 border-t border-gray-200">
    <h3 class="text-base font-semibold leading-6 text-gray-900">Assign Roles</h3>
    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
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
            class="block p-4 border rounded-lg cursor-pointer transition-all peer-checked:border-purple-500 peer-checked:ring-2 peer-checked:ring-purple-200 peer-disabled:cursor-not-allowed peer-disabled:bg-gray-100 peer-disabled:text-gray-500"
          >
            <div class="flex items-center justify-between">
              <span class="font-medium text-gray-900">{{ $role->name }}</span>
              <svg
                class="w-6 h-6 text-purple-600 opacity-0 peer-checked:opacity-100 transition-opacity"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20"
                fill="currentColor"
              >
                <path
                  fill-rule="evenodd"
                  d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.06 0l4-5.5z"
                  clip-rule="evenodd"
                />
              </svg>
            </div>
          </label>
        </div>
      @endforeach
    </div>
  </div>
</div>

<div class="mt-8 pt-6 border-t border-gray-200 flex items-center justify-end gap-x-6">
  <a
    href="{{ route('admin.users.index') }}"
    class="text-sm font-semibold leading-6 text-gray-900"
  >
    Cancel
  </a>
  <button
    type="submit"
    @if($isProtectedUser) disabled @endif
    class="rounded-md bg-black px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black disabled:opacity-50 disabled:cursor-not-allowed"
  >
    {{ $submitButtonText ?? 'Save' }}
  </button>
</div>
<script>
  document.addEventListener('alpine:init', function () {
    const el = document.getElementById('status-select');
    if (el) {
      new TomSelect(el, {
        placeholder: 'Select a status...',
      });
    }
  });
</script>
