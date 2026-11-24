<?php

namespace App\Http\Requests\Finance;

class UpdateBankAccountRequest extends StoreBankAccountRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('master_data.update') ?? false;
    }

    protected function recordId(): ?int
    {
        return $this->route('bankAccount')?->bank_account_id;
    }
}
