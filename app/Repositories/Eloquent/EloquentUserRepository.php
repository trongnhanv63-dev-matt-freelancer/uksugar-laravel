<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

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
    protected $model;

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
}
