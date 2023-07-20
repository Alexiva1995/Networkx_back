<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inversion extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'package_id',
        'orden_id',
        'user_id',
        'status',
        'amount',
        'type'
    ];

    const STATUS_APPROVED = 1;

    const TYPE_INITIAL_MATRIX = 0;

    public function order()
    {
        return $this->belongsTo(Order::class, 'orden_id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
