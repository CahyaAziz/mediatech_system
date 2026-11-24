@php $columns = ['Kode', 'Nama customer', 'Sektor', 'Alamat', 'Telepon', 'Email']; @endphp
@include('master-data.partials.thead')
<tbody>
    @forelse ($records as $record)
        <tr class="transition hover:bg-[#fcfcfe]">
            <td class="{{ $tdClass }} font-semibold text-primary-500">{{ $record->customer_code }}</td>
            <td class="{{ $tdClass }} font-bold text-neutral-800">{{ $record->name }}</td>
            <td class="{{ $tdClass }}">{{ $record->industry_sector ?: '—' }}</td>
            <td class="{{ $tdClass }} max-w-[240px]">{{ $record->address ?: '—' }}</td>
            <td class="{{ $tdClass }} whitespace-nowrap">{{ $record->phone ?: '—' }}</td>
            <td class="{{ $tdClass }}">{{ $record->email ?: '—' }}</td>
            @include('master-data.partials.actions')
        </tr>
    @empty
        @include('master-data.partials.empty', ['colspan' => count($columns) + 1])
    @endforelse
</tbody>
