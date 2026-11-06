@php
    $selectedRoles = old(
        'role_ids',
        isset($user) ? $user->roles->pluck('role_id')->map(fn($id) => (string) $id)->all() : [],
    );
@endphp
<div class="grid grid-cols-1 gap-5 lg:grid-cols-[minmax(0,1.1fr)_minmax(280px,0.9fr)]">
    <section class="rounded-xl border border-[#e8eaf0] bg-white p-5">
        <h2 class="text-[14px] font-extrabold text-[#262626]">Informasi pengguna</h2>
        <p class="mt-1 text-[11px] text-[#737373]">Data akun yang digunakan untuk masuk ke Meditech.</p>
        <div class="mt-5 space-y-4">
            <div><label for="name" class="mb-2 block text-[11px] font-bold text-[#404040]">Nama lengkap</label><input
                    id="name" name="name" value="{{ old('name', $user->name ?? '') }}" required maxlength="180"
                    class="h-10 w-full rounded-lg border border-[#dedfe6] px-3 text-[12px] outline-none focus:border-[#5d6ac0] focus:ring-2 focus:ring-[#e7e9f6]">
                @error('name')
                    <p class="mt-1 text-[10px] text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div><label for="email" class="mb-2 block text-[11px] font-bold text-[#404040]">Email</label><input
                    id="email" name="email" type="email" value="{{ old('email', $user->email ?? '') }}" required
                    maxlength="255" autocomplete="off"
                    class="h-10 w-full rounded-lg border border-[#dedfe6] px-3 text-[12px] outline-none focus:border-[#5d6ac0] focus:ring-2 focus:ring-[#e7e9f6]">
                @error('email')
                    <p class="mt-1 text-[10px] text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <div><label for="password"
                        class="mb-2 block text-[11px] font-bold text-[#404040]">{{ isset($user) ? 'Kata sandi baru (opsional)' : 'Kata sandi sementara' }}</label><input
                        id="password" name="password" type="password" {{ isset($user) ? '' : 'required' }}
                        minlength="12" autocomplete="new-password"
                        class="h-10 w-full rounded-lg border border-[#dedfe6] px-3 text-[12px] outline-none focus:border-[#5d6ac0] focus:ring-2 focus:ring-[#e7e9f6]">
                    @error('password')
                        <p class="mt-1 text-[10px] text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div><label for="password_confirmation"
                        class="mb-2 block text-[11px] font-bold text-[#404040]">Konfirmasi kata sandi</label><input
                        id="password_confirmation" name="password_confirmation" type="password"
                        {{ isset($user) ? '' : 'required' }} minlength="12" autocomplete="new-password"
                        class="h-10 w-full rounded-lg border border-[#dedfe6] px-3 text-[12px] outline-none focus:border-[#5d6ac0] focus:ring-2 focus:ring-[#e7e9f6]">
                </div>
            </div>
            <p class="text-[10px] leading-relaxed text-[#737373]">Gunakan minimal 12 karakter. Jangan kirim kata sandi
                melalui chat grup atau menyimpannya di repository.</p>
            @if (isset($user))
                <div><label class="mb-2 block text-[11px] font-bold text-[#404040]">Status akun</label><select
                        name="is_active" class="h-10 w-full rounded-lg border border-[#dedfe6] px-3 text-[12px]">
                        <option value="1" @selected((string) old('is_active', (int) $user->is_active) === '1')>Aktif</option>
                        <option value="0" @selected((string) old('is_active', (int) $user->is_active) === '0')>Nonaktif</option>
                </select></div>@else<input type="hidden" name="is_active" value="1">
            @endif
        </div>
    </section>

    <section class="rounded-xl border border-[#e8eaf0] bg-white p-5">
        <div class="flex items-start justify-between gap-3">
            <div>
                <h2 class="text-[14px] font-extrabold text-[#262626]">Role pengguna</h2>
                <p class="mt-1 text-[11px] text-[#737373]">Pilih role sesuai tanggung jawab. Permission mengikuti role
                    yang dipilih.</p>
            </div><span
                class="rounded-md bg-[#e7e9f6] px-2 py-1 text-[9px] font-extrabold uppercase tracking-wide text-[#0d21a1]">RBAC</span>
        </div>
        @if (auth()->user()?->isSuperAdmin() && auth()->user()?->can('users.assign_roles'))
            <div class="mt-4 space-y-2">
                @foreach ($roles as $role)
                    <label
                        class="flex cursor-pointer items-start gap-3 rounded-lg border border-[#eceef3] p-3 transition hover:border-[#b4bae2] hover:bg-[#fafbff]">
                        <input type="checkbox" name="role_ids[]" value="{{ $role->role_id }}"
                            @checked(in_array((string) $role->role_id, array_map('strval', $selectedRoles), true))
                            class="mt-0.5 h-4 w-4 rounded border-[#bfc3d2] text-[#0d21a1] focus:ring-[#b4bae2]">
                        <span class="min-w-0 flex-1"><span
                                class="block text-[11px] font-bold text-[#262626]">{{ $role->role_name }}</span><span
                                class="mt-1 block text-[10px] leading-relaxed text-[#737373]">{{ $role->description }}</span>
                            @if ($role->is_system_role)
                                <span
                                    class="mt-1 inline-flex rounded bg-[#f1f2f5] px-1.5 py-0.5 text-[9px] font-bold text-[#525252]">Role
                                    bawaan</span>
                            @endif
                        </span>
                    </label>
                @endforeach
            </div>
            @error('role_ids')
                <p class="mt-2 text-[10px] text-red-600">{{ $message }}</p>
            @enderror
            @error('role_ids.*')
                <p class="mt-2 text-[10px] text-red-600">{{ $message }}</p>
            @enderror
            <div
                class="mt-4 rounded-lg border border-[#f7e5a9] bg-[#fdf9ec] p-3 text-[10px] leading-relaxed text-[#64531c]">
                Role Super Admin hanya dapat diberikan oleh pengguna yang saat ini memiliki akses Super Admin. Pilih
                hanya izin yang benar-benar diperlukan untuk pekerjaan pengguna.</div>
        @else
            <div class="mt-4 flex flex-wrap gap-2">
                @forelse(($user->roles ?? collect()) as $assignedRole)
                    <span
                    class="rounded-md bg-[#e7e9f6] px-2.5 py-1.5 text-[10px] font-bold text-[#0d21a1]">{{ $assignedRole->role_name }}</span>@empty<span
                        class="text-[11px] text-[#737373]">Belum memiliki role.</span>
                @endforelse
            </div>
            <p class="mt-3 text-[10px] leading-relaxed text-[#737373]">Kamu tidak memiliki izin untuk mengubah
                penempatan role. Perubahan role hanya dapat dilakukan oleh Super Admin.</p>
        @endif
    </section>
</div>
