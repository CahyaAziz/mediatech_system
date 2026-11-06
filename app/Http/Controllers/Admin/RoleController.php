<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRoleRequest;
use App\Http\Requests\Admin\UpdateRoleRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Services\Admin\RoleManagementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function __construct(private readonly RoleManagementService $roles)
    {
    }

    public function index(): View
    {
        $roles = Role::query()
            ->whereNull('deleted_at')
            ->withCount(['users', 'permissions'])
            ->orderByDesc('is_super_admin')
            ->orderBy('role_name')
            ->paginate(10);

        return view('admin.roles.index', [
            'roles' => $roles,
            'activeRolesCount' => Role::query()->active()->count(),
            'systemRolesCount' => Role::query()->where('is_system_role', true)->whereNull('deleted_at')->count(),
        ]);
    }

    public function create(): View
    {
        return view('admin.roles.create', [
            'role' => new Role(),
            'permissionGroups' => $this->permissionGroups(),
            'selectedPermissionIds' => [],
        ]);
    }

    public function store(StoreRoleRequest $request): RedirectResponse
    {
        $role = $this->roles->create($request->validated(), $request->user());

        return redirect()->route('admin.roles.edit', $role->role_id)
            ->with('success', 'Role baru berhasil dibuat.');
    }

    public function edit(Role $role): View
    {
        abort_if($role->deleted_at || !$role->is_active, 404);
        $role->load('permissions');

        return view('admin.roles.edit', [
            'role' => $role,
            'permissionGroups' => $this->permissionGroups($role->is_super_admin),
            'selectedPermissionIds' => $role->permissions->pluck('permission_id')->all(),
        ]);
    }

    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        $this->roles->update($role, $request->validated(), $request->user());

        return redirect()->route('admin.roles.edit', $role->role_id)
            ->with('success', 'Role dan hak akses berhasil diperbarui.');
    }

    public function destroy(Request $request, Role $role): RedirectResponse
    {
        $validated = $request->validate([
            'deletion_reason' => ['required', 'string', 'min:5', 'max:500'],
        ]);

        $this->roles->softDelete($role, $validated['deletion_reason'], $request->user());

        return redirect()->route('admin.roles.index')->with('success', 'Role berhasil dinonaktifkan dan masuk ke riwayat penghapusan.');
    }

    private function permissionGroups(bool $includePrivileged = false)
    {
        $query = Permission::query()->where('is_active', true);

        if (!$includePrivileged) {
            $query->where('is_assignable', true);
        }

        $moduleOrder = [
            'dashboard', 'quotations', 'sales_orders', 'invoices', 'payments',
            'cash_flow', 'petty_cash', 'inventory', 'master_data', 'users',
            'cms', 'audit', 'profit', 'commissions', 'role_management',
        ];
        $operationOrder = [
            'read', 'read_all', 'create', 'update', 'delete', 'soft_delete', 'restore',
            'permanent_delete', 'cancel', 'cancel_override', 'post', 'reverse', 'approve',
            'export', 'assign_roles',
        ];

        $groups = $query->get()->groupBy('module_code');
        $orderedGroups = collect();

        foreach ($moduleOrder as $moduleCode) {
            if (!$groups->has($moduleCode)) {
                continue;
            }

            $orderedGroups->put(
                $moduleCode,
                $groups->get($moduleCode)->sortBy(
                    fn (Permission $permission) => array_search($permission->operation_code, $operationOrder, true) ?: 0
                )->values()
            );
        }

        return $orderedGroups;
    }
}