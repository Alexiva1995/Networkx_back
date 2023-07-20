<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\WalletComission;

class WalletComissionRepository
{
    private $model;

    public function __construct()
    {
        $this->model = new WalletComission();
    }

    public function save(WalletComission $walletComission)
    {
        return $walletComission->save();
    }

    public function getByReinversionLevel(User $user, int $level, int $amount, $status)
    {
        return $this->model->where('user_id', $user->id)->where('level', $level)->where('amount', $amount)->where('status', $status)->sum('amount');
    }
}
