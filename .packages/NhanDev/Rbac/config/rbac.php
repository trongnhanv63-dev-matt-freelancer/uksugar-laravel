<?php

return [
    /*
    |--------------------------------------------------------------------------
    | User Model
    |--------------------------------------------------------------------------
    | The user model used by the RBAC package. You can override this with
    | your own User model, which must use the HasRolesAndPermissions trait.
    */
    'user_model' => \App\Models\User::class,

    /*
    |--------------------------------------------------------------------------
    | Core Models
    |--------------------------------------------------------------------------
    | Models provided by the package. Users can extend and override them here.
    */
    'models' => [
        'role' => \NhanDev\Rbac\Models\Role::class,
        'permission' => \NhanDev\Rbac\Models\Permission::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Repositories
    |--------------------------------------------------------------------------
    | Bindings for repository interfaces.
    */
    'repositories' => [
        'user' => \NhanDev\Rbac\Repositories\Eloquent\EloquentUserRepository::class,
        'role' => \NhanDev\Rbac\Repositories\Eloquent\EloquentRoleRepository::class,
        'permission' => \NhanDev\Rbac\Repositories\Eloquent\EloquentPermissionRepository::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Status Values
    |--------------------------------------------------------------------------
    | Define the string values for various statuses used in the package.
    | Users can override these values or add new ones.
    */
    'user_statuses' => [
        'pending_verification' => 'pending_verification',
        'active' => 'active',
        'inactive' => 'inactive',
        'suspended' => 'suspended',
        // Add more statuses here...
    ],

    'role_statuses' => [
        'active' => 'active',
        'inactive' => 'inactive',
        // Add more statuses here...
    ],

    'permission_statuses' => [
        'active' => 'active',
        'inactive' => 'inactive',
        // Add more statuses here...
    ],
];
