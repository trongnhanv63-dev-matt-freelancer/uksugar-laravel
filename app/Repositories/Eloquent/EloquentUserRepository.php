<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
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
        return $this->model->with('roles')->latest('id')->get();
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
    public function update(int $id, array $attributes): User
    {
        $user = $this->findById($id);
        if ($user) {
            $user->update($attributes);
        }
        // Return a fresh instance to ensure the returned model has the latest data.
        return $user->fresh();
    }
}
