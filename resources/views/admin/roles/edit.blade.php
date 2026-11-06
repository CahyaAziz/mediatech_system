@extends('admin.layouts.layout')
@section('title', 'Atur Role')
@section('page_title', 'Atur Role')
@section('page_breadcrumb', 'Administration / Manajemen Role / Pengaturan')
@section('content')
    <div class="mb-5">
        <a href="{{ route('admin.roles.index') }}" class="text-[11px] font-bold text-[#0d21a1] hover:underline">← Kembali ke
            Manajemen Role</a>
        <h1 class="mt-3 text-[23px] font-extrabold tracking-tight text-[#171717]">Pengaturan role: {{ $role->role_name }}
        </h1>
        <p class="mt-1 text-[12px] text-[#737373]">Perubahan permission berlaku pada pemeriksaan akses berikutnya.</p>
    </div>
    <form method="POST" action="{{ route('admin.roles.update', $role->role_id) }}">@csrf @method('PUT')
        @include('admin.roles._form')<div class="mt-5 flex justify-end gap-2"><a href="{{ route('admin.roles.index') }}"
                class="rounded-lg border border-[#dedfe6] bg-white px-4 py-2.5 text-[11px] font-bold text-[#525252]">Batal</a>
            @unless ($role->is_super_admin)
                <button class="rounded-lg bg-[#0d21a1] px-4 py-2.5 text-[11px] font-bold text-white hover:bg-[#091772]">Simpan
                    perubahan</button>
            @endunless
        </div>
    </form>
@endsection
