<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use App\Repositories\TransactionRepository;
use App\Models\Transaction;
use App\Models\User;
use App\Services\Contracts\EmailServiceInterface;
use App\Services\Contracts\PaymentAuthorizationServiceInterface;

class TransactionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $user = User::find(1); // Admin
        $this->actingAs($user);
    }

    public function testCreateValidate()
    {
        $this->json('POST', '/transaction', ['payer' => 2, 'payee' => 4])
            ->seeStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->seeJson(['value' => ['The value field is required.']]);

        $this->json('POST', '/transaction', ['value' => 'AAAA', 'payer' => 2, 'payee' => 4])
            ->seeStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->seeJson(['value' => ['The value must be a number.']]);

        $this->json('POST', '/transaction', ['value' => 10.00, 'payee' => 4])
            ->seeStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->seeJson(['payer' => ['The payer field is required.']]);

        $this->json('POST', '/transaction', ['value' => 10.00, 'payer' => 'aaa', 'payee' => 4])
            ->seeStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->seeJson(['payer' => ['The payer must be an integer.']]);

        $this->json('POST', '/transaction', ['value' => 10.00, 'payer' => 4])
            ->seeStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->seeJson(['payee' => ['The payee field is required.']]);

        $this->json('POST', '/transaction', ['value' => 10.00, 'payer' => 2, 'payee' => 'aaa'])
            ->seeStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->seeJson(['payee' => ['The payee must be an integer.']]);
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

    public function testPaymentAuthorizationServiceReponse()
    {
        $model = new Transaction();
        $paymentAuthorizationService = $this->getMockBuilder(PaymentAuthorizationServiceInterface::class)->getMock();
        $emailService = $this->getMockBuilder(EmailServiceInterface::class)->getMock();

        // denied
        $paymentAuthorizationService->method('verify')->willReturn(false);

        $transactionRepository = new TransactionRepository(
            $model,
            $paymentAuthorizationService,
            $emailService
        );

        $body = [
            'value' => 10.00,
            'payer' => 2,
            'payee' => 4,
        ];

        try {
            $return = $transactionRepository->create($body);
        } catch (Exception $e) {
            $this->assertEquals('Transação não autorizada', $e->getMessage());
        }
    }
}
