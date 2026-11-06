<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) ($this->user()?->isSuperAdmin() && $this->user()?->can('users.create') && $this->user()?->can('users.assign_roles'));
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:180'],
            'email' => ['required', 'email:rfc', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'confirmed', 'string', 'min:12', 'max:255'],
            'is_active' => ['required', 'boolean'],
            'role_ids' => ['required', 'array', 'min:1'],
            'role_ids.*' => [
                'integer',
                'distinct',
                Rule::exists('roles', 'role_id')->where(fn ($query) => $query->where('is_active', true)->whereNull('deleted_at')),
            ],
        ];
    }
}
