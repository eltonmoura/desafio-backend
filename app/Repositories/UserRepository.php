<?php

namespace App\Repositories;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\BadRequestException;

class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function create(array $data)
    {
        $this->checkEmailAlreadyExists($data['email']);
        $this->checkIdentityAlreadyExists($data['identity']);

        $data = $this->encryptPassword($data);
        return $this->model->create($data);
    }

    public function update(array $data, $id)
    {
        $data = $this->encryptPassword($data);
        return $this->model->create($data);
    }

    private function encryptPassword($data)
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        return $data;
    }

    private function checkEmailAlreadyExists($email)
    {
        if (! User::where('email', $email)->get()->isEmpty()) {
            throw new BadRequestException('Já existe um usuário com este e-mail cadastrado');
        }
    }

    private function checkIdentityAlreadyExists($identity)
    {
        if (! User::where('identity', $identity)->get()->isEmpty()) {
            throw new BadRequestException('Já existe um usuário com este documento cadastrado');
        }
    }
}
