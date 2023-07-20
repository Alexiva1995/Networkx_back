<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageMembership extends Model
{
    use HasFactory;

    const FTY_EVALUATION = 1;
    const FTY_FAST = 2;
    const FTY_ACCELERATED = 3;
    const FTY_FLASH = 4;

    protected $fillable = [
        'account',
        'amount',
        'target',
        'type',
        'min_trading_days',
        'daily_starting_drawdown',
        'overall_drawdown',
        'available_Leverage',
        'scability_plan'
    ];

    public function getTypeName() {
        $array = [ 1 => 'Evaluation', 2 => 'Fast', 3 => 'Accelerated', 4 => 'Flash'];
        return $array[$this->type];
    }
}
