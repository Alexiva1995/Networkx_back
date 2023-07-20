<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Market;

class marketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = [
            ['product_name' => 'Cyborg 1', 'amount' => 50],
            ['product_name' => 'Cyborg 2', 'amount' => 20],
            ['product_name' => 'Cyborg 3', 'amount' => 20],
            ['product_name' => 'Cyborg 4', 'amount' => 20],
            ['product_name' => 'Cyborg 5', 'amount' => 20],
        ];

        foreach ($products as $product) {
            Market::create($product);
        }
    }
}
