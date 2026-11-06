<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    protected $table = 'permissions';
    protected $primaryKey = 'permission_id';
    public $timestamps = false;

    protected $fillable = [
        'permission_code',
        'module_code',
        'operation_code',
        'description',
        'is_assignable',
        'is_destructive_operation',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_assignable' => 'boolean',
            'is_destructive_operation' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'role_permissions',
            'permission_id',
            'role_id',
            'permission_id',
            'role_id'
        )->withPivot(['granted_by', 'granted_at']);
    }
}
