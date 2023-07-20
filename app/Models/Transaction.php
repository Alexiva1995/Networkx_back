<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $table = 'transactions';
    protected $fillable = [
        'id',
        'liquidation_id',
        'wallets_commissions_id',
        'amount',
        'amount_retired',
        'amount_available',
        'utilies_id',
    ];
}
