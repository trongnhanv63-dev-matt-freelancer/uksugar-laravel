<?php

namespace App\Livewire\Auth;

use App\Domains\Auth\Actions\LoginAction;
use App\Domains\Auth\DTO\LoginData;
use App\Domains\Auth\Exceptions\InvalidCredentialsException;
use Livewire\Component;

class LoginForm extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    public function render()
    {
        return view('livewire.auth.login-form');
    }

    public function login(LoginAction $loginAction)
    {
        $this->validate();

        try {
            $loginAction->execute(new LoginData(
                $this->email,
                $this->password,
                $this->remember
            ));

            return redirect()->intended(route('home'));
        } catch (InvalidCredentialsException $e) {
            $this->addError('email', $e->getMessage());
        }
    }
}
