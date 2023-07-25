<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\User;

class OrderRepository 
{
    private $model;

    public function __construct()
    {
        $this->model = new Order();
    }

    public function getOrdersByQuantity(int $quantity)
    {
        return $this->model->orderBy('id', 'desc')->with('user', 'project', 'packageMembership')->get()->take($quantity);
    }
    public function getOrders()
    {
        return $this->model->orderBy('id', 'desc')->with('user', 'project', 'packageMembership')->get();
    }

    public function OrdersPaid()
    {
        return $this->model->where('status', 1)->with('packageMembership')->get();
    }

    public function getOrdersByUserId(int $id)
    {
        return $this->model->whereUserId($id)->get();
    }

}