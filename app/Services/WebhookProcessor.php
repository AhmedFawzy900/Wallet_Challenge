<?php

namespace App\Services;

use App\Enums\TransactionsStatusEnum;
use App\Enums\TransactionTypeEnum;
use Illuminate\Support\Facades\Log;
use App\Events\PaymentReceived;
use App\Events\SubscriptionCancelled;
use App\Events\UserCreated;
use App\Models\BankTransaction;
use DateTime;

use function PHPSTORM_META\map;

class WebhookProcessor
{
    /**
     * Process a webhook event based on its type.
     * Each event type maps to a specific handler method.
     */
    public function process(string $eventType, array $payload,$source): void
    {
        Log::info("Processing webhook event: {$eventType}");

        // Map event types to handler methods
        // Add new events here as you need them
        match ($eventType) {
            'payment.succeeded' => $this->handlePaymentSucceeded($payload,$source),
            default => $this->handleUnknownEvent($eventType, $payload),
        };
    }

    public function handlePaymentSucceeded(array $payload,$source): void
    {
        // Extract relevant data from the payload
        $transactions = $payload['transactions'];
        $account_number = $payload['account_number'];
        $currency = $payload['currency'];
        $finalTransactionsArray = [];
        foreach($transactions as $trans){
            if ($source == 'paytech') {
                    $finalTransactionsArray = $this->extractTransactionDataForPayTech($trans);
            }elseif ($source == 'acme') {
                $finalTransactionsArray = $this->extractTransactionDataForAcme($trans);
            }
            if ($finalTransactionsArray["amount"] == '' || $finalTransactionsArray["refrence"] == '') {
                continue;
            }
            $transaction =[
                'reference'=>$finalTransactionsArray["refrence"],
                'transaction_type'=>TransactionTypeEnum::Credit,
                'amount'=>(float)$finalTransactionsArray["amount"],
                'currency'=>$currency,
                'note'=>$finalTransactionsArray["note"],
                'internal_reference'=>$finalTransactionsArray["internal_reference"],
                'transaction_date'=>$finalTransactionsArray["date"],
                'from_bank'=>$source,
                'acount_number'=>$account_number,
                'status'=>TransactionsStatusEnum::Success
            ];
            
            $isTransactionExsit = BankTransaction::where('reference',$finalTransactionsArray["refrence"])->exists();
            
            try{
                if ($isTransactionExsit) {
                    Log::info('Transaction Already exist', [
                        'transaction' => $transaction
                    ]);
                    continue;
                }
                BankTransaction::create($transaction);
                Log::info('Transaction Created', [
                    'transaction' => $transaction
                ]);
            } catch (\Throwable $th) {
                 Log::info('Bank Transaction Not created ', [
                    'error' => $th->getMessage(),
                    'transaction' => $transaction
                ]);
            }
          
        }

    }

    protected function handleUnknownEvent(string $eventType, array $payload): void
    {
        // Log unknown events so you can add handlers later if needed
        Log::info("Unhandled webhook event type: {$eventType}", [
            'payload' => $payload,
        ]);
    }

    protected function extractTransactionDataForPayTech($transaction){
       //20250615156,50#202506159000001#note/debt payment march/internal_reference/aaasdfdf
        $delimiter = "/";
        $transactionArray = explode($delimiter,$transaction);
       //20250615156,50#202506159000001#note,debt payment march,internal_reference,aaasdfdf
       $internal_reference = $transactionArray[3];
       $note = $transactionArray[1];
       $unparsedString = $transactionArray[0];
       $delimiter2 = "#";
       $unparsedStringArray = explode($delimiter2,$unparsedString);
       //20250615156,50 , 202506159000001 , note
       $refrence = $unparsedStringArray[1];
       $unparsedDate = substr($unparsedStringArray[0], 0, 8);
       $amount = substr($unparsedStringArray[0],8);
       $date = $this->prepareDateFormat($unparsedDate);
       return [
            "amount"=>$amount,
            "refrence"=>$refrence,
            "date"=>$date,
            "internal_reference"=>$internal_reference,
            "note"=>$note
       ];
    }
    protected function extractTransactionDataForAcme($transaction){
        //transaction format 156,50//202506159000001//20250615
        $delimiter = "//";
        $transactionArray = explode($delimiter,$transaction);
        //the result should be [156,50,202506159000001,20250615]
        $date = $this->prepareDateFormat($transactionArray[2]);
        return [
            "amount"=>$transactionArray[0],
            "refrence"=>$transactionArray[1],
            "date"=>$date,
            "internal_reference"=>'',
            "note"=>''
        ];
    }

    protected function prepareDateFormat(string $dateString){
        $dateYear = substr($dateString, -8,-4);
        $dateDay = substr($dateString, -2);
        $dateMonth = substr($dateString, -4,-2);
        $date = $dateYear . '-' . $dateMonth . '-' . $dateDay;
        return $date;
    }
}
