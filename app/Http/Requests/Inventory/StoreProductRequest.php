<?php

namespace App\Http\Requests\Inventory;

use App\Enums\ProductType;
use App\Models\Inventory\Product;
use App\Rules\UniqueMasterName;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('master_data.create') ?? false;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'track_serial' => $this->boolean('track_serial'),
            'is_active' => $this->boolean('is_active'),
            'min_stock' => $this->filled('min_stock') ? $this->input('min_stock') : 0,
        ]);
    }

    public function rules(): array
    {
        return [
            'product_name' => ['required', 'string', 'max:191', new UniqueMasterName('products', 'product_name', 'product_id', $this->recordId())],
            'category_id' => ['required', 'integer', Rule::exists('product_categories', 'category_id')->whereNull('deleted_at')->where('is_active', true)],
            'product_type' => ['required', Rule::enum(ProductType::class)],
            'unit' => ['required', 'string', Rule::in(Product::UNITS)],
            'selling_price' => ['required', 'numeric', 'gt:0', 'max:9999999999999999'],
            'purchase_price' => ['required', 'numeric', 'min:0', 'max:9999999999999999'],
            'min_stock' => ['required', 'numeric', 'min:0', 'max:999999999999999'],
            'track_serial' => ['boolean'],
            'is_active' => ['boolean'],
        ];
    }

    /** Jenis Jasa tidak memiliki stok minimum maupun nomor serial. */
    public function validated($key = null, $default = null): mixed
    {
        $data = parent::validated();

        if ($data['product_type'] === ProductType::SERVICE->value) {
            $data['min_stock'] = 0;
            $data['track_serial'] = false;
        }

        return $key === null ? $data : data_get($data, $key, $default);
    }

    public function attributes(): array
    {
        return [
            'product_name' => 'nama produk',
            'category_id' => 'kategori',
            'product_type' => 'jenis',
            'unit' => 'satuan',
            'selling_price' => 'harga jual',
            'purchase_price' => 'harga modal',
            'min_stock' => 'stok minimum',
        ];
    }

    protected function recordId(): ?int
    {
        return null;
    }
}
