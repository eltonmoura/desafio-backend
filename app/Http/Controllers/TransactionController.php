<?php

namespace App\Http\Controllers;

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
}
