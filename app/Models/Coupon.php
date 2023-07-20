<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = ['percentage','stock', 'code', 'buyer_id', 'user_id', 'expiration'];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function buyer(){
        return $this->belongsTo(User::class);
    }
    public function order()
    {
        return $this->hasOne(Order::class, 'coupon_id');
    }

    // Relacion uno a muchos con la tabla user_coupons
    public function users()
    {
        return $this->belongsToMany('App\Models\User');
    }
}
