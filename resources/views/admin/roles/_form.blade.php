@php
  $isEditMode = isset($role);
@endphp

@csrf
{{-- Display validation errors --}}
@if ($errors->any())
  <div
    class="bg-red-50 border-l-4 border-red-400 text-red-700 p-4 mb-6 rounded-md"
    role="alert"
  >
    <p class="font-bold">Please correct the following errors:</p>
    <ul class="mt-2 list-disc list-inside text-sm">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

<div class="space-y-6">
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    {{-- Role Name --}}
    <x-admin.form.input
      name="name"
      label="Role Name"
      :value="$role->name ?? ''"
      :disabled="$isEditMode"
      required
    />

    {{-- Status --}}
    <x-admin.form.select
      name="status"
      id="status-select"
      label="Status"
    >
      @foreach (App\Enums\Status::cases() as $status)
        <option
          value="{{ $status->value }}"
          @selected(old('status', $isEditMode ? $role->status->value : 'active') == $status->value)
        >
          {{ Str::headline($status->name) }}
        </option>
      @endforeach
    </x-admin.form.select>
  </div>

  {{-- Description --}}
  <x-admin.form.textarea
    name="description"
    label="Description"
    :value="$role->description ?? ''"
    placeholder="A brief description of this role's purpose"
  />

  {{-- Permissions Section --}}
  <div class="pt-6 border-t border-gray-200">
    <h3 class="text-base font-semibold leading-6 text-gray-900 mb-4">Assign Permissions</h3>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
      @foreach ($permissions as $group => $permissionList)
        @php
          $groupIdList = collect($permissionList)
            ->pluck('id')
            ->map(fn ($id) => (string) $id);
        @endphp

        <div
          class="border border-gray-200 rounded-lg p-4"
          x-data="permissionGroup('{{ $group }}', @js($groupIdList))"
          x-init="init()"
        >
          <div class="mb-3 flex items-center">
            <input
              type="checkbox"
              :id="groupCheckboxId"
              @change="toggleGroup"
              class="h-4 w-4 text-purple-600 rounded border-gray-300 focus:ring-purple-600"
            />
            <label
              :for="groupCheckboxId"
              class="ml-2 font-semibold text-gray-700 capitalize cursor-pointer"
            >
              {{ $group }}
            </label>
          </div>

          <div class="space-y-2">
            @foreach ($permissionList as $permission)
              @php
                $permissionIsActive = $permission->status === App\Enums\Status::Active;
                $isChecked = isset($rolePermissions) && in_array($permission->id, $rolePermissions);
              @endphp

              <div class="relative flex items-start">
                <div class="flex h-6 items-center">
                  <input
                    id="perm_{{ $permission->id }}"
                    name="permissions[]"
                    type="checkbox"
                    value="{{ $permission->id }}"
                    @if($isChecked) checked @endif
                    @if(!$permissionIsActive) disabled @endif
                    class="h-4 w-4 rounded border-gray-300 text-purple-600 focus:ring-purple-600 disabled:opacity-50"
                    @change="onPermissionChange"
                  />
                </div>
                <div class="ml-3 text-sm leading-6">
                  <label
                    for="perm_{{ $permission->id }}"
                    class="{{ ! $permissionIsActive ? 'text-gray-400 line-through' : 'text-gray-700' }} cursor-pointer"
                  >
                    {{ $permission->name }}
                  </label>
                  @if ($permission->description)
                    <p class="text-gray-500 text-xs mt-1">{{ $permission->description }}</p>
                  @endif
                </div>
              </div>
            @endforeach
          </div>
        </div>
      @endforeach
    </div>
  </div>

  {{-- Form Actions --}}
  <x-admin.form.actions
    :submit-button-text="$submitButtonText"
    :cancel-url="route('admin.roles.index')"
  />
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    if (window.TomSelect) {
      const el = document.getElementById('status-select');
      if (el && !el.disabled) {
        new TomSelect(el, {
          placeholder: 'Select a status...',
        });
      }
    }
  });
</script>

<script>
  window.permissionGroups = @json(
    collect($permissions)->mapWithKeys(function ($permissionList, $group) {
      return [
        $group => collect($permissionList)
          ->pluck('id')
          ->map(fn ($id) => (string) $id)
          ->values(),
      ];
    })
  );
</script>

<script>
  function permissionGroup(groupName, allIds) {
    return {
      groupName,
      allIds,
      checkedIds: [],

      init() {
        this.$nextTick(() => {
          this.checkedIds = this.allIds.filter((id) => {
            const checkbox = document.querySelector(`#perm_${id}`);
            return checkbox?.checked;
          });
          this.syncGroupCheckbox();
        });
      },

      toggleAll(checked) {
        this.checkedIds = checked ? [...this.allIds] : [];
        this.$nextTick(() => {
          this.allIds.forEach((id) => {
            const cb = document.querySelector(`#perm_${id}`);
            if (cb && !cb.disabled) {
              cb.checked = checked;
            }
          });
        });
      },

      syncGroupCheckbox() {
        const enabledIds = this.allIds.filter((id) => {
          const cb = document.querySelector(`#perm_${id}`);
          return cb && !cb.disabled;
        });

        const enabledChecked = enabledIds.filter((id) => {
          const cb = document.querySelector(`#perm_${id}`);
          return cb && cb.checked;
        });

        this.$refs.groupCheck.checked = enabledIds.length > 0 && enabledChecked.length === enabledIds.length;
      },
    };
  }
</script>
