<x-layouts.admin>
  <x-slot:title>Create New User</x-slot>

  <form
    action="{{ route('admin.users.store') }}"
    method="POST"
  >
    <div class="bg-white p-6 rounded-xl shadow-lg">
      <div class="border-b border-gray-200 pb-5 mb-6">
        <h2 class="text-xl font-semibold leading-7 text-gray-900">Create New User</h2>
        <p class="mt-1 text-sm leading-6 text-gray-500">Provide the user's details and assign roles.</p>
      </div>

      @include('admin.users._form', ['submitButtonText' => 'Create User'])
    </div>
  </form>
</x-layouts.admin>
