<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Formulary;
use App\Models\Order;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\WalletComission;
use Illuminate\Database\Eloquent\Collection;
use Tymon\JWTAuth\Facades\JWTAuth;

class DashboardController extends Controller
{
	public function getUser(Request $request)
    {
        $user = User::findOrFail($request->id);
        return response()->json($user, 200);

    }

    public function getWalletBalance(Request $request) {
		$user = JWTAuth::parseToken()->authenticate();
		if (isset($request->id)) {
			$user = User::find($request->id);
		}

    	$balance = WalletComission::where('user_id', $user->id)->sum('amount_available');
    	return response()->json($balance, 200);
    }

    public function getMostDownloadDoc(Request $request) {
    	$document = Document::orderBy('downloads', 'desc')->first();
    	return response()->json($document, 200);
    }
	
    public function getUserPrograms(Request $request) {
		$user = JWTAuth::parseToken()->authenticate();
		if (isset($request->id)) {
			$user = User::find($request->id);
		}

        if ($user->admin == '1') {
			$orders = Order::with('user', 'packageMembership', 'project')->get();
        } else {
			$orders = Order::where('user_id', $user->id)->with('user', 'packageMembership', 'project')->get();
        }

		$data = [];
		foreach ($orders as $order) {
            if (isset($order->project)) {
                $phase = ($order->project->phase2 == null && $order->project->phase1 == null)
                ? ""
                : (($order->project->phase2 != null)
                ? "Phase 2"
                : "Phase 1");
                
                $formulary = Formulary::where('project_id', $order->project->id)->first();
                
                $data[] = [
                    'id' => $order->id,
                    'program' => $order->packageMembership->getTypeName(),
                    'phase' => $phase ?? "",
                    'account' => $order->packageMembership->account,
                    'user' => $order->user,
                    'status' => ($order->project) ? $order->project->status : 0,
                    'amount' => $order->amount,
                    'formulary' => ($formulary) ? $formulary->getFormularyPassword() : null,
                ];
            }
		}
    	
		return response()->json($data, 200);
    }

    public function getUserOrders(Request $request) {
		$user = JWTAuth::parseToken()->authenticate();
		if (isset($request->id)) {
			$user = User::find($request->id);
		}

        if ($user->admin == '1') {
			$orders = Order::with('user', 'packageMembership')->get();
        } else {
			$orders = Order::where('user_id', $user->id)->with('user', 'packageMembership')->get();
        }

		$data = [];
		foreach ($orders as $order) {
            $data[] = [
                'id' => $order->id,
                'program' => $order->packageMembership->getTypeName(),
                'account' => $order->packageMembership->account,
                'user' => $order->user,
                'status' => $order->status,
                'amount' => $order->amount,
            ];
		}
    	
		return response()->json($data, 200);
    }

	public function getUserRefunds(Request $request) {
		$user = JWTAuth::parseToken()->authenticate();
		if (isset($request->id)) {
			$user = User::find($request->id);
		}

		if ($user->admin == '1') {
			$refunds = WalletComission::where('type', 3)->with('package')->with('order')->get();
        } else {
			$refunds = WalletComission::where('user_id', $user->id)->where('type', 3)->with('package')->with('order')->get();
        }

		$data = array();
        foreach ($refunds as $refund) {
            if (isset($refund->order->project)) {
                $phase = ($refund->order->project->phase2 == null && $refund->order->project->phase1 == null)
                ? ""
                : (($refund->order->project->phase2 != null)
                ? "Phase 2"
                : "Phase 1");
                
                $object = [
                    'id' => $refund->id,
                    'program' => $refund->order->packageMembership->getTypeName(),
                    'phase' => $phase ?? "",
                    'account' => $refund->order->packageMembership->account,
                    'amount' => $refund->amount,
                    'date' => $refund->created_at,
                ];
                array_push($data, $object);
            }
        }

        return response()->json($data, 201);
    }
}
