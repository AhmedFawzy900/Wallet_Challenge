<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTransaction extends Model
{
    protected $table = 'users_transactions';
    protected $fillable = [
        'user_id',
        'transaction_reference',
        'transaction_date',
        'currency',
        'transaction_type',
        'note',
        'sender_account_number',
        'reciever_account_number',
        'reciever_bank_code',
        'beneficiary_name',
        'payment_type',
        'charge_details',
        'status',
        'amount',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
}
