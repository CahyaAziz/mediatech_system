<?php

namespace App\Http\Requests\Inventory;

use App\Rules\UniqueMasterName;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('master_data.create') ?? false;
    }

    public function rules(): array
    {
        return [
            // category_name unik di database, termasuk data yang sudah dihapus sementara.
            'category_name' => ['required', 'string', 'max:191', new UniqueMasterName('product_categories', 'category_name', 'category_id', $this->recordId(), includeDeleted: true)],
        ];
    }

    public function attributes(): array
    {
        return ['category_name' => 'nama kategori'];
    }

    protected function recordId(): ?int
    {
        return null;
    }
}
