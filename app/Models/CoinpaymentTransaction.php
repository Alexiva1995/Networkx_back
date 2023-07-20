<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CoinpaymentTransaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'buyer_name',
        'buyer_email',
        'address',
        'amount_total_fiat',
        'amount',
        'amountf',
        'coin',
        'time_expires',
        'currency_code',
        'confirms_needed',
        'payment_address',
        'qrcode_url',
        'received',
        'receivedf',
        'recv_confirms',
        'status',
        'status_text',
        'status_url',
        'checkout_url',
        'redirect_url',
        'cancel_url',
        'timeout',
        'txn_id',
        'type',
        'payload'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'amount' => 'double',
        'amountf' => 'double',
        'received' => 'double',
        'receivedf' => 'double',
        'payload' => 'array'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'deleted_at'
    ];

    protected static function boot() {
        parent::boot();

        self::creating(function($model) {
            $model->uuid = (String) Str::uuid();
        });

        self::deleting(function($model) {
            if(! is_null($model->txn_id)) {
                throw new Exception("This transaction cannot be deleted");
            }
        });
    }

}
