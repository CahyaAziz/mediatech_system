<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('role_management.update') ?? false;
    }

    public function rules(): array
    {
        $target = $this->route('role');
        $roleId = is_object($target) ? $target->role_id : $target;

        return [
            'role_name' => ['required', 'string', 'max:100', Rule::unique('roles', 'role_name')->ignore($roleId, 'role_id')],
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
