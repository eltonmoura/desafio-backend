<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\TransactionRepositoryInterface;

class TransactionController extends Controller
{
    public function __construct(TransactionRepositoryInterface $transactionRepository)
    {
        $this->repository = $transactionRepository;
    }
}
