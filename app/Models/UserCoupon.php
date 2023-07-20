<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCoupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'coupon_id',
        'used'
    ];

    protected $table = 'coupon_user';

    public function coupon(){
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
