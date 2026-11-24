@include('master-data.partials.field', ['name' => 'bank_name', 'label' => 'Bank', 'type' => 'select', 'required' => true, 'placeholder' => 'Pilih bank', 'options' => $banks, 'value' => $record->bank_name])
@include('master-data.partials.field', ['name' => 'purpose', 'label' => 'Peruntukan', 'type' => 'select', 'required' => true, 'options' => $purposes, 'value' => $record->purpose])
@include('master-data.partials.field', ['name' => 'account_holder', 'label' => 'Atas nama', 'required' => true, 'full' => true, 'maxlength' => 180, 'value' => $record->account_holder])
@include('master-data.partials.field', ['name' => 'account_number', 'label' => 'Nomor rekening', 'required' => true, 'full' => true, 'maxlength' => 100, 'value' => $record->account_number])
