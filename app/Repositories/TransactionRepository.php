<?php

namespace App\Repositories;

use App\Repositories\Contracts\TransactionRepositoryInterface;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Exceptions\BadRequestException;
use App\Services\Contracts\PaymentAuthorizationServiceInterface;
use App\Services\Contracts\EmailServiceInterface;

class TransactionRepository extends AbstractRepository implements TransactionRepositoryInterface
{
    protected $model;

    public function __construct(
        Transaction $model,
        PaymentAuthorizationServiceInterface $paymentAuthorizationService,
        EmailServiceInterface $emailService
    ) {
        $this->model = $model;
        $this->paymentAuthorizationService = $paymentAuthorizationService;
        $this->emailService = $emailService;
    }

    public function create(array $data)
    {
        DB::beginTransaction();

        $transaction = parent::create($data);

        if ($transaction->userPayer->type == User::TYPE_COMPANY) {
            throw new BadRequestException('Lojistas não podem fazer tranferências');
        }

        if (!$this->paymentAuthorizationService->verify($transaction->userPayer->identity)) {
            throw new BadRequestException('Transação não autorizada');
        }

        $this->emailService->sendConfimation($transaction);

        DB::commit();
        return $transaction;
    }
}
