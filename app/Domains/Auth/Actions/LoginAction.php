<?php

namespace App\Domains\Auth\Actions;

use App\Domains\Auth\DTO\LoginData;
use App\Domains\Auth\Exceptions\InvalidCredentialsException;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginAction
{
    public function execute(LoginData $data): User
    {
        if (!Auth::attempt([
            'email' => $data->email,
            'password' => $data->password,
        ], $data->remember)) {
            throw new InvalidCredentialsException();
        }

        /** @var User $user */
        $user = Auth::user();

        return $user;
    }
}
