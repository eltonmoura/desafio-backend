<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\UserRepositoryInterface;

class UserController extends Controller
{
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->repository = $userRepository;
    }
}
