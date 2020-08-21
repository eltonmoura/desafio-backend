<?php

namespace App\Providers;

use App\Repositories\UserRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use App\Services\EmailService;
use App\Services\Contracts\EmailServiceInterface;
use App\Services\PaymentAuthorizationService;
use App\Services\Contracts\PaymentAuthorizationServiceInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);
        $this->app->bind(EmailServiceInterface::class, EmailService::class);
        $this->app->bind(PaymentAuthorizationServiceInterface::class, PaymentAuthorizationService::class);
    }
}
