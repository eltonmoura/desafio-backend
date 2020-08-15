<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction;

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

    protected function beforeStore(Request $request, Model $obj) : Model
    {
        if (!$obj->isValid()) {
            throw new \Exception('Invalid Transaction', Response::HTTP_BAD_REQUEST);
        }
        return $obj;
    }
}
