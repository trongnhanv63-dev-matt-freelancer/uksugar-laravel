<?php

namespace App\Domains\Auth\DTO;

use Spatie\LaravelData\Data;

class LoginData extends Data
{
    public string $email;
    public string $password;
    public bool $remember;

    public function __construct(
        string $email,
        string $password,
        bool $remember
    ) {
        $this->email = $email;
        $this->password = $password;
        $this->remember = $remember;
    }
}
