<x-layouts.admin>
  <x-slot:title>Edit Role: {{ $role->name }}</x-slot>

  <x-admin.form.card
    action="{{ route('admin.roles.update', $role) }}"
    method="PUT"
  >
    <x-admin.form.header
      title="Edit Role: {{ $role->name }}"
      description="Update the role's details and assigned permissions."
    />

    @include('admin.roles._form', ['role' => $role, 'submitButtonText' => 'Update Role'])
  </x-admin.form.card>
</x-layouts.admin>
