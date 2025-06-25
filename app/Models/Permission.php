<?php

namespace App\Models;

// 1. Import the base Permission model from the Spatie package
use Spatie\Permission\Models\Permission as SpatiePermission;

/**
 * Custom Permission model that extends the base Spatie Permission model.
 */
class Permission extends SpatiePermission
{
    // In the future, you can add `use LogsActivity;` here.
}
