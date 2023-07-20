<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageTicket extends Model
{
    protected $table = 'message_tickets';

    protected $fillable = [
        'support_id', 
        'ticket_id',
        'user_id',
        'type', 
        'message',
        'image'
    ];

    public function support()
    {
        return $this->belongsTo(User::class, 'support_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id', 'id');
    }
}
