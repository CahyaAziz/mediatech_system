<?php

namespace App\Http\Requests\Inventory;

class UpdateProductCategoryRequest extends StoreProductCategoryRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('master_data.update') ?? false;
    }

    protected function recordId(): ?int
    {
        return $this->route('category')?->category_id;
    }
}
