<?php

namespace App\Services\Admin;

use App\Models\Role;
use App\Models\User;
use App\Services\Audit\AuditLogService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class UserManagementService
{
    public function __construct(private readonly AuditLogService $audit)
    {
    }

    public function create(array $data, User $actor): User
    {
        return DB::transaction(function () use ($data, $actor) {
            $roles = $this->getAssignableRoles($data['role_ids'] ?? [], $actor);

            $user = User::query()->create([
                'name' => $data['name'],
                'email' => mb_strtolower($data['email']),
                'password' => Hash::make($data['password']),
                'is_active' => (bool) $data['is_active'],
                'email_verified_at' => now(),
            ]);

            $this->syncRoles($user, $roles, $actor);

            $this->audit->record($actor, 'CREATE_USER', 'USERS', $user->user_id,
                'Akun pengguna dibuat.', [], [
                    'name' => $user->name,
                    'email' => $user->email,
                    'is_active' => $user->is_active,
                    'role_ids' => $roles->pluck('role_id')->all(),
                ]);

            return $user->load('roles');
        });
    }

    public function update(User $user, array $data, User $actor): User
    {
        return DB::transaction(function () use ($user, $data, $actor) {
            $user = User::query()->whereKey($user->user_id)->lockForUpdate()->firstOrFail();
            $this->protectSuperAdminTarget($user, $actor);

            if ($user->user_id === $actor->user_id && !(bool) $data['is_active']) {
                throw ValidationException::withMessages(['is_active' => 'Kamu tidak dapat menonaktifkan akun sendiri.']);
            }

            if ($actor->isSuperAdmin() && $actor->can('users.assign_roles')) {
                $roles = $this->getAssignableRoles($data['role_ids'] ?? [], $actor);
            } else {
                if (array_key_exists('role_ids', $data)) {
                    throw new AccessDeniedHttpException('Kamu tidak memiliki izin untuk mengubah role pengguna.');
                }
                $roles = $user->roles()->get();
            }

            $old = [
                'name' => $user->name,
                'email' => $user->email,
                'is_active' => $user->is_active,
                'role_ids' => $user->roles()->pluck('roles.role_id')->all(),
            ];

            $remainsSuperAdmin = $roles->contains(fn (Role $role) => $role->is_super_admin);
            if ($user->isSuperAdmin() && (!(bool) $data['is_active'] || !$remainsSuperAdmin)) {
                $this->ensureAnotherActiveSuperAdminExists($user);
            }

            $user->name = $data['name'];
            $user->email = mb_strtolower($data['email']);
            $user->is_active = (bool) $data['is_active'];

            $passwordUpdated = !empty($data['password']);
            if ($passwordUpdated) {
                $user->password = Hash::make($data['password']);
                $user->remember_token = bin2hex(random_bytes(30));
            }

            $user->save();
            $this->syncRoles($user, $roles, $actor);

            if (!$user->is_active || $passwordUpdated) {
                $this->invalidateDatabaseSessions($user);
            }

            $new = [
                'name' => $user->name,
                'email' => $user->email,
                'is_active' => $user->is_active,
                'role_ids' => $roles->pluck('role_id')->all(),
                'password_updated' => $passwordUpdated,
            ];

            $this->audit->record($actor, 'UPDATE_USER', 'USERS', $user->user_id,
                'Data dan hak akses pengguna diperbarui.', $old, $new);

            return $user->load('roles');
        });
    }

    public function changeStatus(User $user, bool $active, User $actor): void
    {
        DB::transaction(function () use ($user, $active, $actor) {
            $user = User::query()->whereKey($user->user_id)->lockForUpdate()->firstOrFail();
            $this->protectSuperAdminTarget($user, $actor);

            if ($user->user_id === $actor->user_id && !$active) {
                throw ValidationException::withMessages(['status' => 'Kamu tidak dapat menonaktifkan akun sendiri.']);
            }

            if ($user->isSuperAdmin() && !$active) {
                $this->ensureAnotherActiveSuperAdminExists($user);
            }

            $old = ['is_active' => $user->is_active];
            $user->is_active = $active;
            if (!$active) {
                // Rotasi remember token agar cookie Remember Me lama tidak dapat dipakai kembali.
                $user->remember_token = Str::random(60);
            }
            $user->save();

            if (!$active) {
                $this->invalidateDatabaseSessions($user);
            }

            $this->audit->record($actor, $active ? 'ACTIVATE_USER' : 'DEACTIVATE_USER', 'USERS', $user->user_id,
                $active ? 'Akun pengguna diaktifkan.' : 'Akun pengguna dinonaktifkan.', $old, ['is_active' => $active]);
        });
    }

    private function getAssignableRoles(array $roleIds, User $actor)
    {
        abort_unless($actor->isSuperAdmin() && $actor->can('users.assign_roles'), 403, 'Hanya Super Admin yang dapat menetapkan role pengguna.');

        $query = Role::query()->active()->whereIn('role_id', $roleIds);
        if (!$actor->isSuperAdmin()) {
            // Non-Super Admin hanya boleh menetapkan role kustom, bukan role bawaan sistem.
            $query->where('is_system_role', false)->where('is_super_admin', false);
        }
        $roles = $query->get();

        if ($roles->count() !== count(array_unique($roleIds))) {
            throw ValidationException::withMessages(['role_ids' => 'Satu atau lebih role tidak aktif atau tidak tersedia.']);
        }

        if ($roles->contains(fn (Role $role) => $role->is_super_admin) && !$actor->isSuperAdmin()) {
            throw new AccessDeniedHttpException('Hanya Super Admin yang dapat memberikan role Super Admin.');
        }

        return $roles;
    }

    private function syncRoles(User $user, $roles, User $actor): void
    {
        $sync = [];
        foreach ($roles as $role) {
            $sync[$role->role_id] = [
                'assigned_by' => $actor->user_id,
                'assigned_at' => now(),
            ];
        }

        $user->roles()->sync($sync);
    }

    private function protectSuperAdminTarget(User $target, User $actor): void
    {
        if ($target->isSuperAdmin() && !$actor->isSuperAdmin()) {
            throw new AccessDeniedHttpException('Hanya Super Admin yang dapat mengubah akun Super Admin.');
        }
    }

    private function ensureAnotherActiveSuperAdminExists(User $target): void
    {
        // Kunci baris role Super Admin untuk mencegah dua request paralel
        // menonaktifkan dua akun Super Admin terakhir secara bersamaan.
        Role::query()->where('is_super_admin', true)->lockForUpdate()->first();

        $otherExists = User::query()
            ->where('is_active', true)
            ->whereHas('roles', fn ($query) => $query->where('roles.is_super_admin', true)->where('roles.is_active', true)->whereNull('roles.deleted_at'))
            ->where('user_id', '<>', $target->user_id)
            ->exists();

        if (!$otherExists) {
            throw ValidationException::withMessages(['status' => 'Akun Super Admin aktif terakhir tidak boleh dinonaktifkan atau kehilangan role Super Admin.']);
        }
    }

    private function invalidateDatabaseSessions(User $user): void
    {
        if (Schema::hasTable('sessions')) {
            DB::table('sessions')->where('user_id', $user->user_id)->delete();
        }
    }
}
