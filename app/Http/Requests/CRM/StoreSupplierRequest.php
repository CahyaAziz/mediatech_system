<?php

namespace App\Http\Requests\CRM;

use App\Rules\UniqueMasterName;
use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('master_data.create') ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:180', new UniqueMasterName('suppliers', 'name', 'supplier_id', $this->recordId())],
            'phone' => ['required', 'string', 'max:191'],
            'address' => ['required', 'string', 'max:191'],
        ];
    }

    public function attributes(): array
    {
        return ['name' => 'nama supplier', 'phone' => 'telepon', 'address' => 'alamat'];
    }

    protected function recordId(): ?int
    {
        return null;
    }
}
