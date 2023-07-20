<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawalError extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'processId',
        'name',
        'wallet',
        'value',
        'secret',
        'error_code',
        'error_message',
    ];
}
