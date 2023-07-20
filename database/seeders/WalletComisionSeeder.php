<?php

namespace Database\Seeders;

use App\Models\WalletComission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WalletComisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WalletComission::create([
            'buyer_id' => '1',
            'order_id' => '1',
            'level' =>'1',
            'description' => 'pruebas',
            'amount' => '1000',
            'amount_available' => '1000',
            'type' => '0',
            'user_id'=> '2',
            'status' => '0',
            'type_matrix' => 0,
        ]);

        WalletComission::create([
            'buyer_id' => '2',
            'order_id' => '2',
            'level' =>'1',
            'description' => 'pruebas',
            'amount' => '2000',
            'amount_available' => '2000',
            'type' => '0',
            'user_id'=> '3',
            'status' => '0',
            'type_matrix' => 0,
        ]);

        WalletComission::create([
            'buyer_id' => '3',
            'order_id' => '3',
            'level' =>'1',
            'description' => 'pruebas',
            'amount' => '3000',
            'amount_available' => '3000',
            'type' => '0',
            'user_id'=> '4',
            'status' => '0',
            'type_matrix' => 0,
        ]);

        WalletComission::create([
            'buyer_id' => '2',
            'order_id' => '4',
            'level' =>'1',
            'description' => 'pruebas',
            'amount' => '200',
            'amount_available' => '200',
            'type' => '3',
            'user_id'=> '4',
            'status' => '0',
            'type_matrix' => 0,
        ]);

        WalletComission::create([
            'buyer_id' => '2',
            'order_id' => '5',
            'level' =>'1',
            'description' => 'pruebas',
            'amount' => '3000',
            'amount_available' => '3000',
            'type' => '3',
            'user_id'=> '4',
            'status' => '0',
            'type_matrix' => 0,
        ]);

        WalletComission::create([
            'buyer_id' => '2',
            'order_id' => '6',
            'level' =>'1',
            'description' => 'pruebas',
            'amount' => '1000',
            'amount_available' => '1000',
            'type' => '3',
            'user_id'=> '4',
            'status' => '0',
            'type_matrix' => 0,
        ]);
    }
}
