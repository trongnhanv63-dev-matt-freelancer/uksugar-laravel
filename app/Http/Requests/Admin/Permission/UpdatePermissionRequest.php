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
        $permissionId = $this->route('permission')->id;
        return [
            'name' => ['required', 'string', 'max:100', 'unique:permissions,name,' . $permissionId],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }
}
