<?php
namespace App\Helpers;
class Helper {
    public static function returnResponse($message,$data=[],$code='200'){
        return response()->json([
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public static function TransactionPregMatch($transaction,$source){
        
        if ($source == 'paytech') {
            $pattern = '/^\d+,\d+#\d+#[a-zA-Z]+\/[a-zA-z0-9\s]+\/[a-z-A-Z_]+\/[A-Za-z0-9]+/';
        }elseif ($source == 'acme') {
            $pattern = '#^\d+,\d+//\d+//\d+?$#';
        }
       
        return preg_match($pattern, $transaction);
    }
}
