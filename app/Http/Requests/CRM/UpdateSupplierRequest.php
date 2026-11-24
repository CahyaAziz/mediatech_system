<?php

namespace App\Http\Requests\CRM;

class UpdateSupplierRequest extends StoreSupplierRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('master_data.update') ?? false;
    }

    protected function recordId(): ?int
    {
        return $this->route('supplier')?->supplier_id;
    }
}
