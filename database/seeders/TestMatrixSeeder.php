<?php

namespace Database\Seeders;

use App\Models\Inversion;
use App\Models\MarketPurchased;
use App\Models\Order;
use App\Models\Package;
use App\Models\Product;
use App\Models\ReferalLink;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Services\BonusService;
use Illuminate\Support\Str;

class TestMatrixSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Package::create([
            'package' => 'Test',
            'description' => 'Hi i am a description',
            'gain' => 50,
            'amount' => 50,
            'type' => 0,
            'level' => 1
        ]);

        // admin
        $bonusService = new BonusService;

        User::create([
            'name'=> 'user',
            'last_name'=> 'admin',
            'email'=> 'admin@b2b.com',
            'user_name'=> 'admin',
            'admin' => '1',
            // 'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
            'status' => '1',
            'binary_id' => 0,
            'binary_side' => 'L'
        ]);

        $user = User::create([
            'name'=> 'user',
            'last_name'=> 1,
            'email'=> "user1@b2b.com",
            'user_name'=> 'user1',
            'admin'=> '0',
            // 'password' => Hash::make('123456789'),
            'email_verified_at' => now(),
            'status' => '1',
            'buyer_id' => 1,
            'binary_id' => 1,
            'binary_side' => 'L'
        ]);

        $order = Order::create([
            'user_id' => $user->id,
            'amount' => 50,
            'hash' => null,
            'status' => '1',
            'cyborg_id' => '1',
            'membership_packages_id' => 1
        ]);

        Inversion::create([
            'package_id' => 1,
            'orden_id' => $order->id,
            'user_id' => $user->id,
            'status' => Inversion::STATUS_APPROVED,
            'amount' => 50,
            'type' => Inversion::TYPE_INITIAL_MATRIX
        ]);

        $marketPurchased = MarketPurchased::create([
            'user_id' => $user->id,
            'cyborg_id' => 1,
            'order_id' => $order->id
        ]);

        ReferalLink::create([
            'user_id' => $user->id,
            'link_code' => Str::random(6),
            'cyborg_id' => 1,
            'right' => 1,
            'left' => 1,
            'status' => ReferalLink::STATUS_INACTIVE,
        ]);

        ReferalLink::create([
            'user_id' => $user->id,
            'link_code' => Str::random(6),
            'cyborg_id' => 2,
            'right' => 0,
            'left' => 0,
            'status' => ReferalLink::STATUS_ACTIVE,
        ]);

        $bonusService->generateBonus(20, $user, $order, $buyer = $user, $level = 2, $user->id);

        for($i = 3; $i < 33; $i++) {
            
            $father = User::where('id', round($i / 2))->with('marketPurchased')->first();

            $user = User::create([
                'name'=> 'user',
                'last_name'=> $i,
                'email'=> "user{$i}@b2b.com",
                'user_name'=> 'user'.$i,
                'admin'=> '0',
                // 'password' => Hash::make('123456789'),
                'email_verified_at' => now(),
                'status' => '1',
                'buyer_id' => round($i / 2),
                'binary_id' => round($i / 2),
                'binary_side' => ($i % 2) == 0 ? 'R' : 'L',
                'father_cyborg_purchased_id' => $father->marketPurchased->first()->id
            ]);

            Product::create([
                'name'=> "prueba {$i}",
                'user_id'=> $user->id,
                'country'=> '0',
                'document_id'=> '1',
                'postal_code'=> '1',
                'phone_number' => rand(10000000,99999999),
                'status' => '1',
                'state' => 'united',
                'street' => '1',
                'department' => $i
            ]);
    
            $order = Order::create([
                'user_id' => $user->id,
                'amount' => 50,
                'hash' => null,
                'status' => '1',
                'cyborg_id' => '1',
                'membership_packages_id' => 1
            ]);
    
            $marketPurchased = MarketPurchased::create([
                'user_id' => $user->id,
                'cyborg_id' => 1,
                'order_id' => $order->id
            ]);

            $data = [
                'user_id' => $user->id,
                'link_code' => Str::random(6),
                'cyborg_id' => 1,
                'right' => 1,
                'left' => 1,
                'status' => ReferalLink::STATUS_INACTIVE,
            ];

            if($i > 16) {
                $data['right'] = 0;
                $data['left'] = 0;
                $data['status'] = ReferalLink::STATUS_ACTIVE;
            }

            ReferalLink::create($data);

            $bonusService->generateBonus(20,$user, $order, $buyer = $user, $level = 2, $user->id);
        }
    }
}
