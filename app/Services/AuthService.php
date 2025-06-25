<?php

namespace App\Services;

use App\Enums\UserStatus;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Service class for handling authentication-related business logic.
 */
class AuthService
{
    /**
     * The user repository instance.
     */
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Attempt to authenticate a user based on credentials.
     * This method checks for user existence, password validity, and active status.
     *
     * @param array<string, string> $credentials
     * @param bool $remember
     * @return bool Returns true on successful authentication, false otherwise.
     */
    public function attemptLogin(array $credentials, bool $remember = false): bool
    {
        // 1. Find the user by their email address via the repository.
        $user = $this->userRepository->findByEmail($credentials['email']);

        // 2. Perform checks: user must exist, password must match, and status must be 'active'.
        if (
            $user &&
            Hash::check($credentials['password'], $user->password) &&
            $user->status === UserStatus::Active // We check for active status here.
        ) {
            // 3. If all checks pass, log the user in using Laravel's Auth facade.
            Auth::login($user, $remember);
            return true;
        }

        // 4. If any check fails, return false.
        return false;
    }

}
