<?php

namespace Tests\Feature;

use Tests\TestCase;

class TransferMoneyTest extends TestCase
{
    public function testTransferMoneyWithFakePayer()
    {
        $data = [
            'value' => '1.00',
            'payer' => '0730a5e4-89a2-4dd9-8d31-ab6344b0ba60',
            'payee' => '0a35e361-873a-4075-8615-643fa1297fc0'
        ];

        $this->json('POST', 'api/v1/users/transfer', $data, ['Accept' => 'application/json'])
            ->assertStatus(500)
            ->assertJson([
                'message' => 'Payer does not exists',
            ]);
    }
}
