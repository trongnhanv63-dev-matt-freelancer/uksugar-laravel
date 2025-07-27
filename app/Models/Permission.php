<?php

namespace App\Models;

// 1. Import the base Permission model from the Spatie package

use App\Enums\Status;
use Spatie\Permission\Models\Permission as SpatiePermission;

/**
 * Custom Permission model that extends the base Spatie Permission model.
 */
class Permission extends SpatiePermission
{
    // In the future, you can add `use LogsActivity;` here.

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => Status::class,
        ];
    }
}
