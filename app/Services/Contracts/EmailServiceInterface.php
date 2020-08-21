<?php

namespace App\Services\Contracts;

use App\Models\Transaction;

interface EmailServiceInterface
{
    public function sendConfimation(Transaction $transaction);
}
