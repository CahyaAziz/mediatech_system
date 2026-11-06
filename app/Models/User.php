<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'email_verified_at',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'user_roles',
            'user_id',
            'role_id',
            'user_id',
            'role_id'
        )->withPivot(['assigned_by', 'assigned_at']);
    }

    public function isSuperAdmin(): bool
    {
        if (!$this->exists || !$this->is_active) {
            return false;
        }

        return $this->roles()
            ->where('roles.is_super_admin', true)
            ->where('roles.is_active', true)
            ->whereNull('roles.deleted_at')
            ->exists();
    }

    public function hasPermissionTo(string $permissionCode): bool
    {
        if (!$this->exists || !$this->is_active) {
            return false;
        }

        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->roles()
            ->where('roles.is_active', true)
            ->whereNull('roles.deleted_at')
            ->whereHas('permissions', function ($query) use ($permissionCode) {
                $query->where('permissions.permission_code', $permissionCode)
                    ->where('permissions.is_active', true);
            })
            ->exists();
    }
}
