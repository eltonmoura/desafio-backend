<?php

namespace App\Services\Contracts;

interface PaymentAuthorizationServiceInterface
{
    public function verify(string $document);
}
