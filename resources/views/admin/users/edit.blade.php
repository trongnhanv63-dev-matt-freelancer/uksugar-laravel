<x-layouts.admin>
  <x-slot:title>Edit User: {{ $user->name }}</x-slot>

  <form
    action="{{ route('admin.users.update', $user) }}"
    method="POST"
  >
    @method('PUT')
    <div class="bg-white p-6 rounded-xl shadow-lg">
      <div class="border-b border-gray-200 pb-5 mb-6">
        <h2 class="text-xl font-semibold leading-7 text-gray-900">
          Edit User:
          <span class="text-purple-600">{{ $user->name }}</span>
        </h2>
        <p class="mt-1 text-sm leading-6 text-gray-500">Update the user's details and assigned roles.</p>
      </div>

      @include('admin.users._form', ['user' => $user, 'submitButtonText' => 'Update User'])
    </div>
  </form>
</x-layouts.admin>
