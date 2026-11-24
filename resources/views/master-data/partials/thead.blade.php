{{-- Header tabel master data. Variabel: $columns (array label), $hasActions dari index. --}}
<thead>
    <tr class="bg-neutral-50">
        @foreach ($columns as $column)
            <th class="border-b border-[#eeeeee] px-4 py-3 text-[11px] font-bold text-neutral-500">{{ $column }}</th>
        @endforeach
        @if ($hasActions)
            <th class="w-[100px] border-b border-[#eeeeee] px-4 py-3 text-[11px] font-bold text-neutral-500">Aksi</th>
        @endif
    </tr>
</thead>
