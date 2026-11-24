<?php

namespace App\Http\Requests\CRM;

class UpdateCustomerRequest extends StoreCustomerRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('master_data.update') ?? false;
    }

    protected function recordId(): ?int
    {
        return $this->route('customer')?->customer_id;
    }
}
