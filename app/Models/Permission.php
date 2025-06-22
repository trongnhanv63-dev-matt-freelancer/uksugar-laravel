<?php

namespace App\Models;

use App\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    use HasFactory;

    public $timestamps = false; // Permissions might not need timestamps

    protected $fillable = [
        'slug',
        'description',
        'status',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'status' => StatusEnum::class, // Cast the status attribute to our enum
    ];

    /**
     * The roles that belong to the permission.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permissions');
    }
}
