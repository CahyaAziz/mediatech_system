@include('master-data.partials.field', ['name' => 'name', 'label' => 'Nama supplier', 'required' => true, 'full' => true, 'maxlength' => 180, 'value' => $record->name])
@include('master-data.partials.field', ['name' => 'phone', 'label' => 'Telepon', 'required' => true, 'maxlength' => 191, 'value' => $record->phone])
@include('master-data.partials.field', ['name' => 'address', 'label' => 'Alamat', 'type' => 'textarea', 'required' => true, 'full' => true, 'maxlength' => 191, 'value' => $record->address])
