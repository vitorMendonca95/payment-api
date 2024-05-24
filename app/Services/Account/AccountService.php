<?php

namespace App\Services\Account;

use App\Models\Account;
use App\Repositories\Account\AccountRepository;

class AccountService
{
    public function __construct(protected AccountRepository $accountRepository)
    {
    }

    public function create(array $accountData): Account
    {
        return $this->accountRepository->create($accountData);
    }
}
