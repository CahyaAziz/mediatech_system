@php $columns = ['Kode', 'Nama kategori', 'Jumlah produk']; @endphp
@include('master-data.partials.thead')
<tbody>
    @forelse ($records as $record)
        <tr class="transition hover:bg-[#fcfcfe]">
            <td class="{{ $tdClass }} font-semibold text-primary-500">{{ $record->category_code }}</td>
            <td class="{{ $tdClass }} font-bold text-neutral-800">{{ $record->category_name }}</td>
            <td class="{{ $tdClass }}">{{ number_format($record->products_count) }}</td>
            @include('master-data.partials.actions')
        </tr>
    @empty
        @include('master-data.partials.empty', ['colspan' => count($columns) + 1])
    @endforelse
</tbody>
