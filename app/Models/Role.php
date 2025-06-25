<?php

namespace App\Models;

// 1. Import the base Role model from the Spatie package
use Spatie\Permission\Models\Role as SpatieRole;

/**
 * Custom Role model that extends the base Spatie Role model.
 * This allows us to add custom logic, relationships, or traits in the future.
 */
class Role extends SpatieRole
{
    // For now, this model can be empty. We are just establishing the inheritance.
    // In the future, you can add `use LogsActivity;` here.
}
