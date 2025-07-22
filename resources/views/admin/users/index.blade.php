<x-layouts.admin>
  <x-slot:title>User Management</x-slot>

  @php
    // Chuẩn bị dữ liệu ban đầu từ Controller để truyền cho JavaScript.
    $initialData = [
      'users' => $users->items(),
      'pagination' => $users->toArray(),
      'roles' => $roles,
      'fetchUrl' => route('admin.api.users.index'),
    ];
  @endphp

  {{--
    x-data chỉ gọi tên component.
    Dữ liệu sẽ được khởi tạo an toàn trong thẻ <script> bên dưới.
  --}}
  <div x-data="userManagement">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">User Management</h1>
        <p class="mt-1 text-sm text-gray-500">Manage all system users, their roles, and statuses.</p>
      </div>
      @can('users.create')
        <div class="mt-4 sm:mt-0">
          <a
            href="{{ route('admin.users.create') }}"
            class="inline-flex items-center justify-center px-4 py-2 bg-black text-white text-sm font-semibold rounded-lg hover:bg-gray-800 shadow-sm transition-colors"
          >
            <svg
              class="w-5 h-5 mr-2"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="1.5"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M12 6v12m6-6H6"
              />
            </svg>
            Add New User
          </a>
        </div>
      @endcan
    </div>

    {{-- Filters and Search Section --}}
    <div class="bg-white p-4 rounded-xl shadow-lg mb-6">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="md:col-span-2 relative">
          <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <svg
              class="w-5 h-5 text-gray-400"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="1.5"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"
              />
            </svg>
          </div>
          <input
            x-model.debounce.500ms="search"
            type="text"
            placeholder="Search by name or email..."
            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-black focus:border-black transition-colors"
          />
        </div>
        <div>
          <div wire:ignore>
            <select
              id="role-filter-select"
              placeholder="All Roles"
            >
              <option value="">All Roles</option>
              @foreach ($roles as $role)
                <option value="{{ $role->name }}">{{ $role->name }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div>
          <div wire:ignore>
            <select
              id="status-filter-select"
              placeholder="All Statuses"
            >
              <option value="">All Statuses</option>
              @foreach (App\Enums\UserStatus::cases() as $status)
                <option value="{{ $status->value }}">{{ Str::headline($status->name) }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
    </div>

    {{-- Users Table --}}
    <div
      class="bg-white shadow-md rounded-lg overflow-x-auto relative"
      :class="{ 'min-h-[200px]': loading }"
    >
      <div
        x-show="loading"
        x-transition
        class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center z-10"
      >
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-black"></div>
      </div>
      <table
        class="w-full text-sm text-left text-gray-500 divide-y divide-gray-200"
        x-show="!loading"
      >
        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
          <tr>
            <th
              scope="col"
              class="px-6 py-3 cursor-pointer border-r border-gray-200"
              @click="handleSort('name')"
            >
              <div class="flex items-center justify-between">
                <span>User</span>
                <span class="ml-2 flex flex-col">
                  <svg
                    class="w-2.5 h-2.5 -mb-0.5"
                    :class="{ 'text-gray-900': sortBy === 'name' && sortDirection === 'asc', 'text-gray-400': sortBy !== 'name' || sortDirection !== 'asc' }"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path d="M12 4l-8 8h16l-8-8z"></path>
                  </svg>
                  <svg
                    class="w-2.5 h-2.5"
                    :class="{ 'text-gray-900': sortBy === 'name' && sortDirection === 'desc', 'text-gray-400': sortBy !== 'name' || sortDirection !== 'desc' }"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path d="M12 20l8-8H4l8 8z"></path>
                  </svg>
                </span>
              </div>
            </th>
            <th
              scope="col"
              class="px-6 py-3 border-r border-gray-200"
            >
              Roles
            </th>
            <th
              scope="col"
              class="px-6 py-3 border-r border-gray-200"
            >
              Status
            </th>
            <th
              scope="col"
              class="px-6 py-3 cursor-pointer border-r border-gray-200"
              @click="handleSort('last_login_at')"
            >
              <div class="flex items-center justify-between">
                <span>Last Login</span>
                <span class="ml-2 flex flex-col">
                  <svg
                    class="w-2.5 h-2.5 -mb-0.5"
                    :class="{ 'text-gray-900': sortBy === 'last_login_at' && sortDirection === 'asc', 'text-gray-400': sortBy !== 'last_login_at' || sortDirection !== 'asc' }"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path d="M12 4l-8 8h16l-8-8z"></path>
                  </svg>
                  <svg
                    class="w-2.5 h-2.5"
                    :class="{ 'text-gray-900': sortBy === 'last_login_at' && sortDirection === 'desc', 'text-gray-400': sortBy !== 'last_login_at' || sortDirection !== 'desc' }"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path d="M12 20l8-8H4l8 8z"></path>
                  </svg>
                </span>
              </div>
            </th>
            <th
              scope="col"
              class="px-6 py-3"
            >
              <span class="sr-only">Actions</span>
            </th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <template x-if="!loading && users.length === 0">
            <tr>
              <td
                colspan="5"
                class="px-6 py-24 text-center text-gray-500"
              >
                No users found.
              </td>
            </tr>
          </template>
          <template
            x-for="user in users"
            :key="user.id"
          >
            <tr class="hover:bg-gray-50">
              <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap border-r border-gray-200">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10">
                    <div
                      class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center font-bold text-gray-500"
                      x-text="user.name.charAt(0)"
                    ></div>
                  </div>
                  <div class="ml-4">
                    <div
                      class="text-base font-semibold text-gray-900"
                      x-text="user.name"
                    ></div>
                    <div
                      class="text-sm text-gray-500"
                      x-text="user.email"
                    ></div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 border-r border-gray-200">
                <template x-if="user.roles.length > 0">
                  <div class="flex flex-wrap gap-1">
                    <template
                      x-for="role in user.roles"
                      :key="role.id"
                    >
                      <span
                        class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded-full"
                        x-text="role.name"
                      ></span>
                    </template>
                  </div>
                </template>
              </td>
              <td class="px-6 py-4 border-r border-gray-200">
                <span
                  class="inline-flex items-center gap-x-1.5 rounded-md px-2 py-1 text-xs font-medium"
                  :class="{ 'bg-green-100 text-green-700': user.status === 'active', 'bg-yellow-100 text-yellow-800': user.status === 'inactive', 'bg-red-100 text-red-800': user.status === 'suspended' }"
                >
                  <svg
                    class="h-1.5 w-1.5"
                    viewBox="0 0 6 6"
                    :class="{ 'fill-green-500': user.status === 'active', 'fill-yellow-500': user.status === 'inactive', 'fill-red-500': user.status === 'suspended'}"
                    aria-hidden="true"
                  >
                    <circle
                      cx="3"
                      cy="3"
                      r="3"
                    />
                  </svg>
                  <span x-text="user.status.charAt(0).toUpperCase() + user.status.slice(1)"></span>
                </span>
              </td>
              <td
                class="px-6 py-4 text-sm text-gray-500 border-r border-gray-200"
                x-text="user.last_login_at ? new Date(user.last_login_at).toLocaleString() : 'Never'"
              ></td>
              <td class="px-6 py-4">
                @can('users.edit')
                  <a
                    :href="`/admin/users/${user.id}/edit`"
                    class="font-medium text-purple-600 hover:text-purple-900"
                  >
                    Edit
                  </a>
                @endcan
              </td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
      <x-admin.pagination />
    </div>
  </div>

  {{-- Script to safely initialize Alpine component with server-side data --}}
  <script>
    document.addEventListener('alpine:init', () => {
      Alpine.data('userManagement', () => userManagement(@json($initialData)));
    });
  </script>
</x-layouts.admin>
