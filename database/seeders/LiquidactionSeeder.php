<?php

namespace Database\Seeders;

use App\Models\Liquidaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;

class LiquidactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Liquidaction::create([
            'id'=> 1,
            'user_id'=> 2,
            'reference'=> 'Test reference',
            'total'=> 1000.00,
            'monto_bruto'=> 20.00,
            'feed'=> 80.00,
            'hash' => '#16457',
            'wallet_used' => Crypt::encrypt('12345678'),
            'type' => 0,
            'status' => 1,
        ]);
        Liquidaction::create([
            'id'=> 2,
            'user_id'=> 2,
            'reference'=> 'Test reference',
            'total'=> 500.00,
            'monto_bruto'=> 60.00,
            'feed'=> 80.00,
            'hash' => '#16457',
            'wallet_used' => Crypt::encrypt('12345678'),
            'type' => 1,
            'status' => 1,
        ]);
        Liquidaction::create([
            'id'=> 3,
            'user_id'=> 2,
            'reference'=> 'Test reference',
            'total'=> 1200.00,
            'monto_bruto'=> 120.00,
            'feed'=> 80.00,
            'hash' => '#16457',
            'wallet_used' => Crypt::encrypt('12345678'),
            'type' => 0,
            'status' => 2,
        ]);
    }
}
