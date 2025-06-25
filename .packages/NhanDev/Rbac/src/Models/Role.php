<?php

namespace NhanDev\Rbac\Models; // Updated namespace

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use NhanDev\Rbac\Models\Traits\LogsActivity; // 1. Import the new trait

class Role extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = ['name', 'display_name', 'description', 'status'];

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(config('rbac.user_model'), 'user_roles');
    }
}
