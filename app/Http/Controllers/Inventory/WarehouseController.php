<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\MasterDataController;
use App\Http\Requests\Inventory\StoreWarehouseRequest;
use App\Http\Requests\Inventory\UpdateWarehouseRequest;
use App\Models\Inventory\Warehouse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WarehouseController extends MasterDataController
{
    protected function tab(): string
    {
        return 'warehouses';
    }

    public function index(Request $request): View
    {
        return $this->indexView(
            $request,
            Warehouse::query()->notDeleted()->orderBy('warehouse_code'),
            ['warehouse_code', 'warehouse_name', 'location'],
        );
    }

    public function create(): View
    {
        return $this->formView(new Warehouse());
    }

    public function store(StoreWarehouseRequest $request): RedirectResponse
    {
        return $this->storeRecord($request, $request->validated());
    }

    public function edit(Warehouse $warehouse): View
    {
        return $this->formView($warehouse);
    }

    public function update(UpdateWarehouseRequest $request, Warehouse $warehouse): RedirectResponse
    {
        return $this->updateRecord($request, $warehouse, $request->validated());
    }

    public function destroy(Request $request, Warehouse $warehouse): RedirectResponse
    {
        return $this->destroyRecord($request, $warehouse);
    }
}
