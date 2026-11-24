@extends('admin.layouts.layout')

@section('title', 'Master Data')
@section('page_title', 'Master Data')
@section('page_breadcrumb', 'Operations / Master Data / ' . $tab['plural'])

@php
    $canCreate = auth()->user()?->can('master_data.create');
    $canUpdate = auth()->user()?->can('master_data.update');
    $canDelete = auth()->user()?->can('master_data.soft_delete');
    $isProducts = $tab['key'] === 'products';
    $hasActions = $canUpdate || (!$isProducts && $canDelete);
    $indexRoute = route("master-data.{$tab['key']}.index");
    $tdClass = 'border-b border-[#f0f1f5] px-4 py-[13px] text-[11px] text-neutral-600';
@endphp

@section('content')
    <div class="mb-6 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-start">
        <div>
            <div class="mb-[7px] text-[12px] font-bold text-primary-500">Fondasi data</div>
            <h1
                class="m-0 font-heading text-[24px] font-semibold leading-tight tracking-[-0.8px] text-neutral-900 sm:text-[25px]">
                Master Data</h1>
            <p class="mt-[6px] max-w-2xl text-[12px] leading-relaxed text-neutral-500">Data referensi yang dipakai quotation,
                sales order, inventory, dan keuangan.</p>
        </div>
        @if ($canCreate)
            <a href="{{ route("master-data.{$tab['key']}.create") }}"
                class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg bg-primary-500 px-[14px] py-[10px] text-[12px] font-bold text-white transition hover:bg-primary-700"><svg
                    class="h-4 w-4 fill-none stroke-current [stroke-width:1.8] [stroke-linecap:round]" aria-hidden="true">
                    <use href="#i-plus"></use>
                </svg>Tambah {{ mb_strtolower($tab['label']) }}</a>
        @endif
    </div>

    {{-- Tab master data --}}
    <nav class="mb-4 overflow-x-auto" aria-label="Jenis master data">
        <div class="inline-flex min-w-max gap-1 rounded-xl border border-[#e8eaf0] bg-[#f1f2f5] p-1">
            @foreach ($tabs as $item)
                @php $active = $item['key'] === $tab['key']; @endphp
                <a href="{{ route($item['route']) }}" @if ($active) aria-current="page" @endif
                    class="inline-flex items-center gap-2 rounded-lg px-3 py-[8px] text-[12px] transition {{ $active ? 'bg-white font-bold text-primary-500 shadow-[0_1px_2px_rgba(20,27,55,0.08)]' : 'font-semibold text-neutral-600 hover:bg-white/70 hover:text-primary-500' }}">
                    <svg class="h-4 w-4 fill-none stroke-current [stroke-width:1.7] [stroke-linecap:round] [stroke-linejoin:round]"
                        aria-hidden="true">
                        <use href="#{{ $item['icon'] }}"></use>
                    </svg>
                    {{ $item['plural'] }}
                    <span
                        class="rounded-[5px] px-[6px] py-[1px] text-[10px] font-extrabold {{ $active ? 'bg-primary-50 text-primary-500' : 'bg-white text-neutral-500' }}">{{ number_format($item['count']) }}</span>
                </a>
            @endforeach
        </div>
    </nav>

    <section class="overflow-hidden rounded-xl border border-[#e8eaf0] bg-white shadow-[0_1px_2px_rgba(20,27,55,0.02)]">
        <div class="border-b border-[#f0f1f5] px-4 pb-3 pt-4 sm:px-[18px]">
            <h2 class="font-heading text-[15px] font-semibold text-neutral-800">{{ $tab['plural'] }}</h2>
            <p class="mt-1 max-w-3xl text-[11px] leading-relaxed text-neutral-500">{{ $tab['description'] }}</p>
        </div>

        <form id="master-filter-form" method="GET" action="{{ $indexRoute }}"
            class="flex flex-col gap-2 border-b border-[#f0f1f5] p-[14px] sm:flex-row sm:items-center sm:p-[16px]">
            <label class="relative block w-full sm:w-[260px]"><span class="sr-only">Cari data</span><svg
                    class="absolute left-[11px] top-1/2 h-4 w-4 -translate-y-1/2 fill-none stroke-current text-neutral-400 [stroke-width:1.7]"
                    aria-hidden="true">
                    <use href="#i-search"></use>
                </svg><input id="master-search" type="search" name="search" value="{{ request('search') }}"
                    placeholder="Cari data…"
                    class="h-[36px] w-full rounded-lg border border-[#dedfe6] bg-white pl-[34px] pr-3 text-[12px] outline-none focus:border-primary-300 focus:ring-2 focus:ring-primary-50"></label>
            @if (request()->filled('search'))
                <a href="{{ $indexRoute }}"
                    class="inline-flex h-[36px] items-center justify-center rounded-lg border border-[#dedfe6] bg-white px-3 text-[11px] font-semibold text-neutral-600 hover:bg-neutral-50">Reset</a>
            @endif
            <span class="text-[11px] text-neutral-500 sm:ml-auto">{{ number_format($records->total()) }} data</span>
        </form>

        @unless ($canCreate || $canUpdate)
            <div
                class="mx-4 mt-[14px] rounded-lg border border-primary-50 bg-[#f5f6fb] px-4 py-3 text-[11px] leading-relaxed text-neutral-600 sm:mx-[18px]">
                Akun kamu hanya dapat melihat data {{ mb_strtolower($tab['plural']) }}. Penambahan dan perubahan memerlukan
                izin master data dari administrator.</div>
        @endunless

        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] border-collapse text-left">
                @include('master-data.tables.' . $tab['key'])
            </table>
        </div>

        <div
            class="flex flex-col gap-3 border-t border-[#f0f1f5] px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex flex-wrap items-center gap-4 text-[10px] text-neutral-500"><span>Menampilkan
                    {{ $records->firstItem() ?? 0 }}–{{ $records->lastItem() ?? 0 }} dari
                    {{ $records->total() }}</span><label class="flex items-center gap-2">Tampilkan <select
                        id="master-per-page" name="per_page" form="master-filter-form"
                        class="h-8 rounded-md border border-[#dedfe6] bg-white px-2 text-[10px] text-neutral-600">
                        @foreach ($perPageOptions as $option)
                            <option value="{{ $option }}" @selected($perPage === $option)>{{ $option }}</option>
                        @endforeach
                    </select></label></div>
            <div class="flex items-center justify-end gap-2 text-[11px]">
                <a href="{{ $records->previousPageUrl() ?? '#' }}"
                    @if (!$records->previousPageUrl()) aria-disabled="true" tabindex="-1" @endif
                    class="rounded-md border border-neutral-200 px-3 py-2 font-semibold {{ $records->onFirstPage() ? 'pointer-events-none opacity-40' : 'text-neutral-600 hover:bg-[#f5f6fb]' }}">Sebelumnya</a>
                <span class="px-1 text-neutral-500">Halaman
                    {{ $records->currentPage() }}/{{ max(1, $records->lastPage()) }}</span>
                <a href="{{ $records->nextPageUrl() ?? '#' }}"
                    @if (!$records->hasMorePages()) aria-disabled="true" tabindex="-1" @endif
                    class="rounded-md border border-neutral-200 px-3 py-2 font-semibold {{ $records->hasMorePages() ? 'text-neutral-600 hover:bg-[#f5f6fb]' : 'pointer-events-none opacity-40' }}">Berikutnya</a>
            </div>
        </div>
    </section>

    <div
        class="mt-[14px] rounded-lg border border-primary-50 bg-[#f5f6fb] px-4 py-3 text-[11px] leading-relaxed text-neutral-600">
        <span class="font-bold text-primary-500">Catatan:</span> Kode dibuat otomatis dan tidak dapat diubah. Data yang masih
        dipakai transaksi tidak dapat dihapus. Produk dinonaktifkan, bukan dihapus, agar histori transaksi tetap utuh.
    </div>
@endsection

@push('scripts')
    <script>
        (() => {
            const form = document.getElementById('master-filter-form');
            if (!form) return;
            const search = document.getElementById('master-search');
            const perPage = document.getElementById('master-per-page');
            let searchTimer;
            perPage?.addEventListener('change', () => form.requestSubmit());

            // Modal konfirmasi (hapus / ubah status) memakai elemen <dialog> native.
            document.addEventListener('click', (event) => {
                const opener = event.target.closest('[data-open-dialog]');
                if (opener) document.getElementById(opener.dataset.openDialog)?.showModal();
                const closer = event.target.closest('[data-close-dialog]');
                if (closer) closer.closest('dialog')?.close();
            });
            search?.addEventListener('input', () => {
                window.clearTimeout(searchTimer);
                searchTimer = window.setTimeout(() => form.requestSubmit(), 350);
            });
        })();
    </script>
@endpush
