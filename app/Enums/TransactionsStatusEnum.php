<?php

namespace App\Enums;

enum TransactionsStatusEnum:string
{
     case Pending = 'pending';
    case Success = 'success';
    case Failed = 'inactive';
}
