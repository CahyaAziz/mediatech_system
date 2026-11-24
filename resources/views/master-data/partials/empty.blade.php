<tr>
    <td colspan="{{ $colspan }}" class="px-4 py-12 text-center">
        <svg class="mx-auto mb-2 h-8 w-8 fill-none stroke-current text-neutral-400 [stroke-width:1.5]" aria-hidden="true">
            <use href="#i-database"></use>
        </svg>
        <p class="text-[12px] font-bold text-neutral-700">Belum ada data</p>
        <p class="mt-1 text-[11px] text-neutral-500">
            @if (request()->filled('search'))
                Ubah kata kunci pencarian untuk melihat data lainnya.
            @elseif ($canCreate)
                Klik tombol tambah untuk mengisi {{ mb_strtolower($tab['plural']) }}.
            @else
                Data {{ mb_strtolower($tab['plural']) }} belum tersedia.
            @endif
        </p>
    </td>
</tr>
