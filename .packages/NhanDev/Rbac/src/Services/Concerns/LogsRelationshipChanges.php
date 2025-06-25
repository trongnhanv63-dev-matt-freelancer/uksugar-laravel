<?php

namespace NhanDev\Rbac\Services\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use NhanDev\Rbac\Models\ActivityLog;

trait LogsRelationshipChanges
{
    /**
     * Log the changes after a sync operation on a many-to-many relationship.
     *
     * @param \Illuminate\Database\Eloquent\Model $subject The model being updated (e.g., a User or a Role)
     * @param string $description The human-readable log description
     * @param array $changes The changes array from the sync() method, containing 'attached' and 'detached' keys.
     */
    protected function logSyncActivity(Model $subject, string $description, array $changes): void
    {
        // Only create a log entry if there were actual changes.
        if (empty($changes['attached']) && empty($changes['detached'])) {
            return;
        }

        $causer = Auth::user();

        ActivityLog::create([
            'description' => $description,
            'causer_type' => $causer ? get_class($causer) : null,
            'causer_id' => $causer ? $causer->id : null,
            'subject_type' => get_class($subject),
            'subject_id' => $subject->id,
            'properties' => [
                'attached' => $changes['attached'],
                'detached' => $changes['detached'],
            ],
        ]);
    }
}
