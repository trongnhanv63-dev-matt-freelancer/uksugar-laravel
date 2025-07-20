<x-layouts.admin>
  <x-slot:title>User Management</x-slot>

  {{--
    Chúng ta sẽ khởi tạo Alpine từ một thẻ <script> riêng biệt bên dưới.
    x-data chỉ đơn giản là gọi component mà không truyền dữ liệu trực tiếp ở đây.
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
          {{-- MODIFIED: Removed @input --}}
          <input
            x-model.debounce.500ms="search"
            type="text"
            placeholder="Search by name or email..."
            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-black focus:border-black transition-colors"
          />
        </div>
        <div>
          {{-- MODIFIED: Removed @change --}}
          <select
            x-model="selectedRole"
            class="w-full py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-black focus:border-black transition-colors"
          >
            <option value="">All Roles</option>
            <template
              x-for="role in roles"
              :key="role.id"
            >
              <option
                :value="role.name"
                x-text="role.name"
              ></option>
            </template>
          </select>
        </div>
        <div>
          {{-- MODIFIED: Removed @change --}}
          <select
            x-model="selectedStatus"
            class="w-full py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-black focus:border-black transition-colors"
          >
            <option value="">All Statuses</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
            <option value="suspended">Suspended</option>
          </select>
        </div>
      </div>
    </div>

    {{-- Users Table --}}
    <div class="bg-white shadow-md rounded-lg overflow-x-auto relative">
      <div
        x-show="loading"
        x-transition
        class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center z-10"
      >
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-black"></div>
      </div>
      <table class="w-full text-sm text-left text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
          <tr>
            <th
              scope="col"
              class="px-6 py-3 cursor-pointer"
              @click="handleSort('name')"
            >
              <span class="flex items-center">
                User
                <svg
                  x-show="sortBy === 'name'"
                  class="w-3 h-3 ml-1.5"
                  :class="sortDirection === 'asc' ? '' : 'rotate-180'"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="2"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M19.5 8.25l-7.5 7.5-7.5-7.5"
                  />
                </svg>
              </span>
            </th>
            <th
              scope="col"
              class="px-6 py-3"
            >
              Roles
            </th>
            <th
              scope="col"
              class="px-6 py-3"
            >
              Status
            </th>
            <th
              scope="col"
              class="px-6 py-3 cursor-pointer"
              @click="handleSort('last_login_at')"
            >
              <span class="flex items-center">
                Last Login
                <svg
                  x-show="sortBy === 'last_login_at'"
                  class="w-3 h-3 ml-1.5"
                  :class="sortDirection === 'asc' ? '' : 'rotate-180'"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="2"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M19.5 8.25l-7.5 7.5-7.5-7.5"
                  />
                </svg>
              </span>
            </th>
            <th
              scope="col"
              class="px-6 py-3"
            >
              <span class="sr-only">Actions</span>
            </th>
          </tr>
        </thead>
        <tbody>
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
            <tr class="bg-white border-b hover:bg-gray-50">
              <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
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
              <td class="px-6 py-4">
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
              <td class="px-6 py-4">
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
                class="px-6 py-4 text-sm text-gray-500"
                x-text="user.last_login_at ? new Date(user.last_login_at).toLocaleString() : 'Never'"
              ></td>
              <td class="px-6 py-4 text-right">
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
      Alpine.data('userManagement', () =>
        userManagement({
          users: @json($users->items()),
          pagination: @json($users->toArray()),
          roles: @json($roles),
          fetchUrl: @json(route('admin.api.users.index')),
        })
      );
    });
  </script>
</x-layouts.admin>
