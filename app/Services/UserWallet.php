<?php
namespace App\Services;

use App\Helpers\Helper;
use App\Models\Wallet;

class UserWallet{
    public function addBalance($userId, $amount){
        $wallet = Wallet::where('user_id', $userId)->first();
        if ($wallet) {
            $wallet->balance += $amount;
            $wallet->save();
        }else{
            // If the wallet doesn't exist, create a new one
            Wallet::create([
                'user_id' => $userId,
                'balance' => $amount,
                'currency' => 'EGP', 
            ]);
        }
        return true;
    }

    public function deductBalance($userId, $amount){
        $wallet = Wallet::where('user_id', $userId)->first();
        if(!$wallet || $wallet->balance < $amount) {
         return false;
        }
        $wallet->balance -= $amount;
        $wallet->save();
        return true;
        
    }
}