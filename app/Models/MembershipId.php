<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipId extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',
        'status',
        'expiration_date'
    ];
}
