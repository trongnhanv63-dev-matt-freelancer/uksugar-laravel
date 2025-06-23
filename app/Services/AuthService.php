<?php

namespace App\Services;

use App\Enums\UserStatus;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Auth\Events\Registered; // Import Auth facade
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
        // 1. Find the user by email first.
        $user = $this->userRepository->findByEmail($credentials['email']);

        // 2. Check if user exists, password is correct, AND status is active.
        if ($user &&
            Hash::check($credentials['password'], $user->password) &&
            $user->status === UserStatus::Active) {
            // 3. If all checks pass, log the user in manually.
            Auth::login($user, $remember);
            return true;
        }

        // 4. If any check fails, return false.
        return false;
    }
}
