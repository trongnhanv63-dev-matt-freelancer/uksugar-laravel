<x-layouts.admin>
  <x-slot:title>Edit Permission: {{ $permission->name }}</x-slot>

  <x-admin.form.card
    action="{{ route('admin.permissions.update', $permission) }}"
    method="PUT"
  >
    <x-admin.form.header
      title="Edit Permission: {{ $permission->name }}"
      description="Update the permission's details."
    />

    @include('admin.permissions._form', ['permission' => $permission, 'submitButtonText' => 'Save'])
  </x-admin.form.card>
</x-layouts.admin>
