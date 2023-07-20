<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Order::create([
            'amount' => '200',
            'hash' => '1234',
            'user_id'=> '2',
            'status' => '1',
            'cyborg_id' => '1'
        ]);
        Order::create([
            'amount' => '2000',
            'hash' => '1234',
            'user_id'=> '2',
            'status' => '1',
            'cyborg_id' => '2'
        ]);
        Order::create([
            'amount' => '200',
            'hash' => '1234',
            'user_id'=> '3',
            'status' => '1',
            'cyborg_id' => '1'
        ]);
        // Order::create([
        //     'amount' => '2000',
        //     'hash' => '1234',
        //     'user_id'=> '3',
        //     'status' => '1',
        // ]);

        // Order::create([
        //     'amount' => '2000',
        //     'hash' => '1234',
        //     'type' => '1',
        //     'user_id'=> '3',
        //     'status' => '1',
        // ]);

        // Order::create([
        //     'amount' => '3000',
        //     'hash' => '1234',
        //     'type' => '1',
        //     'user_id'=> '4',
        //     'status' => '2',
        // ]);

        // Order::create([
        //     'amount' => '4000',
        //     'hash' => '1234',
        //     'type' => '2',
        //     'user_id'=> '5',
        //     'status' => '3',
        // ]);
    }
}
