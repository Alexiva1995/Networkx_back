<?php

namespace App\Repositories;

use App\Models\Ticket;

class TicketRepository 
{
    private $model;

    public function __construct()
    {
        $this->model = new Ticket();
    }

    public function getTicketsByQuantity(int $quantity)
    {
        return $this->model->orderBy('id', 'desc')->with('user')->get()->take($quantity);
    }

}