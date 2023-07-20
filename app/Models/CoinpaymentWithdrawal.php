<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CoinpaymentWithdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'send_address',
        'time_created',
        'amount',
        'amountf',
        'amounti',
        'coin',
        'send_txid',
        'status',
        'status_text',
        'liquidation_id',
        'tx_id',
    ];

    protected $casts = [
        'amount' => 'double',
        'amountf' => 'double',
    ];

    protected static function boot() {
        parent::boot();

        self::creating(function($model) {
            $model->uuid = (String) Str::uuid();
        });

    }

    public function liquidation()
    {
        return $this->belongsTo(Liquidation::class);
    }
}
