<?php

namespace NhanDev\Rbac\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use NhanDev\Rbac\Models\ActivityLog;

trait LogsActivity
{
    /**
     * Boot the trait to register Eloquent event listeners.
     * This method is automatically called when a model using this trait is booted.
     */
    protected static function bootLogsActivity(): void
    {
        // We listen for the 'created', 'updated', and 'deleted' Eloquent events.
        foreach (['created', 'updated', 'deleted'] as $eventName) {
            static::$eventName(function (Model $model) use ($eventName) {
                $model->logActivity($eventName);
            });
        }
    }

    /**
     * The main method to log the activity for the model.
     *
     * @param string $eventName The name of the Eloquent event (created, updated, deleted).
     */
    public function logActivity(string $eventName): void
    {
        // For the 'updated' event, we only want to log if meaningful attributes have changed.
        // If getLogProperties() returns null, it means only timestamps changed, so we skip logging.
        if ($eventName === 'updated' && is_null($this->getLogProperties('updated'))) {
            return;
        }

        ActivityLog::create([
            'description' => $this->getActivityLogDescription($eventName),
            'causer_type' => Auth::check() ? get_class(Auth::user()) : null,
            'causer_id' => Auth::id(),
            'subject_type' => get_class($this),
            'subject_id' => $this->getKey(),
            'properties' => $this->getLogProperties($eventName),
        ]);
    }

    /**
     * Prepare the description for the activity log.
     * e.g., "created the role 'editor'", "updated the user 'nhan'".
     *
     * @param string $eventName
     * @return string
     */
    protected function getActivityLogDescription(string $eventName): string
    {
        $modelName = strtolower(class_basename($this));
        return sprintf(
            "%s the %s '%s'.",
            ucfirst($eventName),
            $modelName,
            $this->getLogSubjectName()
        );
    }

    /**
     * Get the name/identifier of the subject model to be used in the log description.
     * Models using this trait can override this method if their "name" column is different.
     *
     * @return string
     */
    public function getLogSubjectName(): string
    {
        return $this->name ?? $this->slug ?? $this->username ?? $this->id;
    }

    /**
     * Prepare the properties (like old and new values) to be stored in the log.
     *
     * @param string $eventName
     * @return array|null
     */
    protected function getLogProperties(string $eventName): ?array
    {
        if ($eventName !== 'updated') {
            return null;
        }

        // Get only the attributes that have actually changed.
        $changes = Arr::except($this->getChanges(), ['updated_at', 'created_at']);

        // If there are no changes other than timestamps, we don't need to log properties.
        if (empty($changes)) {
            return null;
        }

        return [
            'old' => Arr::only($this->getOriginal(), array_keys($changes)),
            'new' => $changes,
        ];
    }
}
