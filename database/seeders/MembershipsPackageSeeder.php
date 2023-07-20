<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\PackageMembership;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MembershipsPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //EVALUATION
        PackageMembership::create([
            'account' => 10000,
            'scability_plan' => null,
            'amount' => 80,
            'type' => 1,
            'target' => '8% phase 1 / 5% phase 2',
            'min_trading_days' => '5 Days',
            'daily_starting_drawdown' => '5%',
            'overall_drawdown' => '10%',
            'available_Leverage' => '1:100',
        ]);

        PackageMembership::create([
            'account' => 20000,
            'scability_plan' => null,
            'amount' => 130,
            'type' => 1,
            'target' => '8% phase 1 / 5% phase 2',
            'min_trading_days' => '5 Days',
            'daily_starting_drawdown' => '5%',
            'overall_drawdown' => '10%',
            'available_Leverage' => '1:100',
        ]);

        PackageMembership::create([
            'account' => 50000,
            'scability_plan' => null,
            'amount' => 290,
            'type' => 1,
            'target' => '8% phase 1 / 5% phase 2',
            'min_trading_days' => '5 Days',
            'daily_starting_drawdown' => '5%',
            'overall_drawdown' => '10%',
            'available_Leverage' => '1:100',
        ]);

        PackageMembership::create([
            'account' => 100000,
            'scability_plan' => null,
            'amount' => 490,
            'type' => 1,
            'target' => '8% phase 1 / 5% phase 2',
            'min_trading_days' => '5 Days',
            'daily_starting_drawdown' => '5%',
            'overall_drawdown' => '10%',
            'available_Leverage' => '1:100',
        ]);

        // FAST
        PackageMembership::create([
            'account' => 100000,
            'scability_plan' => 250000,
            'amount' => 600,
            'type' => 2,
            'target' => '7% Phase 1',
            'min_trading_days' => null,
            'daily_starting_drawdown' => '5%',
            'overall_drawdown' => '8%',
            'available_Leverage' => '1:100',
        ]);

        PackageMembership::create([
            'account' => 250000,
            'scability_plan' => 500000,
            'amount' => 1400,
            'type' => 2,
            'target' => '7% Phase 1',
            'min_trading_days' => null,
            'daily_starting_drawdown' => '5%',
            'overall_drawdown' => '8%',
            'available_Leverage' => '1:100',
        ]);

        PackageMembership::create([
            'account' => 500000,
            'scability_plan' => 1500000,
            'amount' => 2500,
            'type' => 2,
            'target' => '7% Phase 1',
            'min_trading_days' => null,
            'daily_starting_drawdown' => '5%',
            'overall_drawdown' => '8%',
            'available_Leverage' => '1:100',
        ]);

        // ACCELERATED
        PackageMembership::create([
            'account' => 10000,
            'scability_plan' => 500000,
            'amount' => 450,
            'type' => 3,
            'target' => '10%',
            'min_trading_days' => null,
            'daily_starting_drawdown' => 'No Daily Drawdown Limits',
            'overall_drawdown' => '5%',
            'available_Leverage' => '1:100',
        ]);

        PackageMembership::create([
            'account' => 20000,
            'scability_plan' => 1000000,
            'amount' => 950,
            'type' => 3,
            'target' => '10%',
            'min_trading_days' => null,
            'daily_starting_drawdown' => 'No Daily Drawdown Limits',
            'overall_drawdown' => '5%',
            'available_Leverage' => '1:100',
        ]);

        PackageMembership::create([
            'account' => 50000,
            'scability_plan' => 1500000,
            'amount' => 2300,
            'type' => 3,
            'target' => '10%',
            'min_trading_days' => null,
            'daily_starting_drawdown' => 'No Daily Drawdown Limits',
            'overall_drawdown' => '5%',
            'available_Leverage' => '1:100',
        ]);
    }
}
