<x-layouts.admin>
  <x-slot:title>Role Management</x-slot>

  <x-admin.live-table
    type="roles"
    :title="'Role Management'"
    :description="'Manage system roles and their assigned permissions.'"
    :initial-data="$roles->toArray()"
    :api-url="route('admin.api.roles.index')"
    :create-url="route('admin.roles.create')"
    :create-permission="'roles.create'"
    :edit-url-template="route('admin.roles.edit', ['role' => 'ITEM_ID'])"
    state-key="roles_management"
  >
    <x-slot:row>
      <tr class="hover:bg-gray-50">
        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap border-r border-gray-200">
          <div class="flex items-center">
            <div class="flex-shrink-0 h-10 w-10">
              <div
                class="h-10 w-10 rounded-full bg-purple-200 flex items-center justify-center font-bold text-purple-600"
                x-text="item.name.charAt(0).toUpperCase()"
              ></div>
            </div>
            <div class="ml-4">
              <div class="text-base font-semibold text-gray-900">
                <template x-if="item.name === 'super-admin'">
                  <span class="text-yellow-500 mr-1">⭐</span>
                </template>
                <span x-text="item.name"></span>
              </div>
              <div
                class="text-sm text-gray-500"
                x-text="item.description || 'No description'"
              ></div>
            </div>
          </div>
        </td>
        <td class="px-6 py-4 border-r border-gray-200">
          <span
            class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full"
            x-text="(item.permissions_count || 0) + ' permissions'"
          ></span>
        </td>
        <td class="px-6 py-4 border-r border-gray-200">
          <span
            class="inline-flex items-center gap-x-1.5 rounded-md px-2 py-1 text-xs font-medium"
            :class="{ 'bg-green-100 text-green-700': item.status === 'active', 'bg-red-100 text-red-800': item.status === 'inactive' }"
          >
            <svg
              class="h-1.5 w-1.5"
              viewBox="0 0 6 6"
              :class="{ 'fill-green-500': item.status === 'active', 'fill-red-500': item.status === 'inactive'}"
              aria-hidden="true"
            >
              <circle
                cx="3"
                cy="3"
                r="3"
              />
            </svg>
            <span x-text="item.status.charAt(0).toUpperCase() + item.status.slice(1)"></span>
          </span>
        </td>
        <td
          class="px-6 py-4 text-sm text-gray-500 border-r border-gray-200"
          x-text="new Date(item.created_at).toLocaleDateString()"
        ></td>
        <td class="px-6 py-4">
          @can('roles.edit')
            <template x-if="item.name !== 'super-admin'">
              <a
                :href="config.editUrlTemplate.replace('ITEM_ID', item.id)"
                class="text-indigo-600 hover:text-indigo-900 font-medium"
              >
                Edit
              </a>
            </template>
            <template x-if="item.name === 'super-admin'">
              <span class="text-gray-400 cursor-not-allowed">Protected</span>
            </template>
          @endcan
        </td>
      </tr>
    </x-slot>
  </x-admin.live-table>
</x-layouts.admin>
