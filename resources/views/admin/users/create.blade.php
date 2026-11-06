@extends('admin.layouts.layout')
@section('title', 'Tambah Pengguna')
@section('page_title', 'Tambah Pengguna')
@section('page_breadcrumb', 'Administration / User Management / Tambah')
@section('content')
    <div class="mb-5"><a href="{{ route('admin.users.index') }}"
            class="text-[11px] font-bold text-[#0d21a1] hover:underline">← Kembali ke User Management</a>
        <h1 class="mt-3 text-[23px] font-extrabold tracking-tight text-[#171717]">Tambah pengguna</h1>
        <p class="mt-1 text-[12px] text-[#737373]">Buat akun internal dan tentukan role sesuai tanggung jawabnya.</p>
    </div>
    <form method="POST" action="{{ route('admin.users.store') }}">@csrf @include('admin.users._form')<div
            class="mt-5 flex flex-wrap justify-end gap-2"><a href="{{ route('admin.users.index') }}"
                class="rounded-lg border border-[#dedfe6] bg-white px-4 py-2.5 text-[11px] font-bold text-[#525252]">Batal</a><button
                class="rounded-lg bg-[#0d21a1] px-4 py-2.5 text-[11px] font-bold text-white hover:bg-[#091772]">Simpan
                pengguna</button></div>
    </form>
@endsection
