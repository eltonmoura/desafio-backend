<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

use App\Models\User;

class TransactionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $user = User::find(1); // Admin
        $this->actingAs($user);
    }

    public function testShouldTransferBetweenUsers()
    {
        $body = [
            'value' => 10.00,
            'payer' => 2,
            'payee' => 4,
        ];

        $this->json('POST', '/transaction', $body)
            ->seeStatusCode(Response::HTTP_CREATED)
            ->seeJson(['ok']);
    }

    public function testShouldNotTransferByCompany()
    {
        $body = [
            'value' => 10.00,
            'payer' => 4,
            'payee' => 2,
        ];

        $this->post('/transaction', $body)
            ->seeStatusCode(Response::HTTP_BAD_REQUEST)
            ->seeJson(['error' => 'Lojistas não podem fazer tranferências']);
    }
}
