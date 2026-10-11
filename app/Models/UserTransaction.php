<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTransaction extends Model
{
    protected $fillable = [
        'user_id',
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


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
