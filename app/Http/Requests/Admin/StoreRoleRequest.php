<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('role_management.create') ?? false;
    }

    public function rules(): array
    {
        return [
            'role_name' => ['required', 'string', 'max:100', Rule::unique('roles', 'role_name')],
            'description' => ['required', 'string', 'max:191'],
            'permission_ids' => ['sometimes', 'array'],
            'permission_ids.*' => [
                'integer',
                'distinct',
                Rule::exists('permissions', 'permission_id')->where(fn ($query) => $query->where('is_active', true)->where('is_assignable', true)),
            ],
        ];
    }
}
