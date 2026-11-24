<?php

namespace App\Http\Requests\Finance;

use App\Models\Finance\BankAccount;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBankAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('master_data.create') ?? false;
    }

    public function rules(): array
    {
        return [
            'bank_name' => ['required', 'string', Rule::in(BankAccount::BANKS)],
            'purpose' => ['required', 'string', Rule::in(BankAccount::PURPOSES)],
            'account_holder' => ['required', 'string', 'max:180'],
            'account_number' => [
                'required', 'string', 'max:100', 'regex:/^[0-9][0-9\- ]*$/',
                // account_number unik di database, termasuk data yang sudah dihapus sementara.
                Rule::unique('bank_accounts', 'account_number')->ignore($this->recordId(), 'bank_account_id'),
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'bank_name' => 'bank',
            'purpose' => 'peruntukan',
            'account_holder' => 'atas nama',
            'account_number' => 'nomor rekening',
        ];
    }

    protected function recordId(): ?int
    {
        return null;
    }
}
