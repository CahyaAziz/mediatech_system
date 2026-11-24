@php $columns = ['SKU', 'Nama produk', 'Kategori', 'Jenis', 'Satuan', 'Harga jual', 'Min. stok', 'Serial', 'Status']; @endphp
@include('master-data.partials.thead')
<tbody>
    @forelse ($records as $record)
        @php $isService = $record->product_type === \App\Enums\ProductType::SERVICE; @endphp
        <tr class="transition hover:bg-[#fcfcfe] {{ $record->is_active ? '' : 'opacity-60' }}">
            <td class="{{ $tdClass }} whitespace-nowrap font-semibold text-primary-500">{{ $record->sku }}</td>
            <td class="{{ $tdClass }} font-bold text-neutral-800">{{ $record->product_name }}</td>
            <td class="{{ $tdClass }}">{{ $record->category?->category_name ?? '—' }}</td>
            <td class="{{ $tdClass }}">
                <span
                    class="inline-flex rounded-[5px] px-2 py-1 text-[10px] font-bold {{ $isService ? 'bg-warning-50 text-secondary-800' : 'bg-[#f1f2f5] text-neutral-600' }}">{{ $record->product_type?->label() }}</span>
            </td>
            <td class="{{ $tdClass }}">{{ $record->unit }}</td>
            <td class="{{ $tdClass }} whitespace-nowrap font-bold text-neutral-800">Rp
                {{ number_format((float) $record->selling_price, 0, ',', '.') }}</td>
            <td class="{{ $tdClass }}">
                {{ $isService ? '—' : number_format((float) $record->min_stock, 0, ',', '.') }}</td>
            <td class="{{ $tdClass }}">
                @if ($record->track_serial)
                    <span
                        class="inline-flex whitespace-nowrap rounded-[5px] bg-[#f1f2f5] px-2 py-1 text-[10px] font-bold text-neutral-600">Per
                        serial</span>@else<span class="text-neutral-400">—</span>
                @endif
            </td>
            <td class="{{ $tdClass }}">
                @if ($record->is_active)
                    <span
                        class="inline-flex items-center gap-[5px] rounded-[5px] bg-success-50 px-2 py-1 text-[10px] font-extrabold text-[#16805d]"><span
                        class="h-[5px] w-[5px] rounded-full bg-current"></span>Aktif</span>@else<span
                        class="inline-flex items-center gap-[5px] rounded-[5px] bg-error-50 px-2 py-1 text-[10px] font-extrabold text-[#b54740]"><span
                            class="h-[5px] w-[5px] rounded-full bg-current"></span>Nonaktif</span>
                @endif
            </td>
            @include('master-data.partials.actions')
        </tr>
    @empty
        @include('master-data.partials.empty', ['colspan' => count($columns) + 1])
    @endforelse
</tbody>
