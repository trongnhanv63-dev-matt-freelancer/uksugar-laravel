<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('users.edit');
    }

    public function rules(): array
    {
        $userId = $this->route('user')->id;
        return [
            'name' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:20', 'unique:users,username,' . $userId],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users,email,' . $userId],
            'password' => ['nullable', 'string', Password::min(8)->symbols()->mixedCase()->numbers()->uncompromised()],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['exists:roles,id'],
            'status' => ['required', 'string'],
        ];
    }
}
