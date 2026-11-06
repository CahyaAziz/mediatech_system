<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('users.update') ?? false;
    }

    public function rules(): array
    {
        $target = $this->route('user');
        $userId = is_object($target) ? $target->user_id : $target;

        return [
            'name' => ['required', 'string', 'max:180'],
            'email' => ['required', 'email:rfc', 'max:255', Rule::unique('users', 'email')->ignore($userId, 'user_id')],
            'password' => ['nullable', 'confirmed', 'string', 'min:12', 'max:255'],
            'is_active' => ['required', 'boolean'],
            'role_ids' => $this->user()?->isSuperAdmin()
                ? ['required', 'array', 'min:1']
                : ['prohibited'],
            'role_ids.*' => [
                'integer',
                'distinct',
                Rule::exists('roles', 'role_id')->where(fn ($query) => $query->where('is_active', true)->whereNull('deleted_at')),
            ],
        ];
    }
}
