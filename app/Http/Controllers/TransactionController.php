<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Repositories\Contracts\TransactionRepositoryInterface;

class TransactionController extends Controller
{
    public function __construct(TransactionRepositoryInterface $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->transactionRepository->create($request->all());

        return response()->json('OK', Response::HTTP_CREATED);
    }
}
