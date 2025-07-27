<x-layouts.admin>
  <x-slot:title>Create New Role</x-slot>

  <x-admin.form.card action="{{ route('admin.roles.store') }}">
    <x-admin.form.header
      title="Create New Role"
      description="Define a new role and assign permissions to it."
    />

    @include('admin.roles._form', ['submitButtonText' => 'Save'])
  </x-admin.form.card>
</x-layouts.admin>
