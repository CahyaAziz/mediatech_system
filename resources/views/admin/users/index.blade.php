@extends('admin.layouts.layout')

@section('title', 'User Management')
@section('page_title', 'User Management')
@section('page_breadcrumb', 'Administration / User Management')

@section('content')
    {{-- @php
        $totalUsersDisplay = $totalUsers ?? (method_exists($users, 'total') ? $users->total() : $users->count());
        $activeUsersDisplay = $activeUsersCount ?? '—';
        $rolesDisplay = $rolesCount ?? $roles->count();
    @endphp --}}

    <div class="mb-6 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-start">
        <div>
            <div class="mb-[7px] text-[10px] font-extrabold uppercase tracking-[1.3px] text-[#0d21a1]">Administration</div>
            <h1 class="m-0 text-[24px] font-extrabold leading-tight tracking-[-0.8px] text-[#171717] sm:text-[25px]">User
                Management</h1>
            <p class="mt-[6px] max-w-2xl text-[12px] leading-relaxed text-[#737373]">Kelola akun pengguna dan role sesuai
                kebutuhan akses masing-masing. Setiap pengguna hanya mendapatkan akses berdasarkan permission yang
                diberikan.</p>
        </div>

        @can('users.create')
            <a href="{{ route('admin.users.create') }}"
                class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg border border-transparent bg-[#0d21a1] px-[14px] py-[10px] text-[12px] font-bold text-white transition hover:bg-[#091772] focus:outline-none focus:ring-4 focus:ring-[#e7e9f6]">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round">
                    <path d="M12 5v14M5 12h14" />
                </svg>
                Tambah pengguna
            </a>
        @endcan
    </div>

    {{-- Summary cards --}}
    <div class="mb-5 grid grid-cols-1 gap-3 sm:grid-cols-3 sm:gap-4">
        <div
            class="min-h-[116px] rounded-xl border border-[#e8eaf0] bg-white px-[18px] py-[16px] shadow-[0_1px_2px_rgba(20,27,55,0.02)]">
            <div class="flex items-center justify-between text-[11px] font-semibold text-[#737373]">
                <span>Total pengguna</span>
                <span class="grid h-[31px] w-[31px] place-items-center rounded-lg bg-[#e7e9f6] text-[#0d21a1]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="8" r="3" />
                        <path d="M3.5 20a5.5 5.5 0 0 1 11 0M16 5.5a3 3 0 0 1 0 5.8M17 14a4.5 4.5 0 0 1 3.5 4.3" />
                    </svg>
                </span>
            </div>
            <div class="my-[10px] text-[25px] font-extrabold tracking-[-0.7px] text-[#171717]">
                {{ number_format($totalUsersDisplay, 0, ',', '.') }}</div>
            <div class="text-[10px] text-[#737373]">Seluruh role</div>
        </div>

        <div
            class="min-h-[116px] rounded-xl border border-[#e8eaf0] bg-white px-[18px] py-[16px] shadow-[0_1px_2px_rgba(20,27,55,0.02)]">
            <div class="flex items-center justify-between text-[11px] font-semibold text-[#737373]">
                <span>Akun aktif</span>
                <span class="grid h-[31px] w-[31px] place-items-center rounded-lg bg-[#eaf7f0] text-[#16805d]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="m5 12 4 4L19 6" />
                    </svg>
                </span>
            </div>
            <div class="my-[10px] text-[25px] font-extrabold tracking-[-0.7px] text-[#171717]">
                {{ is_numeric($activeUsersDisplay) ? number_format($activeUsersDisplay, 0, ',', '.') : $activeUsersDisplay }}
            </div>
            <div class="text-[10px] text-[#737373]">Akun yang dapat login</div>
        </div>

        <div
            class="min-h-[116px] rounded-xl border border-[#e8eaf0] bg-white px-[18px] py-[16px] shadow-[0_1px_2px_rgba(20,27,55,0.02)]">
            <div class="flex items-center justify-between text-[11px] font-semibold text-[#737373]">
                <span>Role tersedia</span>
                <span class="grid h-[31px] w-[31px] place-items-center rounded-lg bg-[#fdf9ec] text-[#a98d30]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 3 14.5 8.5 20.5 9.2 16 13.4 17.2 19.5 12 16.4 6.8 19.5 8 13.4 3.5 9.2 9.5 8.5 12 3Z" />
                    </svg>
                </span>
            </div>
            <div class="my-[10px] text-[25px] font-extrabold tracking-[-0.7px] text-[#171717]">
                {{ number_format($rolesDisplay, 0, ',', '.') }}</div>
            <div class="text-[10px] text-[#737373]">Role sistem dan role tambahan</div>
        </div>
    </div>

    {{-- Search and filters --}}
    <section class="overflow-hidden rounded-xl border border-[#e8eaf0] bg-white shadow-[0_1px_2px_rgba(20,27,55,0.02)]">
        <form action="{{ route('admin.users.index') }}" method="GET"
            class="flex flex-col gap-2 border-b border-[#f0f1f5] p-[14px] sm:flex-row sm:items-center">
            <label class="relative block w-full sm:max-w-[280px]">
                <span class="sr-only">Cari nama atau email</span>
                <svg class="absolute left-[11px] top-1/2 h-4 w-4 -translate-y-1/2 text-[#a1a1a1]" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
                    <circle cx="10.8" cy="10.8" r="6.8" />
                    <path d="m16 16 4.5 4.5" />
                </svg>
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari nama / email…"
                    class="h-[36px] w-full rounded-lg border border-[#dedfe6] bg-white pl-[34px] pr-3 text-[12px] text-[#404040] outline-none placeholder:text-[#a1a1a1] focus:border-[#5d6ac0] focus:ring-2 focus:ring-[#e7e9f6]">
            </label>

            <label class="block w-full sm:w-[190px]">
                <span class="sr-only">Filter role</span>
                <select name="role_id"
                    class="h-[36px] w-full rounded-lg border border-[#dedfe6] bg-white px-3 text-[12px] text-[#404040] outline-none focus:border-[#5d6ac0] focus:ring-2 focus:ring-[#e7e9f6]">
                    <option value="">Semua role</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->role_id }}" @selected((string) request('role_id') === (string) $role->role_id)>{{ $role->role_name }}</option>
                    @endforeach
                </select>
            </label>

            <label class="block w-full sm:w-[160px]">
                <span class="sr-only">Filter status akun</span>
                <select name="status"
                    class="h-[36px] w-full rounded-lg border border-[#dedfe6] bg-white px-3 text-[12px] text-[#404040] outline-none focus:border-[#5d6ac0] focus:ring-2 focus:ring-[#e7e9f6]">
                    <option value="">Semua status</option>
                    <option value="active" @selected(request('status') === 'active')>Aktif</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Nonaktif</option>
                </select>
            </label>

            <div class="flex items-center gap-2 sm:ml-auto">
                <button type="submit"
                    class="inline-flex h-[36px] items-center justify-center gap-2 rounded-lg bg-[#0d21a1] px-3 text-[11px] font-bold text-white transition hover:bg-[#091772]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round">
                        <circle cx="10.8" cy="10.8" r="6.8" />
                        <path d="m16 16 4.5 4.5" />
                    </svg>
                    Terapkan
                </button>
                <a href="{{ route('admin.users.index') }}"
                    class="inline-flex h-[36px] items-center justify-center rounded-lg border border-[#dedfe6] bg-white px-3 text-[11px] font-semibold text-[#525252] transition hover:bg-[#fafafa]">Reset</a>
            </div>
        </form>

        {{-- Users table --}}
        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] border-collapse text-left">
                <thead>
                    <tr class="bg-[#fafafa]">
                        <th
                            class="border-b border-[#eeeeee] px-4 py-3 text-[10px] font-extrabold uppercase tracking-[0.65px] text-[#737373]">
                            Pengguna</th>
                        <th
                            class="border-b border-[#eeeeee] px-4 py-3 text-[10px] font-extrabold uppercase tracking-[0.65px] text-[#737373]">
                            Email</th>
                        <th
                            class="border-b border-[#eeeeee] px-4 py-3 text-[10px] font-extrabold uppercase tracking-[0.65px] text-[#737373]">
                            Role</th>
                        <th
                            class="border-b border-[#eeeeee] px-4 py-3 text-[10px] font-extrabold uppercase tracking-[0.65px] text-[#737373]">
                            Terakhir aktif</th>
                        <th
                            class="border-b border-[#eeeeee] px-4 py-3 text-[10px] font-extrabold uppercase tracking-[0.65px] text-[#737373]">
                            Status</th>
                        <th
                            class="border-b border-[#eeeeee] px-4 py-3 text-[10px] font-extrabold uppercase tracking-[0.65px] text-[#737373]">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr class="transition hover:bg-[#fcfcfe]">
                            <td class="border-b border-[#f0f1f5] px-4 py-[13px]">
                                <div class="flex items-center gap-[10px]">
                                    <span
                                        class="grid h-[34px] w-[34px] shrink-0 place-items-center rounded-full bg-[#e7e9f6] text-[11px] font-extrabold text-[#0d21a1]">
                                        {{ strtoupper(collect(explode(' ', trim($user->name)))->filter()->take(2)->map(fn($part) => mb_substr($part, 0, 1))->implode('')) }}
                                    </span>
                                    <span class="min-w-0">
                                        <span
                                            class="block max-w-[190px] truncate text-[12px] font-bold text-[#262626]">{{ $user->name }}</span>
                                        <span class="mt-[2px] block text-[10px] text-[#a1a1a1]">ID:
                                            {{ $user->user_id }}</span>
                                    </span>
                                </div>
                            </td>
                            <td class="border-b border-[#f0f1f5] px-4 py-[13px] text-[11px] text-[#525252]">
                                {{ $user->email }}</td>
                            <td class="border-b border-[#f0f1f5] px-4 py-[13px]">
                                <div class="flex max-w-[190px] flex-wrap gap-1">
                                    @forelse ($user->roles as $userRole)
                                        <span
                                            class="inline-flex rounded-md bg-[#f1f2f5] px-2 py-1 text-[10px] font-semibold text-[#525252]">{{ $userRole->role_name }}</span>
                                    @empty
                                        <span class="text-[10px] text-[#a1a1a1]">Belum ada role</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="border-b border-[#f0f1f5] px-4 py-[13px] text-[11px] text-[#737373]">
                                @if ($user->last_login_at)
                                    {{ $user->last_login_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }}
                                @else
                                    <span class="text-[#a1a1a1]">Belum pernah login</span>
                                @endif
                            </td>
                            <td class="border-b border-[#f0f1f5] px-4 py-[13px]">
                                @if ($user->is_active)
                                    <span
                                        class="inline-flex items-center gap-[5px] rounded-[5px] bg-[#eaf7f0] px-2 py-1 text-[10px] font-extrabold text-[#16805d]"><span
                                            class="h-[5px] w-[5px] rounded-full bg-current"></span>Aktif</span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-[5px] rounded-[5px] bg-[#fff0ef] px-2 py-1 text-[10px] font-extrabold text-[#b54740]"><span
                                            class="h-[5px] w-[5px] rounded-full bg-current"></span>Nonaktif</span>
                                @endif
                            </td>
                            <td class="border-b border-[#f0f1f5] px-4 py-[13px]">
                                <div class="flex items-center gap-2">
                                    @can('users.read')
                                        <a href="{{ route('admin.users.show', $user->user_id) }}"
                                            class="text-[11px] font-extrabold text-[#0d21a1] hover:underline">Detail</a>
                                    @endcan
                                    @can('users.update')
                                        <a href="{{ route('admin.users.edit', $user->user_id) }}"
                                            class="text-[11px] font-extrabold text-[#525252] hover:text-[#0d21a1] hover:underline">Edit</a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center">
                                <span
                                    class="mx-auto mb-3 grid h-11 w-11 place-items-center rounded-full bg-[#f1f2f5] text-[#737373]">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="10.8" cy="10.8" r="6.8" />
                                        <path d="m16 16 4.5 4.5" />
                                    </svg>
                                </span>
                                <p class="text-[12px] font-bold text-[#404040]">Pengguna tidak ditemukan</p>
                                <p class="mt-1 text-[11px] text-[#737373]">Coba ubah kata kunci atau filter pencarian.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if (method_exists($users, 'links'))
            <div
                class="flex flex-col gap-3 border-t border-[#f0f1f5] px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-[10px] text-[#737373]">
                    Menampilkan {{ $users->firstItem() ?? 0 }}–{{ $users->lastItem() ?? 0 }} dari {{ $users->total() }}
                    pengguna
                </p>
                <div
                    class="text-[11px] [&_nav]:flex [&_nav]:items-center [&_nav]:gap-1 [&_a]:rounded-md [&_a]:border [&_a]:border-[#e5e5e5] [&_a]:px-3 [&_a]:py-2 [&_a]:text-[#525252] [&_a:hover]:bg-[#f5f6fb] [&_span[aria-current=page]]:rounded-md [&_span[aria-current=page]]:bg-[#e7e9f6] [&_span[aria-current=page]]:px-3 [&_span[aria-current=page]]:py-2 [&_span[aria-current=page]]:font-bold [&_span[aria-current=page]]:text-[#0d21a1]">
                    {{ $users->withQueryString()->links() }}
                </div>
            </div>
        @endif
    </section>

    <div
        class="mt-[14px] rounded-lg border border-[#e7e9f6] bg-[#f5f6fb] px-4 py-3 text-[11px] leading-relaxed text-[#525252]">
        <span class="font-bold text-[#0d21a1]">Catatan akses:</span>
        akun Super Admin terakhir tidak boleh dinonaktifkan. Tampilan menu dan tombol mengikuti permission, tetapi setiap
        aksi tetap harus divalidasi di backend melalui Policy atau middleware.
    </div>
@endsection
