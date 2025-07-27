<?php

namespace App\Http\Requests\Admin\Permission;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('permissions.edit');
    }

    public function rules(): array
    {
        return [
            'description' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string'],
        ];
    }
}
