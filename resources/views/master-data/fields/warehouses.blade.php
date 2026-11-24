@include('master-data.partials.field', ['name' => 'warehouse_name', 'label' => 'Nama gudang', 'required' => true, 'full' => true, 'maxlength' => 191, 'value' => $record->warehouse_name])
@include('master-data.partials.field', ['name' => 'location', 'label' => 'Lokasi', 'required' => true, 'full' => true, 'maxlength' => 191, 'placeholder' => 'Kota / alamat singkat', 'value' => $record->location])
