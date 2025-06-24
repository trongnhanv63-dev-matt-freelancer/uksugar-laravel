<?php

namespace NhanDev\Rbac\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'properties' => 'collection', // Automatically cast the JSON column to a helpful Laravel Collection
    ];

    /**
     * Get the user that caused the activity.
     */
    public function causer(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the model that was the subject of the activity.
     */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }
}
