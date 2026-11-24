<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\MasterDataController;
use App\Http\Requests\CRM\StoreSupplierRequest;
use App\Http\Requests\CRM\UpdateSupplierRequest;
use App\Models\CRM\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplierController extends MasterDataController
{
    protected function tab(): string
    {
        return 'suppliers';
    }

    public function index(Request $request): View
    {
        return $this->indexView(
            $request,
            Supplier::query()->notDeleted()->orderByDesc('supplier_code'),
            ['supplier_code', 'name', 'phone', 'address'],
        );
    }

    public function create(): View
    {
        return $this->formView(new Supplier());
    }

    public function store(StoreSupplierRequest $request): RedirectResponse
    {
        return $this->storeRecord($request, $request->validated());
    }

    public function edit(Supplier $supplier): View
    {
        return $this->formView($supplier);
    }

    public function update(UpdateSupplierRequest $request, Supplier $supplier): RedirectResponse
    {
        return $this->updateRecord($request, $supplier, $request->validated());
    }

    public function destroy(Request $request, Supplier $supplier): RedirectResponse
    {
        return $this->destroyRecord($request, $supplier);
    }
}
