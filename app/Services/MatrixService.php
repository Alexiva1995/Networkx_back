<?php

namespace App\Services;

use App\Models\MarketPurchased;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class MatrixService
{
    private $bonusService;

    public function __construct(BonusService $bonusService)
    {
        $this->bonusService = $bonusService;
    }
    // Reto a quien quiera intentar optimizar este codigo haciendo una funcion recursiva y dinamica, si lo logras, pide un aumento 
    public function levelOne()
    {
        // Obtenemos a todos los usuarios que no tengan nivel maximo y que no sean admin.
        $users = User::where('admin', '0')->with('marketPurchased.cyborg')->get();

        foreach ($users as $user) {
            foreach ($user->marketPurchased as $matrixPurchased) {
                $userRight = User::whereHas('marketPurchased', function ($q) use ($matrixPurchased) {
                    $q->where('type', '>=', $matrixPurchased->type);
                })->where('father_cyborg_purchased_id', $matrixPurchased->id)->where('binary_side', 'R')->where('buyer_id', $user->id)->where('status', User::ACTIVE)->first();
                $userLeft = User::whereHas('marketPurchased', function ($q) use ($matrixPurchased) {
                    $q->where('type', '>=', $matrixPurchased->type);
                })->where('father_cyborg_purchased_id', $matrixPurchased->id)->where('binary_side', 'L')->where('buyer_id', $user->id)->where('status', User::ACTIVE)->first();
                if ($userLeft && $userRight) {

                    if ($matrixPurchased->level < 1) {
                        $matrixPurchased->level = 1;
                        $matrixPurchased->save();
                    }

                    $this->levelTwo($matrixPurchased, $users, $userLeft, $userRight);
                }
            }
        }
    }

    public function levelTwo(MarketPurchased $matrixPurchased, $users, User $userLeft, User $userRight)
    {
        $user_left_left = User::whereHas('marketPurchased', function ($q) use ($matrixPurchased) {
            $q->where('type', '>=', $matrixPurchased->type);
        })->where('buyer_id', $userLeft->id)->where('binary_side', 'L')->where('status', User::ACTIVE)->first();
        $user_left_right = User::whereHas('marketPurchased', function ($q) use ($matrixPurchased) {
            $q->where('type', '>=', $matrixPurchased->type);
        })->where('buyer_id', $userLeft->id)->where('binary_side', 'R')->where('status', User::ACTIVE)->first();

        $user_right_left = User::whereHas('marketPurchased', function ($q) use ($matrixPurchased) {
            $q->where('type', '>=', $matrixPurchased->type);
        })->where('buyer_id', $userRight->id)->where('binary_side', 'L')->where('status', User::ACTIVE)->first();
        $user_right_right = User::whereHas('marketPurchased', function ($q) use ($matrixPurchased) {
            $q->where('type', '>=', $matrixPurchased->type);
        })->where('buyer_id', $userRight->id)->where('binary_side', 'R')->where('status', User::ACTIVE)->first();

        if ($user_left_left && $user_left_right && $user_right_left && $user_right_right) {

            if ($matrixPurchased->level < 2) {
                $matrixPurchased->level = 2;
                $matrixPurchased->save();
                $amount = $matrixPurchased->type == MarketPurchased::MATRIX_20 ? 50 : ($matrixPurchased->type == MarketPurchased::MATRIX_200 ? 500 : 5_000);
                $this->bonusService->subtract($amount, $matrixPurchased->user_id, $matrixPurchased->cyborg->id, $level = 2, $matrixPurchased->user, $matrixPurchased->type);
            }

            $this->levelThree($matrixPurchased, $users, $user_left_left, $user_left_right, $user_right_left, $user_right_right);
        }
    }

    public function levelThree(MarketPurchased $matrixPurchased, $users, User $user_left_left, User $user_left_right, User $user_right_left, User $user_right_right)
    {
        $user_left_left_left = User::whereHas('marketPurchased', function ($q) use ($matrixPurchased) {
            $q->where('type', '>=', $matrixPurchased->type);
        })->where('buyer_id', $user_left_left->id)->where('binary_side', 'L')->where('status', User::ACTIVE)->first();
        $user_left_left_right = User::whereHas('marketPurchased', function ($q) use ($matrixPurchased) {
            $q->where('type', '>=', $matrixPurchased->type);
        })->where('buyer_id', $user_left_left->id)->where('binary_side', 'R')->where('status', User::ACTIVE)->first();

        $user_left_right_left = User::whereHas('marketPurchased', function ($q) use ($matrixPurchased) {
            $q->where('type', '>=', $matrixPurchased->type);
        })->where('buyer_id', $user_left_right->id)->where('binary_side', 'L')->where('status', User::ACTIVE)->first();
        $user_left_right_right = User::whereHas('marketPurchased', function ($q) use ($matrixPurchased) {
            $q->where('type', '>=', $matrixPurchased->type);
        })->where('buyer_id', $user_left_right->id)->where('binary_side', 'R')->where('status', User::ACTIVE)->first();

        $user_right_left_left = User::whereHas('marketPurchased', function ($q) use ($matrixPurchased) {
            $q->where('type', '>=', $matrixPurchased->type);
        })->where('buyer_id', $user_right_left->id)->where('binary_side', 'L')->where('status', User::ACTIVE)->first();
        $user_right_left_right = User::whereHas('marketPurchased', function ($q) use ($matrixPurchased) {
            $q->where('type', '>=', $matrixPurchased->type);
        })->where('buyer_id', $user_right_left->id)->where('binary_side', 'R')->where('status', User::ACTIVE)->first();

        $user_right_right_left = User::whereHas('marketPurchased', function ($q) use ($matrixPurchased) {
            $q->where('type', '>=', $matrixPurchased->type);
        })->where('buyer_id', $user_right_right->id)->where('binary_side', 'L')->where('status', User::ACTIVE)->first();
        $user_right_right_right = User::whereHas('marketPurchased', function ($q) use ($matrixPurchased) {
            $q->where('type', '>=', $matrixPurchased->type);
        })->where('buyer_id', $user_right_right->id)->where('binary_side', 'R')->where('status', User::ACTIVE)->first();

        if ($user_left_left_left && $user_left_left_right && $user_left_right_left && $user_left_right_right && $user_right_left_left && $user_right_left_right && $user_right_right_left && $user_right_right_right) {

            if ($matrixPurchased->level < 3) {
                $matrixPurchased->level = 3;
                $matrixPurchased->save();
                $amount = $matrixPurchased->type == MarketPurchased::MATRIX_20 ? 100 : ($matrixPurchased->type == MarketPurchased::MATRIX_200 ? 1_000 : 10_000);
                $this->bonusService->subtract($amount, $matrixPurchased->user_id, $matrixPurchased->cyborg->id, $level = 3, $matrixPurchased->user, $matrixPurchased->type);
            }

            $this->levelFour(
                $matrixPurchased,
                $users,
                $user_left_left_left,
                $user_left_left_right,
                $user_left_right_left,
                $user_left_right_right,
                $user_right_left_left,
                $user_right_left_right,
                $user_right_right_left,
                $user_right_right_right
            );
        }
    }

    public function levelFour(MarketPurchased $matrixPurchased, $users, User $user_left_left_left, User $user_left_left_right, User $user_left_right_left, User $user_left_right_right, User $user_right_left_left, User $user_right_left_right, User $user_right_right_left, User $user_right_right_right)
    {
        $user_left_left_left_left = User::whereHas('marketPurchased', function ($q) use ($matrixPurchased) {
            $q->where('type', '>=', $matrixPurchased->type);
        })->where('buyer_id', $user_left_left_left->id)->where('binary_side', 'L')->where('status', User::ACTIVE)->first();
        $user_left_left_left_right = User::whereHas('marketPurchased', function ($q) use ($matrixPurchased) {
            $q->where('type', '>=', $matrixPurchased->type);
        })->where('buyer_id', $user_left_left_left->id)->where('binary_side', 'R')->where('status', User::ACTIVE)->first();

        $user_left_left_right_left = User::whereHas('marketPurchased', function ($q) use ($matrixPurchased) {
            $q->where('type', '>=', $matrixPurchased->type);
        })->where('buyer_id', $user_left_left_right->id)->where('binary_side', 'L')->where('status', User::ACTIVE)->first();
        $user_left_left_right_right = User::whereHas('marketPurchased', function ($q) use ($matrixPurchased) {
            $q->where('type', '>=', $matrixPurchased->type);
        })->where('buyer_id', $user_left_left_right->id)->where('binary_side', 'R')->where('status', User::ACTIVE)->first();

        $user_left_right_left_left = User::whereHas('marketPurchased', function ($q) use ($matrixPurchased) {
            $q->where('type', '>=', $matrixPurchased->type);
        })->where('buyer_id', $user_left_right_left->id)->where('binary_side', 'L')->where('status', User::ACTIVE)->first();
        $user_left_right_left_right = User::whereHas('marketPurchased', function ($q) use ($matrixPurchased) {
            $q->where('type', '>=', $matrixPurchased->type);
        })->where('buyer_id', $user_left_right_left->id)->where('binary_side', 'R')->where('status', User::ACTIVE)->first();

        $user_left_right_right_left = User::whereHas('marketPurchased', function ($q) use ($matrixPurchased) {
            $q->where('type', '>=', $matrixPurchased->type);
        })->where('buyer_id', $user_left_right_right->id)->where('binary_side', 'L')->where('status', User::ACTIVE)->first();
        $user_left_right_right_right = User::whereHas('marketPurchased', function ($q) use ($matrixPurchased) {
            $q->where('type', '>=', $matrixPurchased->type);
        })->where('buyer_id', $user_left_right_right->id)->where('binary_side', 'R')->where('status', User::ACTIVE)->first();

        $user_right_left_left_left = User::whereHas('marketPurchased', function ($q) use ($matrixPurchased) {
            $q->where('type', '>=', $matrixPurchased->type);
        })->where('buyer_id', $user_right_left_left->id)->where('binary_side', 'L')->where('status', User::ACTIVE)->first();
        $user_right_left_left_right = User::whereHas('marketPurchased', function ($q) use ($matrixPurchased) {
            $q->where('type', '>=', $matrixPurchased->type);
        })->where('buyer_id', $user_right_left_left->id)->where('binary_side', 'R')->where('status', User::ACTIVE)->first();

        $user_right_left_right_left = User::whereHas('marketPurchased', function ($q) use ($matrixPurchased) {
            $q->where('type', '>=', $matrixPurchased->type);
        })->where('buyer_id', $user_right_left_right->id)->where('binary_side', 'L')->where('status', User::ACTIVE)->first();
        $user_right_left_right_right = User::whereHas('marketPurchased', function ($q) use ($matrixPurchased) {
            $q->where('type', '>=', $matrixPurchased->type);
        })->where('buyer_id', $user_right_left_right->id)->where('binary_side', 'R')->where('status', User::ACTIVE)->first();

        $user_right_right_left_left = User::whereHas('marketPurchased', function ($q) use ($matrixPurchased) {
            $q->where('type', '>=', $matrixPurchased->type);
        })->where('buyer_id', $user_right_right_left->id)->where('binary_side', 'L')->where('status', User::ACTIVE)->first();
        $user_right_right_left_right = User::whereHas('marketPurchased', function ($q) use ($matrixPurchased) {
            $q->where('type', '>=', $matrixPurchased->type);
        })->where('buyer_id', $user_right_right_left->id)->where('binary_side', 'R')->where('status', User::ACTIVE)->first();

        $user_right_right_right_left = User::whereHas('marketPurchased', function ($q) use ($matrixPurchased) {
            $q->where('type', '>=', $matrixPurchased->type);
        })->where('buyer_id', $user_right_right_right->id)->where('binary_side', 'L')->where('status', User::ACTIVE)->first();
        $user_right_right_right_right = User::whereHas('marketPurchased', function ($q) use ($matrixPurchased) {
            $q->where('type', '>=', $matrixPurchased->type);
        })->where('buyer_id', $user_right_right_right->id)->where('binary_side', 'R')->where('status', User::ACTIVE)->first();

        if (
            $user_left_left_left_left && $user_left_left_left_right && $user_left_left_right_left && $user_left_left_right_right && $user_left_right_left_left && $user_left_right_left_right
            && $user_left_right_right_left && $user_left_right_right_right && $user_right_left_left_left && $user_right_left_left_right && $user_right_left_right_left
            && $user_right_left_right_right && $user_right_right_left_left && $user_right_right_left_right && $user_right_right_right_left && $user_right_right_right_right
        ) {

            if ($matrixPurchased->level < 4 && $matrixPurchased->type < MarketPurchased::MATRIX_2000) {
                $matrixPurchased->level = 0;
                $matrixPurchased->type = $matrixPurchased->type + 1;
                $matrixPurchased->save();
            } else {
                $matrixPurchased->level = 4;
                $matrixPurchased->save();
            }
        }
    }
}
