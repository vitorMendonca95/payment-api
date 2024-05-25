<?php

namespace App\Enums;

enum TransactionStatusEnum : string
{
    case Pending = 'Pending';
    case Completed = 'Completed';
    case Failed = 'Failed';
}
