<?php

namespace App\Interfaces;

use App\Models\Account;

interface AccountTransferableServiceInterface
{
    public function validateSufficientFundsToTransfer(Account $account, float $amountToTransfer): void;

    public function transferFunds(Account $payerAccount, Account $payeeAccount, float $amountToTransfer);
}
