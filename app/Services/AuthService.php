<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth; // Import Auth facade
use Illuminate\Auth\Events\Registered;

class AuthService
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function registerUser(array $validatedData): User
    {
        $userData = [
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
        ];

        $user = $this->userRepository->create($userData);

        event(new Registered($user));

        return $user;
    }

    /**
     * Attempt to authenticate a user.
     *
     * @param array<string, string> $credentials
     * @param bool $remember
     * @return bool
     */
    public function attemptLogin(array $credentials, bool $remember = false): bool
    {
        // Use Laravel's built-in authentication attempt
        if (Auth::attempt($credentials, $remember)) {
            return true;
        }

        return false;
    }
}
