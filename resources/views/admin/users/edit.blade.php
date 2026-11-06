@extends('admin.layouts.layout')
@section('title', 'Edit Pengguna')
@section('page_title', 'Edit Pengguna')
@section('page_breadcrumb', 'Administration / User Management / Edit')
@section('content')
    <div class="mb-5"><a href="{{ route('admin.users.show', $user->user_id) }}"
            class="text-[11px] font-bold text-[#0d21a1] hover:underline">← Kembali ke detail pengguna</a>
        <h1 class="mt-3 text-[23px] font-extrabold tracking-tight text-[#171717]">Edit pengguna</h1>
        <p class="mt-1 text-[12px] text-[#737373]">Perbarui informasi akun dan penempatan role.</p>
    </div>
    <form method="POST" action="{{ route('admin.users.update', $user->user_id) }}">@csrf @method('PUT')
        @include('admin.users._form')<div class="mt-5 flex flex-wrap justify-end gap-2"><a
                href="{{ route('admin.users.show', $user->user_id) }}"
                class="rounded-lg border border-[#dedfe6] bg-white px-4 py-2.5 text-[11px] font-bold text-[#525252]">Batal</a><button
                class="rounded-lg bg-[#0d21a1] px-4 py-2.5 text-[11px] font-bold text-white hover:bg-[#091772]">Simpan
                perubahan</button></div>
    </form>
@endsection
