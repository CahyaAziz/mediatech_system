@extends('admin.layouts.layout')

@php
    $isEdit = $record->exists;
    $action = ($isEdit ? 'Ubah ' : 'Tambah ') . mb_strtolower($tab['label']);
    $indexRoute = route("master-data.{$tab['key']}.index");
@endphp

@section('title', ucfirst($action))
@section('page_title', 'Master Data')
@section('page_breadcrumb', 'Operations / Master Data / ' . $tab['plural'] . ' / ' . ($isEdit ? 'Ubah' : 'Tambah'))

@section('content')
    <div class="mb-5">
        <a href="{{ $indexRoute }}" class="text-[11px] font-bold text-primary-500 hover:underline">← Kembali ke
            {{ $tab['plural'] }}</a>
        <h1 class="mt-3 font-heading text-[23px] font-semibold tracking-tight text-neutral-900">{{ ucfirst($action) }}</h1>
        <p class="mt-1 text-[12px] text-neutral-500">
            {{ $isEdit ? 'Kode tidak dapat diubah.' : 'Kode dibuat otomatis. Kolom bertanda * wajib diisi.' }}</p>
    </div>

    <form method="POST"
        action="{{ $isEdit ? route("master-data.{$tab['key']}.update", $record->getKey()) : route("master-data.{$tab['key']}.store") }}">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <section class="max-w-3xl rounded-xl border border-[#e8eaf0] bg-white p-5">
            <h2 class="font-heading text-[14px] font-semibold text-neutral-800">Informasi {{ mb_strtolower($tab['label']) }}
            </h2>
            <p class="mt-1 text-[11px] text-neutral-500">{{ $tab['description'] }}</p>

            <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="code" class="mb-2 block text-[11px] font-bold text-neutral-700">
                        {{ $tab['key'] === 'products' ? 'SKU' : 'Kode' }}</label>
                    <input id="code" value="{{ $code }}" disabled
                        class="h-10 w-full rounded-lg border border-neutral-200 bg-neutral-50 px-3 text-[12px] font-semibold text-neutral-600">
                    @unless ($isEdit)
                        <p class="mt-1 text-[10px] text-neutral-500">Perkiraan kode; nomor final ditetapkan saat data disimpan.</p>
                    @endunless
                </div>

                @include('master-data.fields.' . $tab['key'])
            </div>
        </section>

        <div class="mt-5 flex max-w-3xl flex-wrap justify-end gap-2">
            <a href="{{ $indexRoute }}"
                class="rounded-lg border border-[#dedfe6] bg-white px-4 py-2.5 text-[11px] font-bold text-neutral-600">Batal</a>
            <button
                class="rounded-lg bg-primary-500 px-4 py-2.5 text-[11px] font-bold text-white hover:bg-primary-700">Simpan</button>
        </div>
    </form>
@endsection
