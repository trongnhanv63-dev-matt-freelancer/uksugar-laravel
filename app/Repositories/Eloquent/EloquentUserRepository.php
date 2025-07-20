<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * The Eloquent implementation of the UserRepositoryInterface.
 * This class handles all the database calls for the User model.
 */
class EloquentUserRepository implements UserRepositoryInterface
{
    /**
     * The Eloquent User model instance.
     *
     * @var \App\Models\User
     */
    protected User $model;

    /**
     * EloquentUserRepository constructor.
     *
     * @param \App\Models\User $model
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * {@inheritdoc}
     */
    public function getAllWithRoles(): Collection
    {
        // Eager load the 'roles' relationship to prevent N+1 query problems on the index page.
        // Order by the latest created users.
        return $this->model->with('roles')->latest()->get();
    }

    /**
     * {@inheritdoc}
     */
    public function findById(int $id): ?User
    {
        return $this->model->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $attributes): User
    {
        return $this->model->create($attributes);
    }

    /**
     * {@inheritdoc}
     */
    public function update(User $user, array $attributes): User
    {
        $user->update($attributes);
        // Return a fresh instance to ensure the returned model has the latest data.
        return $user->fresh();
    }

    /**
     * {@inheritdoc}
     */
    public function getPaginatedUsers(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = User::with('roles');

        // Apply search filter for name or username
        if (!empty($filters['search'])) {
            $searchTerm = $filters['search'];
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('username', 'like', "%{$searchTerm}%");
            });
        }

        // Apply role filter
        if (!empty($filters['role'])) {
            $roleName = $filters['role'];
            $query->whereHas('roles', fn ($q) => $q->where('name', $roleName));
        }

        // --- NEW: Apply status filter ---
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Apply sorting
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';

        // Prevent sorting by roles relationship to avoid errors
        if ($sortBy !== 'roles') {
            $query->orderBy($sortBy, $sortDirection);
        }

        return $query->paginate($perPage);
    }
}
