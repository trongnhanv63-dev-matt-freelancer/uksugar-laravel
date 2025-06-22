<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

/**
 * Service class for handling authentication-related business logic.
 */
class AuthService
{
    /**
     * The user repository instance.
     * We inject the interface, and Laravel's service container provides the concrete implementation.
     *
     * @var UserRepositoryInterface
     */
    protected $userRepository;

    /**
     * AuthService constructor.
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Handle the logic for registering a new user.
     *
     * @param array<string, mixed> $validatedData
     * @return User
     */
    public function registerUser(array $validatedData): User
    {
        // 1. Prepare data for creation
        $userData = [
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            // The 'password' attribute is automatically hashed by the User model's cast.
            'password' => $validatedData['password'],
        ];

        // 2. Call the repository to create the user
        $user = $this->userRepository->create($userData);

        // 3. Dispatch an event after user is created.
        // Laravel's listeners can handle tasks like sending a verification email.
        event(new Registered($user));

        // 4. Return the newly created user object
        return $user;
    }

    // We will add other methods like login(), logout(), etc. here later.
}
