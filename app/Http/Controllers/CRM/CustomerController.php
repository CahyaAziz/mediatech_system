<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\MasterDataController;
use App\Http\Requests\CRM\StoreCustomerRequest;
use App\Http\Requests\CRM\UpdateCustomerRequest;
use App\Models\CRM\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends MasterDataController
{
    protected function tab(): string
    {
        return 'customers';
    }

    public function index(Request $request): View
    {
        return $this->indexView(
            $request,
            Customer::query()->notDeleted()->orderByDesc('customer_code'),
            ['customer_code', 'name', 'industry_sector', 'address', 'phone', 'email'],
        );
    }

    public function create(): View
    {
        return $this->formView(new Customer(), ['sectors' => Customer::SECTORS]);
    }

    public function store(StoreCustomerRequest $request): RedirectResponse
    {
        return $this->storeRecord($request, $request->validated());
    }

    public function edit(Customer $customer): View
    {
        return $this->formView($customer, ['sectors' => Customer::SECTORS]);
    }

    public function update(UpdateCustomerRequest $request, Customer $customer): RedirectResponse
    {
        return $this->updateRecord($request, $customer, $request->validated());
    }

    public function destroy(Request $request, Customer $customer): RedirectResponse
    {
        return $this->destroyRecord($request, $customer);
    }
}
