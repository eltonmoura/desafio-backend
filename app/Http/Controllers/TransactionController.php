<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Transaction;
use App\Models\User;
use App\Exceptions\BadRequestException;
use App\Services\PaymentAuthorizationService;
use App\Services\EmailService;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * The Model class associated with this Controller.
     *
     * @var string
     */
    protected $model = Transaction::class;

    /**
     * Get relationships when return objects
     *
     * @var array
     */
    protected $withRelationships = [];

    /**
     * Fields where the search will be made
     *
     * @var array
     */
    protected $searchFields = [];

    public function __construct(
        PaymentAuthorizationService $paymentAuthorizationService,
        EmailService $EmailService
    ) {
        $this->paymentAuthorizationService = $paymentAuthorizationService;
        $this->EmailService = $EmailService;
    }

    /**
     * Override Controller::store()
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        $transaction = Transaction::create($request->all());

        if ($transaction->userPayer->type == User::TYPE_COMPANY) {
            throw new BadRequestException('Lojistas não podem fazer tranferências');
        }

        if (!$this->paymentAuthorizationService->verify($transaction->userPayer->identity)) {
            throw new BadRequestException('Transação não autorizada');
        }

        $this->EmailService->sendConfimation($transaction);

        $transaction->save();

        DB::commit();

        return response()->json('OK', Response::HTTP_CREATED);
    }
}
