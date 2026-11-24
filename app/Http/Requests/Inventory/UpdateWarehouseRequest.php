<?php

namespace App\Http\Requests\Inventory;

class UpdateWarehouseRequest extends StoreWarehouseRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('master_data.update') ?? false;
    }

    protected function recordId(): ?int
    {
        return $this->route('warehouse')?->warehouse_id;
    }
}
