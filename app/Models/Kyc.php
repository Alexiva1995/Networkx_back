<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kyc extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'document',
        'file_front',
        'file_back',
        'status',
    ];

    public function getUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
