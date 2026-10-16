<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AcmeWebhookRequest;
use App\Http\Requests\PayTechWebhookRequest;
use App\Services\WebhookProcessor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
            'payload' => $request->all(),
        ]);
        $eventType = 'payment.succeeded';
        $source = 'paytech' ;
        
        // This ensures we respond quickly to the sender
         dispatch(function () use ($request, $eventType,$source) {
            $this->processor->process($eventType, $request->all(),$source);
        })->afterResponse();
        returnResponse('Webhook received',[],200);
    }
    public function handleAcme(AcmeWebhookRequest $request){
         // Log the incoming webhook for debugging
        Log::info('Webhook received', [
            'headers' => $request->headers->all(),
            'payload' => $request->validated(),
        ]);
        $eventType = 'payment.succeeded';
        $source = 'acme' ;

        dispatch(function () use ($request, $eventType,$source) {
            $this->processor->process($eventType, $request->validated(),$source);
        })->afterResponse();

        returnResponse('Webhook received',[],200);
    }

   
}
