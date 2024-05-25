<?php

namespace App\Enums;

enum AccountTypeEnum : string
{
    case Common = 'common';
    case Merchant = 'merchant';

    public static function accountTypesAllowedToTransferFunds(): array
    {
        return [
            self::Common->value,
        ];
    }

    public static function accountTypeCanTransferFunds(string $accountType): bool
    {
        return in_array($accountType, self::accountTypesAllowedToTransferFunds());
    }
}
