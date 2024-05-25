<?php

namespace App\Repositories\Account;

use App\Models\Account;

class AccountRepository
{
    public function __construct(protected Account $account)
    {
    }

    public function create(array $accountData): Account
    {
        $this->account->fill($accountData);
        $this->account->save();

        return $this->account;
    }

    public function updateBalance(Account $account, float $newBalance): void
    {
        $account->balance = $newBalance;
        $account->save();
    }

    public function findByUserId(int $userId)
    {
        return Account::query()->where('user_id', $userId)->get()->first();
    }
}
