<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use App\Services\Admin\UserManagementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(private readonly UserManagementService $users)
    {
    }

    public function index(Request $request): View
    {
        $perPage = (int) $request->query('per_page', 10);
        if (!in_array($perPage, [5, 10, 25, 50], true)) {
            $perPage = 10;
        }

        $users = $this->filteredUsersQuery($request)
            ->with('roles')
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'roles' => Role::query()->active()->orderBy('role_name')->get(),
            'totalUsers' => User::count(),
            'activeUsersCount' => User::where('is_active', true)->count(),
            'rolesCount' => Role::query()->active()->count(),
            'perPage' => $perPage,
        ]);
    }

    public function export(Request $request)
    {
        $query = $this->filteredUsersQuery($request)->with('roles')->orderBy('name');
        $filename = 'meditech-user-management-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $output = fopen('php://output', 'w');
            // UTF-8 BOM agar nama berbahasa Indonesia terbaca baik ketika CSV dibuka di Excel.
            fwrite($output, "\xEF\xBB\xBF");
            fputcsv($output, ['ID Pengguna', 'Nama', 'Email', 'Role', 'Terakhir Aktif', 'Status']);

            $query->chunkById(500, function ($users) use ($output) {
                foreach ($users as $user) {
                    $values = [
                        $user->user_id,
                        $user->name,
                        $user->email,
                        $user->roles->pluck('role_name')->implode(', '),
                        $user->last_login_at?->timezone('Asia/Jakarta')->format('Y-m-d H:i:s') ?? 'Belum pernah login',
                        $user->is_active ? 'Aktif' : 'Nonaktif',
                    ];

                    // Hindari formula injection saat CSV dibuka di spreadsheet.
                    $values = array_map(static function ($value) {
                        $value = (string) $value;
                        return preg_match('/^\s*[=+@\-]/u', $value) ? "'" . $value : $value;
                    }, $values);

                    fputcsv($output, $values);
                }
            }, 'user_id');

            fclose($output);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function filteredUsersQuery(Request $request)
    {
        $query = User::query();

        if ($search = trim((string) $request->query('search', ''))) {
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $roleId = $request->query('role_id');
        if (is_scalar($roleId) && ctype_digit((string) $roleId) && (int) $roleId > 0) {
            $query->whereHas('roles', fn ($builder) => $builder->where('roles.role_id', (int) $roleId));
        }

        if ($request->query('status') === 'active') {
            $query->where('is_active', true);
        } elseif ($request->query('status') === 'inactive') {
            $query->where('is_active', false);
        }

        return $query;
    }

    public function create(): View
    {
        abort_unless(auth()->user()?->isSuperAdmin() && auth()->user()?->can('users.assign_roles'), 403);

        return view('admin.users.create', ['roles' => $this->availableRoles()]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $user = $this->users->create($request->validated(), $request->user());

        return redirect()->route('admin.users.show', $user->user_id)
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function show(User $user): View
    {
        $user->load('roles');
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        $user->load('roles');
        return view('admin.users.edit', [
            'user' => $user,
            'roles' => $this->availableRoles(),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $this->users->update($user, $request->validated(), $request->user());

        return redirect()->route('admin.users.show', $user->user_id)
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function updateStatus(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        $this->users->changeStatus($user, (bool) $validated['is_active'], $request->user());

        return back()->with('success', $validated['is_active'] ? 'Akun berhasil diaktifkan.' : 'Akun berhasil dinonaktifkan.');
    }

    private function availableRoles()
    {
        $query = Role::query()->active()->orderBy('role_name');

        if (!auth()->user()?->isSuperAdmin() || !auth()->user()?->can('users.assign_roles')) {
            return collect();
        }

        if (!auth()->user()?->isSuperAdmin()) {
            $query->where('is_system_role', false)->where('is_super_admin', false);
        }

        return $query->get();
    }
}