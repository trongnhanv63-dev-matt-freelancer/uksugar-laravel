<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\StatusEnum;
use App\Enums\UserStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'status',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'status' => UserStatus::class, // Cast status to our Enum
        ];
    }

    /**
     * The roles that belong to the user.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    /**
     * Determine if the user is a super admin.
     *
     * This is an accessor, allowing you to access this logic
     * via a virtual property: $user->is_super_admin
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function isSuperAdmin(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->hasRole('super-admin'),
        );
    }

    /**
     * Check if the user has a specific permission, either directly or through roles.
     */
    public function hasPermissionTo(string $permissionSlug): bool
    {
        // First, check if the user has the 'super-admin' role.
        // If so, they bypass all other permission checks.
        if ($this->is_super_admin) {
            return true;
        }

        // Use whereHas for an efficient database query.
        // This checks for the existence of a relationship matching constraints.
        return $this->roles()
            ->where('status', StatusEnum::Active) // 1. The role itself must be active
            ->whereHas('permissions', function ($query) use ($permissionSlug) {
                $query
                    ->where('slug', $permissionSlug)
                    ->where('status', StatusEnum::Active); // 2. The permission attached to that role must also be active
            })
            ->exists(); // Returns true if at least one such role/permission link exists
    }

    /**
     * Check if the user has a specific role.
     */
    public function hasRole(string $roleName): bool
    {
        return $this->roles->contains('name', $roleName);
    }
}
