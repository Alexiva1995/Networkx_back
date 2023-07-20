<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'tickets';

    public $timestamps = true;

    protected $fillable = [
        'user_id', 
        'status', 
        'subject', 
        'categories'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function messages()
    {
        return $this->hasMany(MessageTicket::class, 'ticket_id', 'id');
    }
    
}
