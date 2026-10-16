<?php
if(!function_exists('returnResponse')){
    function returnResponse($message,$data=[],$code='200'){
        return response()->json([
            'message' => $message,
            'data' => $data
        ], $code);
    }
}
