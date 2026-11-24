{{-- Kolom aksi per baris. Variabel: $record, $tab, $canUpdate, $canDelete, $isProducts, $hasActions. --}}
@if ($hasActions)
    @php
        $recordKey = $record->getKey();
        $recordName = $tab['key'] === 'bank-accounts'
            ? $record->bank_name . ' ' . $record->account_number
            : $record->{$tab['name_column']};
        $dialogId = 'master-dialog-' . $tab['key'] . '-' . $recordKey;
        $iconButton = 'grid h-8 w-8 place-items-center rounded-lg border border-transparent transition';
        $icon = 'h-4 w-4 fill-none stroke-current [stroke-width:1.7] [stroke-linecap:round] [stroke-linejoin:round]';
    @endphp
    <td class="border-b border-[#f0f1f5] px-4 py-[10px]">
        <div class="flex items-center gap-1">
            @if ($canUpdate)
                <a href="{{ route("master-data.{$tab['key']}.edit", $recordKey) }}" title="Ubah"
                    aria-label="Ubah {{ $recordName }}"
                    class="{{ $iconButton }} text-neutral-600 hover:border-primary-50 hover:bg-[#f5f6fb] hover:text-primary-500"><svg
                        class="{{ $icon }}" aria-hidden="true">
                        <use href="#i-edit"></use>
                    </svg></a>
            @endif

            @if ($isProducts && $canUpdate)
                <button type="button" data-open-dialog="{{ $dialogId }}"
                    title="{{ $record->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                    aria-label="{{ $record->is_active ? 'Nonaktifkan' : 'Aktifkan' }} {{ $recordName }}"
                    class="{{ $iconButton }} text-neutral-600 hover:border-primary-50 hover:bg-[#f5f6fb] hover:text-primary-500"><svg
                        class="{{ $icon }}" aria-hidden="true">
                        <use href="#{{ $record->is_active ? 'i-power' : 'i-check' }}"></use>
                    </svg></button>
                <dialog id="{{ $dialogId }}"
                    class="m-auto w-[min(92vw,420px)] rounded-xl border border-[#e8eaf0] bg-white p-0 shadow-xl backdrop:bg-[#111827]/40">
                    <form method="POST" action="{{ route('master-data.products.status', $recordKey) }}" class="p-5">
                        @csrf
                        @method('PATCH')
                        <p class="font-heading text-[14px] font-semibold text-neutral-900">
                            {{ $record->is_active ? 'Nonaktifkan' : 'Aktifkan' }} produk?</p>
                        <p class="mt-2 text-[12px] leading-relaxed text-neutral-600">
                            <b>{{ $recordName }}</b>
                            {{ $record->is_active ? 'tidak akan bisa dipilih di quotation baru.' : 'akan bisa dipilih kembali di quotation baru.' }}
                        </p>
                        <div class="mt-5 flex justify-end gap-2">
                            <button type="button" data-close-dialog
                                class="rounded-lg border border-[#dedfe6] bg-white px-4 py-2.5 text-[11px] font-bold text-neutral-600">Batal</button>
                            <button
                                class="rounded-lg bg-primary-500 px-4 py-2.5 text-[11px] font-bold text-white hover:bg-primary-700">{{ $record->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                        </div>
                    </form>
                </dialog>
            @endif

            @if (!$isProducts && $canDelete)
                <button type="button" data-open-dialog="{{ $dialogId }}" title="Hapus"
                    aria-label="Hapus {{ $recordName }}"
                    class="{{ $iconButton }} text-[#b54740] hover:border-[#f2c8c5] hover:bg-error-50"><svg
                        class="{{ $icon }}" aria-hidden="true">
                        <use href="#i-trash"></use>
                    </svg></button>
                <dialog id="{{ $dialogId }}"
                    class="m-auto w-[min(92vw,420px)] rounded-xl border border-[#e8eaf0] bg-white p-0 shadow-xl backdrop:bg-[#111827]/40">
                    <form method="POST" action="{{ route("master-data.{$tab['key']}.destroy", $recordKey) }}"
                        class="p-5">
                        @csrf
                        @method('DELETE')
                        <p class="font-heading text-[14px] font-semibold text-neutral-900">Hapus
                            {{ mb_strtolower($tab['label']) }}?</p>
                        <p class="mt-2 text-[12px] leading-relaxed text-neutral-600">Hapus <b>{{ $recordName }}</b> dari
                            master data? Data masuk ke Sampah dan masih dapat dipulihkan selama masa retensi.</p>
                        <label for="{{ $dialogId }}-reason"
                            class="mt-4 block text-[11px] font-bold text-neutral-700">Alasan penghapusan <span
                                class="text-[#b54740]">*</span></label>
                        <textarea id="{{ $dialogId }}-reason" name="deletion_reason" required minlength="5" maxlength="500"
                            placeholder="Jelaskan alasan penghapusan"
                            class="mt-2 min-h-20 w-full rounded-lg border border-[#dedfe6] p-3 text-[12px] outline-none focus:border-primary-300 focus:ring-2 focus:ring-primary-50"></textarea>
                        <div class="mt-5 flex justify-end gap-2">
                            <button type="button" data-close-dialog
                                class="rounded-lg border border-[#dedfe6] bg-white px-4 py-2.5 text-[11px] font-bold text-neutral-600">Batal</button>
                            <button
                                class="rounded-lg bg-[#b54740] px-4 py-2.5 text-[11px] font-bold text-white hover:bg-[#9c3b35]">Hapus</button>
                        </div>
                    </form>
                </dialog>
            @endif
        </div>
    </td>
@endif
