<?php
namespace App\Helpers;
class Helper {
    public static function returnResponse($message,$data=[],$code='200'){
        return response()->json([
            'message' => $message,
            'data' => $data
        ], $code);
    }
}
