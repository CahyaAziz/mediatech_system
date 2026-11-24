@php $columns = ['Kode', 'Bank', 'Atas nama', 'Nomor rekening', 'Peruntukan']; @endphp
@include('master-data.partials.thead')
<tbody>
    @forelse ($records as $record)
        <tr class="transition hover:bg-[#fcfcfe]">
            <td class="{{ $tdClass }} font-semibold text-primary-500">{{ $record->account_code }}</td>
            <td class="{{ $tdClass }} font-bold text-neutral-800">{{ $record->bank_name }}</td>
            <td class="{{ $tdClass }}">{{ $record->account_holder }}</td>
            <td class="{{ $tdClass }} whitespace-nowrap font-mono">{{ $record->account_number }}</td>
            <td class="{{ $tdClass }}">{{ $record->purpose ?: '—' }}</td>
            @include('master-data.partials.actions')
        </tr>
    @empty
        @include('master-data.partials.empty', ['colspan' => count($columns) + 1])
    @endforelse
</tbody>
