<?php

namespace App\Http\Controllers;

use App\Http\Requests\CouponStoreRequest;
use App\Models\Coupon;
use App\Models\UserCoupon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use SebastianBergmann\Type\TrueType;

class CouponController extends Controller
{
    public function create(CouponStoreRequest $request) {
        $user = JWTAuth::parseToken()->authenticate();
        try {
            DB::beginTransaction();
            $coupon = Coupon::create([
                'buyer_id' => $user->id,
                'percentage' => $request->percentage,
                'stock' => 1,
                'code' => Str::random(8),
                'expiration' => $request->expiration
            ]);
            $dataMail = [
                'code' => $coupon->code,
            ];
            Mail::send('mails.CouponCreate', $dataMail,  function ($msj) use ($user) {
                $msj->subject('Coupon Create!');
                $msj->to($user->email);
            });
            
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Coupon Created!'], 200);
        } catch (\Throwable $th) {
            Log::error($th);
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $th], 400);
        }
    }

    public function validateCoupon(Request $request) {
        $user = JWTAuth::parseToken()->authenticate();
        $today_date = date("Y-m-d");
        $validator = Validator::make($request->all(), [
            'codeCoupon' => 'required',
        ]);
        if ($validator->fails()) return response()->json($validator->errors()->toJson(), 400);

        $userCoupon = UserCoupon::with('coupon')->where('user_id', $user->id)->whereHas('coupon', function ($q) use ($today_date) {
			$q->where('expiration','>' ,$today_date);
		})->first();

        if ($userCoupon != null) return response()->json(['status' => 'warning', 'message' => 'You already have an active coupon'], 400);

        $coupon = Coupon::where('code', $request->codeCoupon)
            ->where('expiration', '>', $today_date)
            ->where('buyer_id', '!=', $user->id)
            ->first();

        if(is_null($coupon)){
            return response()->json(['status' => 'error', 'message' => 'This Coupon is not available'], 400);
        }
        if ($coupon->buyer_id != $user->buyer_id) {
            return response()->json(['status' => 'error', 'message' => 'You are not referred to the creator of the coupon'], 400);
        }

        $user->coupons()->attach($coupon->id);

        return response()->json(['status' => 'success', 'data' => ['percentage' => $coupon->percentage, 'message' => 'Coupon Used!']], 200);
    }

    public function checkUserCouponActive(Request $request) 
    {
        $user = JWTAuth::parseToken()->authenticate();
        $userCoupon = UserCoupon::with('coupon')->where('user_id', $user->id)->whereHas('coupon', function ($q){
			$q->where('expiration','>' ,date("Y-m-d"));
		})->first();
        
        if ($userCoupon != null) {
            return response()->json(['status' => 'warning', 'data' => ['percentage' => $userCoupon->coupon->percentage, 'message' => 'You already have an active coupon']], 400);
        } else {
            return response()->json(['status' => 'success', 'message' => 'Not Coupon Active'], 200);
        }
    }
}
