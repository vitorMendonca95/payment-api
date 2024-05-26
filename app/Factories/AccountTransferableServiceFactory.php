<?php

namespace App\Factories;

use App\Enums\AccountTypeEnum;
use App\Exceptions\Account\AccountTypeNotFoundException;
use App\Interfaces\AccountTransferableServiceInterface;
use App\Services\Account\AccountCommonService;
use Exception;

class AccountTransferableServiceFactory
{
    /**
     * @throws Exception
     */
    public function getAccountServiceInstance(string $type): AccountTransferableServiceInterface
    {
        return match ($type) {
            AccountTypeEnum::Common->value => app(AccountCommonService::class),
            default => throw new AccountTypeNotFoundException($type),
        };
    }
}
