<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    use HasFactory;

    

    protected $fillable = [
        'membership_id',
        'order_id',
        'status'
    ];
    public function liquidactions()
    {
        return $this->hasMany(Liquidaction::class);
    }
}
