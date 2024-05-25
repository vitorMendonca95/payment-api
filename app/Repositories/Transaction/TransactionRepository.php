<?php

namespace App\Repositories\Transaction;

use App\Enums\TransactionStatusEnum;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;

class TransactionRepository
{
    public function __construct(protected Transaction $transaction)
    {
    }

    public function create(array $transactionData): Transaction
    {
        $this->transaction->fill($transactionData);
        $this->transaction->save();

        return $this->transaction;
    }

    public function updateStatus(TransactionStatusEnum $transactionStatusEnum): Transaction
    {
        $this->transaction->status = $transactionStatusEnum->value;
        $this->transaction->save();

        return $this->transaction;
    }
}
