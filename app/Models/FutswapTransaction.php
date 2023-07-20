<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FutswapTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'billId',
        'status',
        'token',
        'coinName',
        'address',
        'value',
        'coinSymbol',
        'usdValue',
        'expires',
        'time',
        'paymentUrl',
        'defaultUnitValue',
        'totalPaid',
        'trm',
        'recoveryFeeTransaction',
        'transactionToMasterWallet',
        'internalFee',
        'index',
        'hash',
        'contractAddress',
        'blockchainSymbol',
        'secret'
    ];

    public function orderPurchase()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function dataTransaction()
    {
        return $this->hasMany(PartyalTransactions::class, 'futswap_transactions_id');
    }

    public function storeFutswapTransaction($data)
    {
        $futswap_transaction =  $this::create($data);
        return $futswap_transaction;
    }
}
