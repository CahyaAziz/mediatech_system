@php
    $moduleLabels = [
        'dashboard' => 'Dashboard',
        'quotations' => 'Quotation',
        'sales_orders' => 'Sales Order',
        'invoices' => 'Invoice',
        'payments' => 'Payment',
        'cash_flow' => 'Cash Flow',
        'petty_cash' => 'Petty Cash',
        'inventory' => 'Inventory',
        'master_data' => 'Master Data',
        'users' => 'User Management',
        'role_management' => 'Manajemen Role',
        'cms' => 'CMS Content',
        'audit' => 'Audit Log',
        'profit' => 'Profit',
        'commissions' => 'Aturan Komisi',
    ];
    $operationLabels = [
        'read' => 'Lihat',
        'read_all' => 'Lihat semua simulasi',
        'create' => 'Tambah',
        'update' => 'Ubah',
        'delete' => 'Hapus role',
        'soft_delete' => 'Hapus sementara',
        'restore' => 'Pulihkan',
        'permanent_delete' => 'Hapus permanen',
        'cancel' => 'Batalkan (30 menit)',
        'cancel_override' => 'Batalkan di luar batas waktu',
        'post' => 'Posting/konfirmasi',
        'reverse' => 'Koreksi/pembalikan',
        'approve' => 'Setujui/validasi',
        'export' => 'Ekspor',
        'assign_roles' => 'Menetapkan role pengguna',
    ];
@endphp

<div class="grid grid-cols-1 gap-5 lg:grid-cols-[minmax(260px,0.7fr)_minmax(0,1.3fr)]">
    <section class="h-fit rounded-xl border border-[#e8eaf0] bg-white p-5">
        <h2 class="text-[13px] font-extrabold text-[#262626]">Informasi role</h2>
        <div class="mt-4 space-y-4">
            <div><label for="role_name" class="mb-2 block text-[11px] font-bold text-[#404040]">Nama role</label><input
                    id="role_name" name="role_name" value="{{ old('role_name', $role->role_name) }}" maxlength="100"
                    required @if ($role->exists && $role->is_system_role) readonly @endif
                    class="h-10 w-full rounded-lg border border-[#dedfe6] px-3 text-[12px] outline-none focus:border-[#5d6ac0] focus:ring-2 focus:ring-[#e7e9f6] @if ($role->exists && $role->is_system_role) bg-[#f5f5f5] text-[#737373] @endif">
                @error('role_name')
                    <p class="mt-1 text-[10px] text-red-600">{{ $message }}</p>
                    @enderror @if ($role->exists && $role->is_system_role)
                        <p class="mt-1 text-[10px] text-[#737373]">Nama role bawaan tidak dapat diubah.</p>
                    @endif
                </div>
                <div><label for="description" class="mb-2 block text-[11px] font-bold text-[#404040]">Deskripsi</label>
                    <textarea id="description" name="description" maxlength="191" @if ($role->is_super_admin) readonly @endif required
                        class="min-h-28 w-full rounded-lg border border-[#dedfe6] p-3 text-[12px] outline-none focus:border-[#5d6ac0] focus:ring-2 focus:ring-[#e7e9f6] @if ($role->is_super_admin) bg-[#f5f5f5] text-[#737373] @endif">{{ old('description', $role->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-[10px] text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                @if ($role->is_system_role)
                    <div
                        class="rounded-lg border border-[#f7e5a9] bg-[#fdf9ec] p-3 text-[10px] leading-relaxed text-[#64531c]">
                        Role ini merupakan role bawaan dan tidak dapat dihapus.</div>
                @endif
                @if ($role->is_super_admin)
                    <div
                        class="rounded-lg border border-[#e7e9f6] bg-[#f5f6fb] p-3 text-[10px] leading-relaxed text-[#525252]">
                        Hak akses Super Admin selalu penuh. Daftar permission ditampilkan sebagai informasi dan tidak dapat
                        diubah.</div>
                @endif
            </div>
        </section>

        <section class="rounded-xl border border-[#e8eaf0] bg-white p-5">
            <div class="flex flex-col justify-between gap-2 sm:flex-row sm:items-center">
                <div>
                    <h2 class="text-[13px] font-extrabold text-[#262626]">Hak akses per modul</h2>
                    <p class="mt-1 text-[10px] text-[#737373]">
                        @if ($role->is_super_admin)
                            Seluruh hak akses sistem untuk role Super Admin.
                        @else
                            Centang operasi yang boleh dilakukan oleh pengguna dengan role ini.
                        @endif
                    </p>
                </div><span
                    class="rounded-md bg-[#e7e9f6] px-2 py-1 text-[9px] font-extrabold uppercase tracking-wide text-[#0d21a1]">Dynamic
                    RBAC</span>
            </div>
            <div class="mt-4 space-y-4">
                @foreach ($permissionGroups as $module => $permissions)
                    <div class="overflow-hidden rounded-lg border border-[#eceef3]">
                        <div
                            class="flex items-center justify-between gap-3 border-b border-[#eceef3] bg-[#fafafa] px-3 py-2.5">
                            <h3 class="text-[11px] font-extrabold text-[#404040]">
                                {{ $moduleLabels[$module] ?? ucwords(str_replace('_', ' ', $module)) }}</h3><span
                                class="text-[9px] text-[#a1a1a1]">{{ $permissions->count() }} operasi tersedia</span>
                        </div>
                        <div class="grid grid-cols-1 gap-2 p-3 sm:grid-cols-2">
                            @foreach ($permissions as $permission)
                                <label
                                    class="flex items-start gap-2 rounded-md px-2 py-2 {{ $role->is_super_admin ? 'cursor-default' : 'cursor-pointer hover:bg-[#fafbff]' }}">
                                    <input type="checkbox" name="permission_ids[]" value="{{ $permission->permission_id }}"
                                        @checked(in_array(
                                                (int) $permission->permission_id,
                                                array_map('intval', old('permission_ids', $selectedPermissionIds)),
                                                true)) @disabled($role->is_super_admin)
                                        class="mt-0.5 h-4 w-4 rounded border-[#bfc3d2] text-[#0d21a1] focus:ring-[#b4bae2] disabled:opacity-100">
                                    <span class="min-w-0"><span
                                            class="block text-[10px] font-bold {{ $permission->is_destructive_operation ? 'text-[#b54740]' : 'text-[#404040]' }}">{{ $operationLabels[$permission->operation_code] ?? ucwords(str_replace('_', ' ', $permission->operation_code)) }}</span><span
                                            class="mt-0.5 block text-[9px] leading-relaxed text-[#737373]">{{ $permission->description }}</span></span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            @unless ($role->is_super_admin)
                <div
                    class="mt-4 rounded-lg border border-[#f7e5a9] bg-[#fdf9ec] p-3 text-[10px] leading-relaxed text-[#64531c]">
                    Operasi berwarna merah memiliki dampak besar, seperti pembatalan, koreksi, dan penghapusan. Berikan hanya
                    jika memang diperlukan. Hak akses tetap diperiksa di server.</div>
            @endunless
        </section>
    </div>
