<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Exceptions\BadRequestException;

class UserController extends Controller
{
    /**
     * The Model class associated with this Controller.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Get relationships when return objects
     *
     * @var array
     */
    protected $withRelationships = [
        'receipts',
        'payments',
    ];

    /**
     * Fields where the search will be made
     *
     * @var array
     */
    protected $searchFields = [
        'email',
        'name',
    ];

    protected function beforeStore(Request $request, Model $obj) : Model
    {
        if (! User::where('identity', $obj->identity)->get()->isEmpty()) {
            throw new BadRequestException('Já existe um usuário com este documento cadastrado');
        }

        if (! User::where('email', $obj->email)->get()->isEmpty()) {
            throw new BadRequestException('Já existe um usuário com este e-mail cadastrado');
        }

        return $this->encryptPassord($request, $obj);
    }

    protected function beforeUpdate(Request $request, Model $obj) : Model
    {
        return $this->encryptPassord($request, $obj);
    }

    private function encryptPassord(Request $request, Model $obj) : Model
    {
        if (!empty($request->input('password'))) {
            $obj->password = Hash::make($request->input('password'));
        }
        return $obj;
    }
}
