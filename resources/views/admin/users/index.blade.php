<x-layouts.admin>
  <x-slot:title>User Management</x-slot>

  @php
    $columns = [['label' => 'User', 'key' => 'name', 'sortable' => true], ['label' => 'Roles', 'key' => 'roles', 'sortable' => false], ['label' => 'Status', 'key' => 'status', 'sortable' => false], ['label' => 'Last Login', 'key' => 'last_login_at', 'sortable' => true]];

    $filters = [
      ['type' => 'search', 'key' => 'search', 'placeholder' => 'Search by name or email...'],
      ['type' => 'select', 'key' => 'role', 'id' => 'role-filter-select', 'placeholder' => 'All Roles', 'options' => $roles->map(fn ($r) => ['value' => $r->name, 'text' => $r->name])->all()],
      [
        'type' => 'select',
        'key' => 'status',
        'id' => 'status-filter-select',
        'placeholder' => 'All Statuses',
        'options' => collect(App\Enums\UserStatus::cases())
          ->map(fn ($s) => ['value' => $s->value, 'text' => Str::headline($s->name)])
          ->all(),
      ],
    ];
  @endphp

  <x-admin.live-table
    title="User Management"
    description="Manage all system users, their roles, and statuses."
    :columns="$columns"
    :filters="$filters"
    :initialData="$users->toArray()"
    :apiUrl="route('admin.api.users.index')"
    :createUrl="route('admin.users.create')"
    createPermission="users.create"
  >
    <tr class="hover:bg-gray-50">
      <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap border-r border-gray-200">
        <div class="flex items-center">
          <div class="flex-shrink-0 h-10 w-10">
            <div
              class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center font-bold text-gray-500"
              x-text="item.name.charAt(0)"
            ></div>
          </div>
          <div class="ml-4">
            <div
              class="text-base font-semibold text-gray-900"
              x-text="item.name"
            ></div>
            <div
              class="text-sm text-gray-500"
              x-text="item.email"
            ></div>
          </div>
        </div>
      </td>
      <td class="px-6 py-4 border-r border-gray-200">
        <template x-if="item.roles.length > 0">
          <div class="flex flex-wrap gap-1">
            <template
              x-for="role in item.roles"
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
          :class="{ 'bg-green-100 text-green-700': item.status === 'active', 'bg-yellow-100 text-yellow-800': item.status === 'inactive', 'bg-red-100 text-red-800': item.status === 'suspended' }"
        >
          <svg
            class="h-1.5 w-1.5"
            viewBox="0 0 6 6"
            :class="{ 'fill-green-500': item.status === 'active', 'fill-yellow-500': item.status === 'inactive', 'fill-red-500': item.status === 'suspended'}"
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
        x-text="item.last_login_at ? new Date(item.last_login_at).toLocaleString() : 'Never'"
      ></td>
      <td class="px-6 py-4">
        @can('users.edit')
          <a
            :href="`/admin/users/${item.id}/edit`"
            class="font-medium text-purple-600 hover:text-purple-900"
          >
            Edit
          </a>
        @endcan
      </td>
    </tr>
  </x-admin.live-table>
</x-layouts.admin>
