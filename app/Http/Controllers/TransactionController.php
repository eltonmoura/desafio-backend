<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Transaction;
use App\Models\User;
use App\Exceptions\BadRequestException;

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

    /**
     * Override Controller::store()
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $transaction = Transaction::create($request->all());

        if ($transaction->userPayer->type == User::TYPE_COMPANY) {
            throw new BadRequestException('Lojistas não podem enviar dinheiro');
        }

        $transaction->save();
        return response()->json('OK', Response::HTTP_CREATED);
    }
}
