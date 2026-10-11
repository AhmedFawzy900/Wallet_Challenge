<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankTransaction extends Model
{

    protected $fillable = [
        'reference',
        'transaction_type',
        'amount',
        'currency',
        'note',
        'internal_reference',
        'transaction_date',
        'from_bank',
        'acount_number',
        'status',
    ];

}
