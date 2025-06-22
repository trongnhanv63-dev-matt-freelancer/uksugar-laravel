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

{{-- User details grid --}}
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem">
    <div>
        <label
            for="username"
            style="display: block"
        >
            Username
        </label>
        <input
            type="text"
            name="username"
            id="username"
            value="{{ old('username', $user->username ?? '') }}"
            style="width: 100%; padding: 0.5rem"
            required
        />
    </div>
    <div>
        <label
            for="email"
            style="display: block"
        >
            Email
        </label>
        <input
            type="email"
            name="email"
            id="email"
            value="{{ old('email', $user->email ?? '') }}"
            style="width: 100%; padding: 0.5rem"
            required
        />
    </div>
</div>

{{-- Status Section --}}
<div style="margin-top: 1rem">
    <label
        for="status"
        style="display: block"
    >
        Status
    </label>
    @php
        // Check if the user being edited is the current user or a super admin
        $isProtectedUser = isset($user) && (auth()->id() === $user->id || $user->is_super_admin);
    @endphp

    @if ($isProtectedUser)
        {{-- If protected, show a read-only input and a hidden input --}}
        <input
            type="text"
            style="width: 100%; padding: 0.5rem; background-color: #e9ecef; color: #6c757d"
            value="{{ ucfirst($user->status->value) }}"
            readonly
        />
        <input
            type="hidden"
            name="status"
            value="{{ $user->status->value }}"
        />
        <small>You cannot change the status of the Super Admin or your own account.</small>
    @else
        {{-- If not protected, show the regular select box --}}
        <select
            name="status"
            id="status"
            style="width: 100%; padding: 0.5rem"
        >
            @foreach (App\Enums\UserStatus::cases() as $status)
                {{-- Don't show some internal statuses like 'locked' in the dropdown --}}
                @if (in_array($status->value, ['locked']))
                    @continue
                @endif

                <option
                    value="{{ $status->value }}"
                    @selected(old('status', $user->status->value ?? '') == $status->value)
                >
                    {{ ucfirst(str_replace('_', ' ', $status->value)) }}
                </option>
            @endforeach
        </select>
    @endif
</div>

{{-- Password section with toggle button (Requirement 2 & 3) --}}
<div style="margin-top: 1rem">
    @if (isset($user))
        {{-- This block shows on the EDIT form --}}
        <div id="password-placeholder">
            <label style="display: block">Password</label>
            <input
                type="text"
                style="width: 100%; padding: 0.5rem; background-color: #e9ecef; color: #6c757d"
                value="••••••••••"
                readonly
            />
            <button
                type="button"
                onclick="showPasswordFields()"
                class="btn btn-primary"
                style="margin-top: 0.5rem; background-color: #6c757d; font-size: 0.8rem; padding: 0.25rem 0.5rem"
            >
                Change Password
            </button>
        </div>
        <div
            id="password-fields"
            style="display: none"
        >
            <label
                for="password"
                style="display: block"
            >
                New Password
            </label>
            <input
                type="password"
                name="password"
                id="password"
                style="width: 100%; padding: 0.5rem"
            />
            <small>Leave blank to keep the current password.</small>
        </div>
    @else
        {{-- This block shows on the CREATE form --}}
        <div>
            <label
                for="password"
                style="display: block"
            >
                Password
            </label>
            <input
                type="password"
                name="password"
                id="password"
                style="width: 100%; padding: 0.5rem"
                required
            />
        </div>
    @endif
</div>

{{-- Roles Section --}}
<div style="margin-top: 1rem">
    <strong>Roles:</strong>
    <br />
    <div
        style="
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 0.5rem;
        "
    >
        @foreach ($roles as $role)
            @php
                $isSuperAdminRole = $role->name === 'super-admin';
                $userHasRole = isset($user) && $user->roles->contains($role->id);
            @endphp

            <div>
                <input
                    type="checkbox"
                    name="roles[]"
                    value="{{ $role->id }}"
                    id="role_{{ $role->id }}"
                    @if($userHasRole) checked @endif
                    @if($isSuperAdminRole) disabled @endif
                />
                <label for="role_{{ $role->id }}">{{ $role->display_name }}</label>
                @if ($isSuperAdminRole && $userHasRole)
                    <small>(Cannot be unassigned)</small>
                @endif
            </div>
        @endforeach
    </div>
</div>

<div style="margin-top: 2rem">
    <button
        type="submit"
        class="btn btn-primary"
    >
        {{ $submitButtonText ?? 'Save' }}
    </button>
</div>

{{-- JavaScript for toggling password fields (Requirement 3) --}}
@if (isset($user))
    <script>
        function showPasswordFields() {
            document.getElementById('password-placeholder').style.display = 'none';
            document.getElementById('password-fields').style.display = 'block';
            document.getElementById('password').focus();
        }
    </script>
@endif
