<?php

namespace App\Enums;

enum TransactionType: string
{
    case DEPOSIT = 'deposit';
    case WITHDRAW = 'withdraw';
    case SEND = 'send';
    case RECEIVE = 'receive';
    case ADD = 'add';
    case OTHERS = 'others';
}
