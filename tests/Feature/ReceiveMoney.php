<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReceiveMoney extends TestCase
{
    /**
     * A basic feature test example.
     */

    public function test_user_can_receive_money_from_paytech_bank(): void
    {
        $payload = [
                "account_number"=>1234567890,
                "currency"=>"EGP",
                "transactions"=>[
                    "20250615156,50#2024225061459000001#note/debt payment march/internal_refrence/AE155933",
                    "20250615200,50#2025880761598000001#note/debt payment march/internal_refrence/AE155933"
                ]
        ];
        $headers = [
            'Content-Type' => 'application/json',
            'X-PayTech-Signature' => 'PAYTECH-SIGNTURE-101', // Replace with the actual signature if needed
        ];
        $response = $this->postJson('/api/webhook/paytech', $payload, $headers);
        $response->assertStatus(200);
    }

     public function test_user_can_receive_money_from_acme_bank(): void
    {
        $payload = [
               
                "account_number"=>1234567890,
                "currency"=>"EGP",
                "transactions"=>[
                    "156,50//2025061595000001//20250615",
                    "1006,50//2025067169000001//20250615"
                ]
            
        ];
        $headers = [
            'Content-Type' => 'application/json',
            'X-Acme-Signature' => 'ACME-SIGNTURE-99', // Replace with the actual signature if needed
        ];
        $response = $this->postJson('/api/webhook/acme', $payload, $headers);
        $response->assertStatus(200);
    }
}
