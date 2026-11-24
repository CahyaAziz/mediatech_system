@php $columns = ['Kode', 'Nama gudang', 'Lokasi']; @endphp
@include('master-data.partials.thead')
<tbody>
    @forelse ($records as $record)
        <tr class="transition hover:bg-[#fcfcfe]">
            <td class="{{ $tdClass }} font-semibold text-primary-500">{{ $record->warehouse_code }}</td>
            <td class="{{ $tdClass }} font-bold text-neutral-800">{{ $record->warehouse_name }}</td>
            <td class="{{ $tdClass }}">{{ $record->location ?: '—' }}</td>
            @include('master-data.partials.actions')
        </tr>
    @empty
        @include('master-data.partials.empty', ['colspan' => count($columns) + 1])
    @endforelse
</tbody>
