<?php

namespace App\Livewire\Auth;

use App\Domains\Auth\Actions\LogoutAction;
use Livewire\Component;

class Logout extends Component
{
    public function logout(LogoutAction $logoutAction)
    {
        $logoutAction->execute();
        
        return redirect()->route('login');
    }

    public function render()
    {
        return view('livewire.auth.logout');
    }
}