@extends('admin.layouts.layout')
@section('title', 'Detail Pengguna')
@section('page_title', 'Detail Pengguna')
@section('page_breadcrumb', 'Administration / User Management / Detail')
@section('content')
    <div class="mb-5 flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
        <div><a href="{{ route('admin.users.index') }}" class="text-[11px] font-bold text-[#0d21a1] hover:underline">← Kembali
                ke User Management</a>
            <h1 class="mt-3 text-[23px] font-extrabold tracking-tight text-[#171717]">Detail pengguna</h1>
            <p class="mt-1 text-[12px] text-[#737373]">Informasi akun, role, dan status akses.</p>
        </div>
        @can('users.update')
            <a href="{{ route('admin.users.edit', $user->user_id) }}"
                class="inline-flex h-10 items-center justify-center rounded-lg bg-[#0d21a1] px-4 text-[11px] font-bold text-white hover:bg-[#091772]">Edit
                pengguna</a>
        @endcan
    </div>
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-[minmax(0,1.2fr)_minmax(260px,0.8fr)]">
        <section class="rounded-xl border border-[#e8eaf0] bg-white p-5">
            <div class="flex items-center gap-3"><span
                    class="grid h-12 w-12 place-items-center rounded-full bg-[#e7e9f6] text-[15px] font-extrabold text-[#0d21a1]">{{ strtoupper(collect(explode(' ', trim($user->name)))->filter()->take(2)->map(fn($part) => mb_substr($part, 0, 1))->implode('')) }}</span>
                <div>
                    <h2 class="text-[15px] font-extrabold text-[#171717]">{{ $user->name }}</h2>
                    <p class="mt-1 text-[11px] text-[#737373]">{{ $user->email }}</p>
                </div>
            </div>
            <dl class="mt-6 grid grid-cols-1 gap-4 border-t border-[#f0f1f5] pt-5 sm:grid-cols-2">
                <div>
                    <dt class="text-[10px] font-bold uppercase tracking-wide text-[#a1a1a1]">User ID</dt>
                    <dd class="mt-1 text-[12px] font-semibold">{{ $user->user_id }}</dd>
                </div>
                <div>
                    <dt class="text-[10px] font-bold uppercase tracking-wide text-[#a1a1a1]">Status akun</dt>
                    <dd class="mt-1 text-[12px] font-semibold">{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</dd>
                </div>
                <div>
                    <dt class="text-[10px] font-bold uppercase tracking-wide text-[#a1a1a1]">Dibuat</dt>
                    <dd class="mt-1 text-[12px]">
                        {{ $user->created_at?->timezone('Asia/Jakarta')->format('d M Y, H:i') ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-[10px] font-bold uppercase tracking-wide text-[#a1a1a1]">Terakhir login</dt>
                    <dd class="mt-1 text-[12px]">
                        {{ $user->last_login_at?->timezone('Asia/Jakarta')->format('d M Y, H:i') ?? 'Belum pernah login' }}
                    </dd>
                </div>
            </dl>
        </section>
        <section class="rounded-xl border border-[#e8eaf0] bg-white p-5">
            <h2 class="text-[13px] font-extrabold text-[#262626]">Role yang diberikan</h2>
            <div class="mt-4 flex flex-wrap gap-2">
                @forelse($user->roles as $role)
                    <span
                    class="rounded-md bg-[#e7e9f6] px-2.5 py-1.5 text-[10px] font-bold text-[#0d21a1]">{{ $role->role_name }}</span>@empty<span
                        class="text-[11px] text-[#737373]">Belum memiliki role</span>
                @endforelse
            </div>
            <p class="mt-4 text-[10px] leading-relaxed text-[#737373]">Permission efektif dihitung dari seluruh role aktif
                yang diberikan kepada pengguna ini.</p>
            @can('users.update')
                <details class="mt-6 border-t border-[#f0f1f5] pt-4">
                    <summary
                        class="cursor-pointer list-none text-center text-[11px] font-bold {{ $user->is_active ? 'text-[#b54740]' : 'text-[#16805d]' }}">
                        {{ $user->is_active ? 'Nonaktifkan akun' : 'Aktifkan akun' }}</summary>
                    <div class="mt-3 rounded-lg border border-[#e8eaf0] bg-[#fafafa] p-3">
                        <p class="text-[10px] leading-relaxed text-[#525252]">
                            {{ $user->is_active ? 'Akun tidak dapat digunakan untuk login setelah dinonaktifkan. Histori transaksi tetap tersimpan.' : 'Akun dapat login kembali setelah diaktifkan.' }}
                        </p>
                        <form method="POST" action="{{ route('admin.users.status', $user->user_id) }}" class="mt-3">@csrf
                            @method('PATCH')<input type="hidden" name="is_active"
                                value="{{ $user->is_active ? 0 : 1 }}"><button
                                class="w-full rounded-lg border px-3 py-2.5 text-[11px] font-bold {{ $user->is_active ? 'border-[#f2c8c5] bg-[#fff0ef] text-[#b54740]' : 'border-[#bfe5cf] bg-[#eaf7f0] text-[#16805d]' }}">Konfirmasi
                                {{ $user->is_active ? 'nonaktifkan' : 'aktifkan' }}</button></form>
                    </div>
                </details>
            @endcan
        </section>
    </div>
@endsection
