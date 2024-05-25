<?php

namespace App\Services\Account;

use App\Exceptions\Transaction\InsufficientFundsException;
use App\Interfaces\AccountTransferableServiceInterface;
use App\Models\Account;
use App\Repositories\Account\AccountRepository;

class AccountCommonService extends AccountService implements AccountTransferableServiceInterface
{
    public function __construct(AccountRepository $accountRepository)
    {
        parent::__construct($accountRepository);
    }

    /**
     * @throws InsufficientFundsException
     */
    public function validateSufficientFundsToTransfer(Account $account, float $amountToTransfer): void
    {
        if ($account->balance < $amountToTransfer) {
            throw new InsufficientFundsException();
        }
    }

    /**
     * @throws InsufficientFundsException
     */
    public function transferFunds(Account $payerAccount, Account $payeeAccount, float $amountToTransfer): void
    {
        $this->validateSufficientFundsToTransfer($payerAccount, $amountToTransfer);

        $newPayerAmount = $payerAccount->balance - $amountToTransfer;
        $this->accountRepository->updateBalance($payerAccount, $newPayerAmount);

        $newPayeeAmount = $payeeAccount->balance + $amountToTransfer;
        $this->accountRepository->updateBalance($payeeAccount, $newPayeeAmount);
    }
}
