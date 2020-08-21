<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\TransactionRepositoryInterface;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct(TransactionRepositoryInterface $transactionRepository)
    {
        $this->repository = $transactionRepository;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'value' => 'required|numeric',
            'payer' => 'required|integer',
            'payee' => 'required|integer',
        ]);

        return parent::store($request);
    }
}
