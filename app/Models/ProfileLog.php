<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user',
        'subject'
    ];

    public function UserChange() {
    	return $this->belongsTo(User::class, 'user');
    }
}
