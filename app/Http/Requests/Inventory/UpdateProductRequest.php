<?php

namespace App\Http\Requests\Inventory;

class UpdateProductRequest extends StoreProductRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('master_data.update') ?? false;
    }

    protected function recordId(): ?int
    {
        return $this->route('product')?->product_id;
    }
}
