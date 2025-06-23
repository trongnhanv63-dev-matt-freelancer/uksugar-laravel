<?php

namespace NhanDev\Rbac\Repositories\Eloquent;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use NhanDev\Rbac\Repositories\Contracts\UserRepositoryInterface;

/**
 * The Eloquent implementation of the UserRepositoryInterface.
 * This class handles all the database calls for the User model.
 */
class EloquentUserRepository implements UserRepositoryInterface
{
    /**
     * The Eloquent User model.
     *
     * @var User
     */
    protected User $model;

    /**
     * EloquentUserRepository constructor.
     *
     * @param User $model
     */
    public function __construct(User $model)
    {
        $this->model = $model;
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
    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    /**
     * {@inheritdoc}
     */
    public function getAllWithRoles(): Collection
    {
        // Eager load roles to prevent N+1 query problem on the index page
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
    public function update(int $id, array $attributes): User
    {
        $user = $this->findById($id);
        if ($user) {
            $user->update($attributes);
        }
        return $user->fresh(); // Use fresh() for consistency
    }
}
