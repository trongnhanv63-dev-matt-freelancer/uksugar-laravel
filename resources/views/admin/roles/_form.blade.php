@csrf

@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Whoops!</strong>
        There were some problems with your input.
        <br />
        <br />
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Check if the form is for an existing role to lock the name field --}}
@php
    $isEditMode = isset($role);
    $isSuperAdmin = $isEditMode && $role->name === "super-admin";
@endphp

<div style="margin-bottom: 1rem">
    <label
        for="name"
        style="display: block"
    >
        Role Name (slug)
    </label>
    {{-- The role name (slug) is read-only after creation for ALL roles --}}
    <input
        type="text"
        name="name"
        id="name"
        value="{{ old("name", $role->name ?? "") }}"
        style="width: 100%; padding: 0.5rem"
        required
        @if($isEditMode) readonly @endif
    />
    <small>Cannot be changed after creation. e.g., 'editor', 'content-manager'.</small>
</div>

<div style="margin-bottom: 1rem">
    <label
        for="display_name"
        style="display: block"
    >
        Display Name
    </label>
    <input
        type="text"
        name="display_name"
        id="display_name"
        value="{{ old("display_name", $role->display_name ?? "") }}"
        style="width: 100%; padding: 0.5rem"
        required
    />
    <small>e.g., 'Editor', 'Content Manager'.</small>
</div>

<div style="margin-bottom: 1rem">
    <label
        for="description"
        style="display: block"
    >
        Description
    </label>
    <textarea
        name="description"
        id="description"
        rows="3"
        style="width: 100%; padding: 0.5rem"
    >
{{ old("description", $role->description ?? "") }}</textarea
    >
</div>

<div style="margin-bottom: 1rem">
    <strong>Permissions:</strong>
    <br />
    @if ($isSuperAdmin ?? false)
        <div
            class="alert alert-success"
            style="margin-top: 0.5rem"
        >
            Super Admin always has all permissions. Permissions cannot be changed.
        </div>
    @endif

    <div
        style="
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 0.5rem;
        "
    >
        @foreach ($permissions as $permission)
            @php
                $isPermissionActive = $permission->status === App\Enums\StatusEnum::Active;
                $isSuperAdminRole = isset($role) && $role->name === "super-admin";
            @endphp

            <div>
                <input
                    type="checkbox"
                    name="permissions[]"
                    value="{{ $permission->id }}"
                    id="perm_{{ $permission->id }}"
                    {{-- Super Admin has all checked. For other roles, check if relationship exists. --}}
                    @if ($isSuperAdminRole || (isset($role) && $role->permissions->contains($permission->id)))
                        checked
                    @endif
                    {{-- Disable checkbox if permission is inactive OR if editing the super admin role --}}
                    @if (! $isPermissionActive || $isSuperAdminRole)
                        disabled
                    @endif
                />
                <label
                    for="perm_{{ $permission->id }}"
                    style="{{ ! $isPermissionActive ? "color: #999; text-decoration: line-through;" : "" }}"
                >
                    {{ $permission->slug }}
                    @if (! $isPermissionActive)
                        (Inactive)
                    @endif
                </label>
            </div>
        @endforeach
    </div>
</div>

{{-- The submit button is now always enabled on the edit page --}}
<button
    type="submit"
    class="btn btn-primary"
>
    {{ $submitButtonText ?? "Save" }}
</button>
