<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'value',
        'payer',
        'payee',
    ];

    public function userPayer()
    {
        return $this->belongsTo(User::class, 'payer');
    }

    public function userPayee()
    {
        return $this->belongsTo(User::class, 'payee');
    }

    public function isValid()
    {
        if ($this->userPayer->type == User::TYPE_COMPANY) {
            return false;
        }
        return true;
    }
}
