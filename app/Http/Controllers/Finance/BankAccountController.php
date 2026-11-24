<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\MasterDataController;
use App\Http\Requests\Finance\StoreBankAccountRequest;
use App\Http\Requests\Finance\UpdateBankAccountRequest;
use App\Models\Finance\BankAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BankAccountController extends MasterDataController
{
    protected function tab(): string
    {
        return 'bank-accounts';
    }

    public function index(Request $request): View
    {
        return $this->indexView(
            $request,
            BankAccount::query()->notDeleted()->orderBy('account_code'),
            ['account_code', 'bank_name', 'account_holder', 'account_number', 'purpose'],
        );
    }

    public function create(): View
    {
        return $this->formView(new BankAccount(), $this->formOptions());
    }

    public function store(StoreBankAccountRequest $request): RedirectResponse
    {
        return $this->storeRecord($request, $request->validated());
    }

    public function edit(BankAccount $bankAccount): View
    {
        return $this->formView($bankAccount, $this->formOptions());
    }

    public function update(UpdateBankAccountRequest $request, BankAccount $bankAccount): RedirectResponse
    {
        return $this->updateRecord($request, $bankAccount, $request->validated());
    }

    public function destroy(Request $request, BankAccount $bankAccount): RedirectResponse
    {
        return $this->destroyRecord($request, $bankAccount);
    }

    private function formOptions(): array
    {
        return ['banks' => BankAccount::BANKS, 'purposes' => BankAccount::PURPOSES];
    }
}
