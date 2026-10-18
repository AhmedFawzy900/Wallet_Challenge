<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SendingMoney extends TestCase
{
    /**
     * A basic feature test example.
     */
        protected function getTokenForUser()
    {
        $user= [
        'email' => 'test@example.com',
        'password' => 'password'
        ];

        $response = $this->post('/api/login', $user);
        $responseData = $response->json();
        return $responseData['token'] ?? null;
    }

    public function test_user_can_send_money(): void
    {
        $payload=[
                "amount"=>10000,
                "currency"=>"EGP",
                "reciever_account_number"=>"14522562486658",
                "reciever_bank_code"=>"ATT1542",
                "beneficiary_name"=>"Ahmed Attia",
                "payment_type"=>"99",
                "charge_details"=>"RB"
        ];

        $response = $this->postJson('/api/user-transactions', $payload, [
            'Authorization' => 'Bearer ' . $this->getTokenForUser(),
        ]);
        $response->assertStatus(200);
    }
}
