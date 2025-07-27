<x-layouts.admin>
  <x-slot:title>Create New User</x-slot>

  <x-admin.form.card action="{{ route('admin.users.store') }}">
    <x-admin.form.header
      title="Create New User"
      description="Provide the user's details and assign roles."
    />

    @include('admin.users._form', ['submitButtonText' => 'Save'])
  </x-admin.form.card>
</x-layouts.admin>
