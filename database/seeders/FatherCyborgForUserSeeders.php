<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FatherCyborgForUserSeeders extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::find(3)->update(['father_cyborg_purchased_id' => 1]);
        $user = User::find(4)->update(['father_cyborg_purchased_id' => 1]);
        $user = User::find(5)->update(['father_cyborg_purchased_id' => 3]);
        $user = User::find(6)->update(['father_cyborg_purchased_id' => 3]);
        $user = User::find(7)->update(['father_cyborg_purchased_id' => 4]);
        $user = User::find(8)->update(['father_cyborg_purchased_id' => 4]);
        $user = User::find(9)->update(['father_cyborg_purchased_id' => 5]);
        $user = User::find(10)->update(['father_cyborg_purchased_id' => 5]);
        $user = User::find(11)->update(['father_cyborg_purchased_id' => 6]);
        $user = User::find(12)->update(['father_cyborg_purchased_id' => 6]);
        $user = User::find(13)->update(['father_cyborg_purchased_id' => 7]);
        $user = User::find(14)->update(['father_cyborg_purchased_id' => 7]);
        $user = User::find(15)->update(['father_cyborg_purchased_id' => 8]);
        $user = User::find(16)->update(['father_cyborg_purchased_id' => 8]);
        $user = User::find(17)->update(['father_cyborg_purchased_id' => 9]);
        $user = User::find(18)->update(['father_cyborg_purchased_id' => 9]);
        $user = User::find(19)->update(['father_cyborg_purchased_id' => 10]);
        $user = User::find(20)->update(['father_cyborg_purchased_id' => 10]);
        $user = User::find(21)->update(['father_cyborg_purchased_id' => 11]);
        $user = User::find(22)->update(['father_cyborg_purchased_id' => 11]);
        $user = User::find(23)->update(['father_cyborg_purchased_id' => 12]);
        $user = User::find(24)->update(['father_cyborg_purchased_id' => 12]);
        $user = User::find(25)->update(['father_cyborg_purchased_id' => 13]);
        $user = User::find(26)->update(['father_cyborg_purchased_id' => 13]);
        $user = User::find(27)->update(['father_cyborg_purchased_id' => 14]);
        $user = User::find(28)->update(['father_cyborg_purchased_id' => 14]);
        $user = User::find(29)->update(['father_cyborg_purchased_id' => 15]);
        $user = User::find(30)->update(['father_cyborg_purchased_id' => 15]);
        $user = User::find(31)->update(['father_cyborg_purchased_id' => 16]);
        $user = User::find(32)->update(['father_cyborg_purchased_id' => 16]);
        $user = User::find(33)->update(['father_cyborg_purchased_id' => 2]);
        $user = User::find(34)->update(['father_cyborg_purchased_id' => 2]);
    }
}
