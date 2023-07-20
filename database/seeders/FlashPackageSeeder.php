<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PackageMembership;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FlashPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $new_packages = [
            [
                'account' => 10000,
                'amount' => 200,
                'type' => 4,
                'target' => '8% phase 1',
                'min_trading_days' => 10,
                'daily_starting_drawdown' => '5%',
                'overall_drawdown' => '5%',
                'available_Leverage' => '1:100',
                'scability_plan' => null
            ],
            [
                'account' => 25000,
                'amount' => 300,
                'type' => 4,
                'target' => '8% phase 1',
                'min_trading_days' => 10,
                'daily_starting_drawdown' => '5%',
                'overall_drawdown' => '5%',
                'available_Leverage' => '1:100',
                'scability_plan' => null
            ],
            [
                'account' => 50000,
                'amount' => 400,
                'type' => 4,
                'target' => '8% phase 1',
                'min_trading_days' => 10,
                'daily_starting_drawdown' => '5%',
                'overall_drawdown' => '5%',
                'available_Leverage' => '1:100',
                'scability_plan' => null
            ],
            [
                'account' => 100000,
                'amount' => 600,
                'type' => 4,
                'target' => '8% phase 1',
                'min_trading_days' => 10,
                'daily_starting_drawdown' => '5%',
                'overall_drawdown' => '5%',
                'available_Leverage' => '1:100',
                'scability_plan' => null
            ],
        ];
        foreach($new_packages as $package){
            PackageMembership::create($package);
        }
    }
}
