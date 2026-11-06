<?php

namespace App\Services\Admin;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Services\Audit\AuditLogService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class RoleManagementService
{
    public function __construct(private readonly AuditLogService $audit)
    {
    }

    public function create(array $data, User $actor): Role
    {
        abort_unless($actor->isSuperAdmin(), 403, 'Hanya Super Admin yang dapat membuat role.');

        return DB::transaction(function () use ($data, $actor) {
            $role = Role::query()->create([
                'role_name' => $data['role_name'],
                'description' => $data['description'],
                'is_system_role' => false,
                'is_super_admin' => false,
                'is_active' => true,
                'created_by' => $actor->user_id,
            ]);

            $this->syncPermissions($role, $data['permission_ids'] ?? [], $actor);

            $this->audit->record($actor, 'CREATE_ROLE', 'ROLES', $role->role_id,
                'Role baru dibuat.', [], [
                    'role_name' => $role->role_name,
                    'description' => $role->description,
                    'permission_ids' => $role->permissions()->pluck('permissions.permission_id')->all(),
                ]);

            return $role->load('permissions');
        });
    }

    public function update(Role $role, array $data, User $actor): Role
    {
        abort_unless($actor->isSuperAdmin(), 403, 'Hanya Super Admin yang dapat mengatur role dan permission.');

        if ($role->is_super_admin) {
            throw new AccessDeniedHttpException('Pengaturan role Super Admin dikunci karena akses penuhnya ditentukan oleh sistem.');
        }

        return DB::transaction(function () use ($role, $data, $actor) {
            $role = Role::query()->whereKey($role->role_id)->lockForUpdate()->firstOrFail();
            if (!$role->is_active || $role->deleted_at) {
                throw ValidationException::withMessages(['role' => 'Role sudah tidak aktif.']);
            }

            $old = [
                'role_name' => $role->role_name,
                'description' => $role->description,
                'permission_ids' => $role->permissions()->pluck('permissions.permission_id')->all(),
            ];

            if (!$role->is_system_role) {
                $role->role_name = $data['role_name'];
            }
            $role->description = $data['description'];
            $role->save();

            $this->syncPermissions($role, $data['permission_ids'] ?? [], $actor);

            $new = [
                'role_name' => $role->role_name,
                'description' => $role->description,
                'permission_ids' => $role->permissions()->pluck('permissions.permission_id')->all(),
            ];

            $this->audit->record($actor, 'UPDATE_ROLE', 'ROLES', $role->role_id,
                'Nama, deskripsi, atau permission role diperbarui.', $old, $new);

            return $role->load('permissions');
        });
    }

    public function softDelete(Role $role, string $reason, User $actor): void
    {
        abort_unless($actor->isSuperAdmin(), 403, 'Hanya Super Admin yang dapat menonaktifkan role.');

        if ($role->is_system_role || $role->is_super_admin) {
            throw ValidationException::withMessages(['role' => 'Role bawaan tidak dapat dihapus.']);
        }

        DB::transaction(function () use ($role, $reason, $actor) {
            $role = Role::query()->whereKey($role->role_id)->lockForUpdate()->firstOrFail();

            if ($role->users()->exists()) {
                throw ValidationException::withMessages(['role' => 'Role masih digunakan oleh pengguna. Pindahkan pengguna ke role lain terlebih dahulu.']);
            }

            $old = ['is_active' => $role->is_active, 'deleted_at' => $role->deleted_at];
            $role->is_active = false;
            $role->deleted_at = now();
            $role->deleted_by = $actor->user_id;
            $role->deletion_reason = $reason;
            $role->save();

            $this->audit->record($actor, 'SOFT_DELETE_ROLE', 'ROLES', $role->role_id,
                'Role kustom dihapus sementara.', $old, ['is_active' => false, 'deleted_at' => now()->toDateTimeString()], $reason);
        });
    }

    private function syncPermissions(Role $role, array $permissionIds, User $actor): void
    {
        $permissionIds = array_values(array_unique(array_map('intval', $permissionIds)));
        $permissions = Permission::query()
            ->whereIn('permission_id', $permissionIds)
            ->where('is_active', true)
            ->where('is_assignable', true)
            ->get();

        if ($permissions->count() !== count($permissionIds)) {
            throw ValidationException::withMessages(['permission_ids' => 'Daftar permission berisi izin yang tidak aktif atau tidak dapat diberikan.']);
        }

        $sync = [];
        foreach ($permissions as $permission) {
            $sync[$permission->permission_id] = [
                'granted_by' => $actor->user_id,
                'granted_at' => now(),
            ];
        }

        $role->permissions()->sync($sync);
    }
}
