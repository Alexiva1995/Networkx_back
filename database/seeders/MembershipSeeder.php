<?php

namespace Database\Seeders;

use App\Models\Membership;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MembershipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Membership::create([
            'id'=> 1,
            'order_id'=> 1,
            'membership_id'=> 1,
            'status' => 0,
        ]);
        Membership::create([
            'id'=> 2,
            'order_id'=> 2,
            'membership_id'=> 4,
            'status' => 0,
        ]);
        Membership::create([
            'id'=> 3,
            'order_id'=> 3,
            'membership_id'=> 8,
            'status' => 0,
        ]);
    }
}
