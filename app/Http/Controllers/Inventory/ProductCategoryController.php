<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\MasterDataController;
use App\Http\Requests\Inventory\StoreProductCategoryRequest;
use App\Http\Requests\Inventory\UpdateProductCategoryRequest;
use App\Models\Inventory\ProductCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductCategoryController extends MasterDataController
{
    protected function tab(): string
    {
        return 'categories';
    }

    public function index(Request $request): View
    {
        return $this->indexView(
            $request,
            ProductCategory::query()
                ->notDeleted()
                ->withCount(['products' => fn ($query) => $query->whereNull('deleted_at')])
                ->orderBy('category_code'),
            ['category_code', 'category_name'],
        );
    }

    public function create(): View
    {
        return $this->formView(new ProductCategory());
    }

    public function store(StoreProductCategoryRequest $request): RedirectResponse
    {
        return $this->storeRecord($request, $request->validated());
    }

    public function edit(ProductCategory $category): View
    {
        return $this->formView($category);
    }

    public function update(UpdateProductCategoryRequest $request, ProductCategory $category): RedirectResponse
    {
        return $this->updateRecord($request, $category, $request->validated());
    }

    public function destroy(Request $request, ProductCategory $category): RedirectResponse
    {
        return $this->destroyRecord($request, $category);
    }
}
