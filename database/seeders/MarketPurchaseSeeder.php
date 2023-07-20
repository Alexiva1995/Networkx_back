<?php

namespace Database\Seeders;

use App\Models\MarketPurchased;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MarketPurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'user_id' => 2,
                'cyborg_id' => 1,
                'order_id' => 1
            ],
            [
                'user_id' => 2,
                'cyborg_id' => 2,
                'order_id' => 2
            ],
            [
                'user_id' => 3,
                'cyborg_id' => 1,
                'order_id' => 3
            ],
            [
                'user_id' => 4,
                'cyborg_id' => 1,
                'order_id' => 3
            ],
            [
                'user_id' => 5,
                'cyborg_id' => 1,
                'order_id' => 3
            ],
            [
                'user_id' => 6,
                'cyborg_id' => 1,
                'order_id' => 3
            ],
            [
                'user_id' => 7,
                'cyborg_id' => 1,
                'order_id' => 3
            ],
            [
                'user_id' => 8,
                'cyborg_id' => 1,
                'order_id' => 3
            ],
            [
                'user_id' => 9,
                'cyborg_id' => 1,
                'order_id' => 3
            ],
            [
                'user_id' => 10,
                'cyborg_id' => 1,
                'order_id' => 3
            ],
            [
                'user_id' => 11,
                'cyborg_id' => 1,
                'order_id' => 3
            ],
            [
                'user_id' => 12,
                'cyborg_id' => 1,
                'order_id' => 3
            ],
            [
                'user_id' => 13,
                'cyborg_id' => 1,
                'order_id' => 3
            ],
            [
                'user_id' => 14,
                'cyborg_id' => 1,
                'order_id' => 3
            ],
            [
                'user_id' => 15,
                'cyborg_id' => 1,
                'order_id' => 3
            ],
            [
                'user_id' => 16,
                'cyborg_id' => 1,
                'order_id' => 3
            ],
        ];

        foreach ($data as $d) {
            MarketPurchased::create($d);
        }
    }
}
