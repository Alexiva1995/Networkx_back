<?php

namespace Database\Seeders;

use App\Models\Kyc;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KycSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Kyc::create([
            'document' => '1',
            'file_front' => '2.1676052844.front.jpg',
            'file_back' => '2.1676052844',
            'status' => '1',
            'user_id'=> '3',
        ]);

        Kyc::create([
            'document' => '2',
            'file_front' => '2.1676052844.front.jpg',
            'file_back' => '2.1676052844',
            'status' => '0',
            'user_id'=> '4',
        ]);

        Kyc::create([
            'document' => '0',
            'file_front' => '2.1676052844.front.jpg',
            'file_back' => '2.1676052844',
            'status' => '1',
            'user_id'=> '5',
        ]);
    }
}
