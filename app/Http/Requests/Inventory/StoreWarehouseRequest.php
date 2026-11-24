<?php

namespace App\Http\Requests\Inventory;

use App\Rules\UniqueMasterName;
use Illuminate\Foundation\Http\FormRequest;

class StoreWarehouseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('master_data.create') ?? false;
    }

    public function rules(): array
    {
        return [
            'warehouse_name' => ['required', 'string', 'max:191', new UniqueMasterName('warehouses', 'warehouse_name', 'warehouse_id', $this->recordId())],
            'location' => ['required', 'string', 'max:191'],
        ];
    }

    public function attributes(): array
    {
        return ['warehouse_name' => 'nama gudang', 'location' => 'lokasi'];
    }

    protected function recordId(): ?int
    {
        return null;
    }
}
