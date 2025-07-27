<?php

namespace App\Models;

// 1. Import the base Permission model from the Spatie package

use App\Enums\Status;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Permission as SpatiePermission;

/**
 * Custom Permission model that extends the base Spatie Permission model.
 */
class Permission extends SpatiePermission
{
    use LogsActivity;

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
            ->logOnly(['name', 'description', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $eventName) => "Permission has been {$eventName}");
    }
}
