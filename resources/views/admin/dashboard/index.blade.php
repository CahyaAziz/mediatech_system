@extends('admin.layouts.layout')
@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_breadcrumb', 'Workspace')
@section('content')
    <div class="mb-6">
        <div class="text-[10px] font-extrabold uppercase tracking-[1.3px] text-[#0d21a1]">Meditech System</div>
        <h1 class="mt-2 text-[25px] font-extrabold tracking-tight text-[#171717]">Selamat datang, {{ auth()->user()->name }}.
        </h1>
        <p class="mt-2 text-[12px] text-[#737373]">Login berhasil. Modul akan ditampilkan sesuai permission yang diberikan
            kepada akunmu.</p>
    </div>
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        @can('users.read')
            <a href="{{ route('admin.users.index') }}"
                class="rounded-xl border border-[#e8eaf0] bg-white p-5 transition hover:border-[#b4bae2] hover:shadow-sm">
                <p class="text-[10px] font-extrabold uppercase tracking-wide text-[#0d21a1]">Administration</p>
                <h2 class="mt-2 text-[15px] font-extrabold text-[#171717]">User Management</h2>
                <p class="mt-1 text-[11px] text-[#737373]">Kelola akun pengguna sesuai hak akses.</p>
            </a>
        @endcan
        @can('role_management.read')
            <a href="{{ route('admin.roles.index') }}"
                class="rounded-xl border border-[#e8eaf0] bg-white p-5 transition hover:border-[#b4bae2] hover:shadow-sm">
                <p class="text-[10px] font-extrabold uppercase tracking-wide text-[#0d21a1]">Administration</p>
                <h2 class="mt-2 text-[15px] font-extrabold text-[#171717]">Manajemen Role</h2>
                <p class="mt-1 text-[11px] text-[#737373]">Atur role dan permission sesuai tanggung jawab.</p>
            </a>
        @endcan
    </div>
@endsection
