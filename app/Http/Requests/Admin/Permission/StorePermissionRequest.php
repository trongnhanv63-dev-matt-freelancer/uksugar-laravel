<?php
namespace App\Http\Requests\Admin\Permission;

use Illuminate\Foundation\Http\FormRequest;

class StorePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('permissions.create');
    }

    public function rules(): array
    {
        return [
            'slug' => ['required', 'string', 'unique:permissions,slug', 'max:100'],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }
} 