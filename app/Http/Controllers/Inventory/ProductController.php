<?php

namespace App\Http\Controllers\Inventory;

use App\Enums\ProductType;
use App\Http\Controllers\MasterDataController;
use App\Http\Requests\Inventory\StoreProductRequest;
use App\Http\Requests\Inventory\UpdateProductRequest;
use App\Models\Inventory\Product;
use App\Models\Inventory\ProductCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends MasterDataController
{
    protected function tab(): string
    {
        return 'products';
    }

    public function index(Request $request): View
    {
        return $this->indexView(
            $request,
            Product::query()->notDeleted()->with('category')->orderByDesc('is_active')->orderBy('sku'),
            ['sku', 'product_name', 'product_type', 'unit'],
        );
    }

    public function create(): View
    {
        return $this->formView(new Product(['is_active' => true, 'product_type' => ProductType::GOODS->value, 'unit' => 'Unit']), $this->formOptions());
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        return $this->storeRecord($request, $request->validated());
    }

    public function edit(Product $product): View
    {
        return $this->formView($product, $this->formOptions($product));
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        return $this->updateRecord($request, $product, $request->validated());
    }

    /** Produk tidak dihapus; produk dinonaktifkan agar tidak muncul di quotation baru. */
    public function toggleStatus(Request $request, Product $product): RedirectResponse
    {
        $product = $this->masterData->toggleActive('products', $product, $request->user());

        return $this->backToIndex($product->product_name . ($product->is_active ? ' diaktifkan.' : ' dinonaktifkan.'));
    }

    private function formOptions(?Product $product = null): array
    {
        return [
            'categories' => ProductCategory::query()
                ->notDeleted()
                ->where(fn ($query) => $query->where('is_active', true)
                    ->when($product, fn ($q) => $q->orWhere('category_id', $product->category_id)))
                ->orderBy('category_name')
                ->get(),
            'productTypes' => ProductType::cases(),
            'units' => Product::UNITS,
        ];
    }
}
