<?php

namespace App\Http\Controllers\API;

use App\Enums\TransactionsStatusEnum;
use App\Enums\TransactionTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserTransactionRequest;
use App\Models\UserTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;
use Spatie\ArrayToXml\ArrayToXml;


class UserTransactionsController extends Controller
{
    public function create(CreateUserTransactionRequest $request)
    {
        $user = Auth::user();
        if(!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
        if(!$user->account_number) {
            return response()->json(['error' => 'User does not have an account number'], 400);
        }

        $validatedData = $request->validated();

        $date = Carbon::now()->toDateTimeString();
        $reference = (string) Uuid::uuid4();

        $payload = array_merge($validatedData, [
            'transaction_reference' => $reference,
            'user_id' => $user->id,
            'transaction_date' => $date,
            'sender_account_number' => $user->account_number,
            'transaction_type' => TransactionTypeEnum::Depit,
            'status' => TransactionsStatusEnum::Pending,
        ]);

        //generate xml file from payload
        $xmlArray =[
            'TransferInfo'=>[
                'Reference'=>$reference,
                'Date'=>$payload['transaction_date'],
                'Amount'=>$payload['amount'],
                'Currency'=>$payload['currency'],
            ],
            'SenderInfo'=>[
                'AccountNumber'=>$payload['sender_account_number'],
            ],
            'ReceiverInfo'=>[
                'AccountNumber'=>$payload['reciever_account_number'],
                'BankCode'=>$payload['reciever_bank_code'], 
                'BeneficiaryName'=>$payload['beneficiary_name'],
            ],            
            
        ] ;
        if(isset($payload['payment_type']) && $payload['payment_type'] != 99) {
            $xmlArray['PaymentType'] = $payload['payment_type'];
        }
        if(isset($payload['charge_details']) && $payload['charge_details'] != 'SHA') {
            $xmlArray['ChargeDetails'] = $payload['charge_details'];
        }
        if(isset($payload['note']) && is_array($payload['note'])) {
            $xmlArray['Notes'] = [
                'Note' => $payload['note']
            ];
        }
        $xml = ArrayToXml::convert($xmlArray, [
            'rootElementName' => 'PaymentRequestMessage',
        ], true, 'UTF-8', '1.0');


        //store transaction in database
        $payload['note'] = isset($payload['note']) && is_array($payload['note']) ? json_encode($payload['note']) : null;
       
        try {
                 UserTransaction::create($payload);
                Log::info('User Transaction Created', [
                    'transaction' => $payload
                ]);
                $data=[
                    'transaction_reference' => $payload['transaction_reference'],
                    'xml' => $xml,
                ];
                returnResponse('Transaction created successfully',$data,201);
            } catch (\Throwable $th) {
                 Log::info('User Transaction Not created ', [
                    'error' => $th->getMessage(),
                    'transaction' => $payload
                ]);
                $data=[
                    'transaction_reference' => $payload['transaction_reference'],
                    'xml' => $xml,
                ];
                returnResponse($th->getMessage(),$data,401);
                
            }
        
    }
}
