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
    public function getPaginatedUsers(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = $this->model->with('roles')->withCount('roles');

        $this->applyFilters($query, $filters);
        $this->applySorting($query, $filters);

        return $query->paginate($perPage);
    }

    /**
     * Apply search, role, and status filters to the query.
     */
    private function applyFilters($query, array $filters): void
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(fn ($q) => $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
        })
        ->when($filters['role'] ?? null, function ($query, $role) {
            $query->whereHas('roles', fn ($q) => $q->where('name', $role));
        })
        ->when($filters['status'] ?? null, function ($query, $status) {
            $query->where('status', $status);
        });
    }

    /**
     * Apply dynamic sorting to the query.
     */
    private function applySorting($query, array $filters): void
    {
        $sortBy = $filters['sort_by'] ?? 'id';
        $sortDirection = $filters['sort_direction'] ?? 'desc';

        $sortableColumns = ['id', 'name', 'email', 'status', 'roles_count'];
        if (! in_array($sortBy, $sortableColumns)) {
            $sortBy = 'id';
        }

        $query->orderBy($sortBy, $sortDirection);
    }
}
