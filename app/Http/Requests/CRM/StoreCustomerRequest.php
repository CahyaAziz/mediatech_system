<?php

namespace App\Http\Requests\CRM;

use App\Models\CRM\Customer;
use App\Rules\UniqueMasterName;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('master_data.create') ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:180', new UniqueMasterName('customers', 'name', 'customer_id', $this->recordId())],
            'industry_sector' => ['required', 'string', Rule::in(Customer::SECTORS)],
            'phone' => ['required', 'string', 'max:191'],
            'address' => ['required', 'string', 'max:191'],
            'email' => ['required', 'email', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nama customer',
            'industry_sector' => 'sektor industri',
            'phone' => 'telepon',
            'address' => 'alamat',
            'email' => 'email',
        ];
    }

    protected function recordId(): ?int
    {
        return null;
    }
}
