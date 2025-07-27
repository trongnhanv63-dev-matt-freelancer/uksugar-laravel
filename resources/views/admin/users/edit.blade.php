<x-layouts.admin>
  <x-slot:title>Edit User: {{ $user->name }}</x-slot>

  <x-admin.form.card
    action="{{ route('admin.users.update', $user) }}"
    method="PUT"
  >
    <x-admin.form.header
      title="Edit User: {{ $user->name }}"
      description="Update the user's details and assigned roles."
    />

    @include('admin.users._form', ['user' => $user, 'submitButtonText' => 'Save'])
  </x-admin.form.card>
</x-layouts.admin>
