@php $columns = ['Kode', 'Nama supplier', 'Telepon', 'Alamat']; @endphp
@include('master-data.partials.thead')
<tbody>
    @forelse ($records as $record)
        <tr class="transition hover:bg-[#fcfcfe]">
            <td class="{{ $tdClass }} font-semibold text-primary-500">{{ $record->supplier_code }}</td>
            <td class="{{ $tdClass }} font-bold text-neutral-800">{{ $record->name }}</td>
            <td class="{{ $tdClass }} whitespace-nowrap">{{ $record->phone ?: '—' }}</td>
            <td class="{{ $tdClass }}">{{ $record->address ?: '—' }}</td>
            @include('master-data.partials.actions')
        </tr>
    @empty
        @include('master-data.partials.empty', ['colspan' => count($columns) + 1])
    @endforelse
</tbody>
