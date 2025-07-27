@php
  $isEditMode = isset($permission);
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
  {{-- Permission Name --}}
  <x-admin.form.input
    name="name"
    label="Permission Name"
    :value="$permission->name ?? ''"
    :disabled="$isEditMode"
    required
    autocomplete="off"
    help-text="Use dot notation (e.g., `posts.create`). Cannot be changed after creation."
  />

  {{-- Description --}}
  <x-admin.form.textarea
    name="description"
    label="Description"
    :value="$permission->description ?? ''"
    autocomplete="off"
    help-text="A brief, human-readable explanation of what this permission allows."
  />

  {{-- Status --}}
  <x-admin.form.select
    name="status"
    id="status-select"
    label="Status"
  >
    @foreach (App\Enums\Status::cases() as $status)
      <option
        value="{{ $status->value }}"
        @selected(old('status', $isEditMode ? $permission->status->value : 'active') == $status->value)
      >
        {{ Str::headline($status->name) }}
      </option>
    @endforeach
  </x-admin.form.select>
</div>

{{-- Form Actions --}}
<x-admin.form.actions
  :cancel-url="route('admin.permissions.index')"
  :submit-text="$submitButtonText ?? 'Save'"
/>

<script>
  // This script initializes TomSelect for the status dropdown.
  document.addEventListener('DOMContentLoaded', function () {
    if (window.TomSelect) {
      const el = document.getElementById('status-select');
      if (el) {
        new TomSelect(el, {
          placeholder: 'Select a status...',
        });
      }
    }
  });
</script>
