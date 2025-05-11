<?php

namespace App\Domains\Auth\Actions;

use Illuminate\Support\Facades\Auth;

class LogoutAction
{
    public function execute(): void
    {
        Auth::logout();
        
        session()->invalidate();
        session()->regenerateToken();
    }
}