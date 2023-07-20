<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagueloFacilTransaction extends Model
{
    use HasFactory;

    protected $table = 'paguelo_facil_transactions';

    protected $fillable = [
        'user_id',
        'order_id',
        'amount',
        'code',
        'status',
        'total_pay',
        'request_pay_amount',
        'operation_code',
        'display_num',
        'date',
        'operation_type',
        'return_url',
        'expiration_time'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
