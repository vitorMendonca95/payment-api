<?php

namespace App\Services\Account;

use App\Exceptions\Account\UserAlreadyHasAnAccountException;
use App\Models\Account;
use App\Repositories\Account\AccountRepository;
use Exception;

class AccountService
{
    public function __construct(protected AccountRepository $accountRepository)
    {
    }

    /**
     * @throws Exception
     */
    public function create(array $accountData): Account
    {
        if ($this->accountRepository->findByUserId($accountData['user_id'])) {
            throw new UserAlreadyHasAnAccountException();
        }

        return $this->accountRepository->create($accountData);
    }
}
