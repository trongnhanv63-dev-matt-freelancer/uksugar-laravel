<?php

namespace NhanDev\Rbac\Models; // Updated namespace

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use NhanDev\Rbac\Models\Traits\LogsActivity;

class Permission extends Model
{
    use HasFactory;
    use LogsActivity;

    public $timestamps = false;
    protected $fillable = ['slug', 'description', 'status'];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permissions');
    }
}
