<?php

namespace App\Models;

// 1. Import the base Role model from the Spatie package

use App\Enums\Status;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Role as SpatieRole;

/**
 * Custom Role model that extends the base Spatie Role model.
 * This allows us to add custom logic, relationships, or traits in the future.
 */
class Role extends SpatieRole
{
    use LogsActivity;
    // For now, this model can be empty. We are just establishing the inheritance.
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'description', 'status', 'permissions'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $eventName) => "Role has been {$eventName}");
    }
}
