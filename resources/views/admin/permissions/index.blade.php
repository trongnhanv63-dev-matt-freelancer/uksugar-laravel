<x-layouts.admin>
  <x-slot:title>Permission Management</x-slot>

  <x-admin.live-table
    type="permissions"
    :title="'Permission Management'"
    :description="'Manage all system permissions that can be assigned to roles.'"
    :initial-data="$permissions->toArray()"
    :api-url="route('admin.api.permissions.index')"
    :create-url="route('admin.permissions.create')"
    :create-permission="'permissions.create'"
    :edit-url-template="route('admin.permissions.edit', ['permission' => 'ITEM_ID'])"
    state-key="permissions_management"
  >
    <x-slot:row>
      <tr class="hover:bg-gray-50">
        {{-- Permission Name --}}
        <td
          class="px-6 py-4 font-mono text-gray-800 whitespace-nowrap border-r border-gray-200"
          x-text="item.name"
        ></td>

        {{-- Description --}}
        <td
          class="px-6 py-4 text-gray-600 border-r border-gray-200"
          x-text="item.description || 'No description'"
        ></td>

        {{-- Status --}}
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

        {{-- Created Date --}}
        <td
          class="px-6 py-4 text-sm text-gray-500 border-r border-gray-200"
          x-text="new Date(item.created_at).toLocaleDateString()"
        ></td>

        {{-- Actions --}}
        <td class="px-6 py-4">
          @can('permissions.edit')
            <a
              :href="config.editUrlTemplate.replace('ITEM_ID', item.id)"
              @click.prevent="saveStateAndRedirect($el.href)"
              class="text-indigo-600 hover:text-indigo-900 font-medium"
            >
              Edit
            </a>
          @endcan
        </td>
      </tr>
    </x-slot>
  </x-admin.live-table>
</x-layouts.admin>
