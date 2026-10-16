<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ValidateWebhookSignature
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next,string $provider): Response
    {
        
        $paytechSecret = config('services.webhook.paytech_secret');
        $acmeSecret = config('services.webhook.acme_secret');
        $isValid = match ($provider) {
            'paytech' => $this->validateSignature($request,'X-PayTech-Signature',$paytechSecret),
            'acme' => $this->validateSignature($request,'X-Acme-Signature',$acmeSecret),
            default => false,
        };

        if (!$isValid) {
            // Log the failed validation for security monitoring
            Log::warning('Webhook signature validation failed', [
                'provider' => $provider,
                'ip' => $request->ip(),
            ]);

            return response('Invalid signature', 401);
        }

        return $next($request);

    }

    public function validateSignature($request,$header_signature,$secret){

        $signature = $request->header($header_signature);
          if (!$signature) {
            return false;
        }
        return hash_equals($secret,$signature);    
    }

}
