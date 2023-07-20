<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'amount',
        'phase1',
        'phase2',
        'complete',
        'status'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function user()
    {
        return $this->order->user;
    }

    public function formulary()
    {
        return $this->hasOne(Formulary::class, 'project_id', 'id');
    }

}
