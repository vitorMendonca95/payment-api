<?php

namespace App\Services\Transaction;

use App\Jobs\SendTransferenceNotification;
use App\Models\Transaction;
use App\Models\User;

class TransactionNotificationService
{
    public function sendNotificationToPayee(User $payeeUser, Transaction $transaction): void
    {
        SendTransferenceNotification::dispatch($payeeUser->email, $transaction->toArray());
    }
}
