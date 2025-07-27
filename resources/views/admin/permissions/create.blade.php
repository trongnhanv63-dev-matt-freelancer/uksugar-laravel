<x-layouts.admin>
  <x-slot:title>Create New Permission</x-slot>

  <x-admin.form.card action="{{ route('admin.permissions.store') }}">
    <x-admin.form.header
      title="Create New Permission"
      description="Define a new system permission that can be assigned to roles."
    />

    @include('admin.permissions._form', ['submitButtonText' => 'Save'])
  </x-admin.form.card>
</x-layouts.admin>
