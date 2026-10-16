<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AcmeWebhookRequest;
use App\Http\Requests\PayTechWebhookRequest;
use App\Services\WebhookProcessor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Helpers\Helper;
class WebhookController extends Controller
{
    private $processor;
    public function __construct(WebhookProcessor $processor) {
        $this->processor = $processor;
    }

    public function handlePayTech(PayTechWebhookRequest $request){
        
         // Log the incoming webhook for debugging
        Log::info('Webhook received', [
            'headers' => $request->headers->all(),
            'payload' => $request->validated(),
        ]);
        $eventType = 'payment.succeeded';
        $source = 'paytech' ;
        
        
        $this->processor->process($eventType, $request->validated(),$source);

        return Helper::returnResponse('Webhook received',[],200);
    }
    public function handleAcme(AcmeWebhookRequest $request){
         // Log the incoming webhook for debugging
        Log::info('Webhook received', [
            'headers' => $request->headers->all(),
            'payload' => $request->validated(),
        ]);
        $eventType = 'payment.succeeded';
        $source = 'acme' ;

        $this->processor->process($eventType, $request->validated(),$source);

        return Helper::returnResponse('Webhook received',[],200);
    }

   
}
