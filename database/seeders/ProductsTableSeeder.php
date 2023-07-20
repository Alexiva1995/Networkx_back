<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::create([
            'name'=> 'prueba 1',
            'user_id'=> '1',
            'country'=> '0',
            'document_id'=> '1',
            'postal_code'=> '1',
            'phone_number' => '1',
            'status' => '1',
            'state' => 'united',
            'street' => '1',
            'department' => '1'
        ]);

        Product::create([
            'name'=> 'prueba 2',
            'user_id'=> '2',
            'document_id'=> '1',
            'country'=> '1',
            'postal_code'=> '1',
            'phone_number' => '1',
            'status' => '1',
            'state' => 'united',
            'street' => '1',
            'department' => '1'
        ]);

        Product::create([
            'name'=> 'prueba 3',
            'user_id'=> '2',
            'document_id'=> '1',
            'country'=> '1',
            'postal_code'=> '1',
            'phone_number' =>'1',
            'status' => '1',
            'state' => 'united',
            'street' => '1',
            'department' => '1'
        ]);
    }
}
