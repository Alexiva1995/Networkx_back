<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WalletComission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Crypt;

class WalletController extends Controller
{


    public function getChartData()
    {
        $user = JWTAuth::parseToken()->authenticate();

        $availableCommissions = WalletComission::where('user_id', $user->id)
        ->where('status', 0)
        ->get();

        $availableAmount = $availableCommissions->sum('amount');
        $availableIds = $availableCommissions->pluck('id');

        $withdrawalAmount = WalletComission::where('user_id', $user->id)
            ->where('status', 2)
            ->sum('amount');


        $totalEarning = $availableAmount + $withdrawalAmount;

        $data = [
            'available' => $availableAmount,
            'withdrawal' => $withdrawalAmount,
            'totalEarning' => $totalEarning,
            'availableIds' => $availableIds,
        ];

        return response()->json($data, 200);
    }

    public function getMonthlyGain()
{
    $user = JWTAuth::parseToken()->authenticate();

    // Obtener los datos de la tabla 'Wallet comision' ordenados por fecha de creación y usuario especificado
    $monthlyGains = WalletComission::where('user_id', $user->id)->orderBy('created_at')->get();

    $totalMonthlyGains = $monthlyGains->sum('amount');

    // Crear un arreglo para almacenar los datos de la gráfica
    $data = [
        'totalMonthlyGains' => $totalMonthlyGains,
        'days' => [],
    ];

    // Iterar sobre los registros de la tabla 'Wallet comision'
    foreach ($monthlyGains as $item) {
        $diaSemana = $item->created_at->format('D');
        $ganancias = $item->amount;

        // Agregar los datos al arreglo de los días de la semana
        $data['days'][$diaSemana] = $ganancias;
    }

    // Devolver los datos de la gráfica como respuesta JSON
    return response()->json($data, 200);
}



    public function walletUserDataList(Request $request)
    {
        $filter = $request->get('dataFilter');

        $user = JWTAuth::parseToken()->authenticate();
        

        $walletCommissions = WalletComission::where('user_id', $user->id)
            ->select('description', 'status', 'created_at', 'amount','id')
            ->filter($filter)
            ->get();

        $data = $walletCommissions->map(function ($walletCommission) {
            return [
                'id' => $walletCommission->id,
                'description' => $walletCommission->description,
                'status' => $walletCommission->status,
                'created_at' => $walletCommission->created_at->format('Y-m-d H:i:s'),
                'amount' => $walletCommission->amount,
            ];
        });

        return response()->json($data, 200);
    }

    public function walletAdminDataList()
    {
        $walletCommissions = WalletComission::with('user')
        ->select('id', 'user_id', 'description', 'status', 'created_at', 'amount')
        ->get();

         $data = $walletCommissions->map(function ($walletCommission) {
            return [
            'id' => $walletCommission->id,
            'user_id' => $walletCommission->user_id,
            'user_name' => $walletCommission->user->name,
            'description' => $walletCommission->description,
            'status' => $walletCommission->status,
            'created_at' => $walletCommission->created_at->format('Y-m-d H:i:s'),
            'amount' => $walletCommission->amount,
             ];
         });

     return response()->json($data, 200);
    }

    public function addBalanceToUser(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users',
            'email' => 'required|email|exists:users',
            'amount' => 'required'
        ]);

        $auth_user = JWTAuth::parseToken()->authenticate();

        $user = User::find($request->id);

        $selectedType = $request->type;

        if ($selectedType == 0) {
            $type = '0'; // Comisión Nivel 1
        }
        if ($selectedType == 2) {
            $type = '2'; // Comisión Nivel 2
        }
        if ($selectedType == 1) {
            $type = '1'; // Trading
        }

        if ($selectedType == 3) {
            $type = '3'; // refund
        }
        $description = '';

        if($selectedType == 3) {
            $description = "Refund";
        }
        if($selectedType == 2) {
            $description = "Comission Level 2";
        }
        if($selectedType == 0) {
            $description = "Comission Level 1";
        }
        if($selectedType == 1) {
            $description = "Trading Profit";
        }

        $wallet = WalletComission::create([
            'user_id' => $user->id,
            'buyer_id' => $auth_user->id,
            'membership_id' => null,
            'order_id' => null,
            'description' => $description,
            'type' => $type,
            'level' => 1,
            'status' => 0,
            'available_withdraw' => 0,
            'amount_available' => $request->amount,
            'amount' => $request->amount,
        ]);
        return $wallet;
    }

    public function refundsList(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        if ($user->admin == 1) {
            $refunds = WalletComission::where('type', 3)
                ->with('order', 'user')
                ->get();
        } else {
            $refunds = WalletComission::where('type', 3)
                ->where('user_id', $request->auth_user_id)
                ->with('order', 'user')
                ->get();
        }
        $data = array();
        foreach ($refunds as $refund) {
            $item = [
                'id' => $refund->id,
                'program' => 'FYT ' . ucfirst(strtolower($refund->order->packageMembership->getTypeName())) . $refund->order->packageMembership->account,
                'status' => $this->getStatus($refund),
                'status_name' => $this->getStatusName($this->getStatus($refund)),
                'amount' => $refund->amount,
                'amount_available' => $refund->amount_available,
                'created_at' => $refund->created_at->format('d-m-Y')
            ];
            if ($user->admin == 1) {
                $item['name'] = $refund->user->name;
                $item['last_name'] = $refund->user->last_name;
                $item['user_name'] = $refund->user->user_name;
                $item['email'] = $refund->user->email;
            };
            array_push($data, $item);
        }
        return response()->json(['status' => 'success', 'data' => $data, 201]);
    }
    public function getRefundsDownload()
    {
        $refunds = WalletComission::where('type', 3)
            ->with('order', 'user')
            ->get();
        $data = array();
        foreach ($refunds as $refund) {
            $item = [
                'id' => $refund->id,
                'created_at' => $refund->created_at->format('d-m-Y'),
                'user' => $refund->user->name,
                'program' => 'FYT ' . ucfirst(strtolower($refund->order->packageMembership->getTypeName())) . $refund->order->packageMembership->account,
                'status' => $refund->getStatus(),
                'amount' => $refund->amount,
                'amount_available' => $refund->amount_available,
            ];
            array_push($data, $item);
        }
        return response()->json($data, 200);
    }
    public function getComissionsDownload()
    {
        $comissions = WalletComission::where('type', '!=', 3)
            ->with('order', 'user')
            ->get();
        $data = array();
        foreach ($comissions as $comission) {
            $item = [
                'id' => $comission->id,
                'created_at' => $comission->created_at->format('d-m-Y'),
                'user' => $comission->user->name,
                'description' => $comission->englishDescription(),
                'status' => $comission->getStatus(),
                'amount' => $comission->amount,
                'amount_available' => $comission->amount_available,
            ];
            array_push($data, $item);
        }
        return response()->json($data, 200);
    }

    private function getStatus($refund)
    {
        $package = $refund->order->packageMembership->type;
        $project = $refund->order->project;

        if ($package == "3" || $package == "2") {
            return !empty($project->formularies) ? intval($project->formularies->status) : 0;
        }
        if ($package == "1") {
            return !empty($project->formularies[1]) ? intval($project->formularies[1]->status) : 0;
        }
    }

    public function getStatusName($status)
    {
        $array = ['Pending', 'Passed', 'Not Approved', 'Expired'];
        return $array[$status];
    }

    public function getRefunds()
    {
        $user = JWTAuth::parseToken()->authenticate();
        if ($user->admin == 1) {
            $refunds = WalletComission::where('type', 3)->with('package')->with('order')->get();
        } else {
            $refunds = WalletComission::where([['user_id', $user->id], ['type', 3]])->with('package')->with('order')->get();
        }

        $data = array();
        foreach ($refunds as $refund) {
            if (isset($refund->order->project)) {
                $phase = ($refund->order->project->phase2 == null && $refund->order->project->phase1 == null)
                    ? ""
                    : (($refund->order->project->phase2 != null)
                        ? "Phase 2"
                        : "Phase 1");
            }

            $object = [
                'id' => $refund->id,
                'user' => $refund->user,
                'program' => $refund->order->packageMembership->getTypeName(),
                'phase' => $phase ?? "",
                'account' => $refund->order->packageMembership->account,
                'amount' => $refund->amount,
                'date' => $refund->created_at,
            ];
            array_push($data, $object);
        }

        return response()->json($data, 201);
    }

    public function devolutionsAdmin()
    {
        $devolutions = WalletComission::where('type', 3)->with('package', 'order')->get(['id', 'amount', 'membership_id', 'created_at']);

        return response()->json($devolutions, 200);
    }

    public function getWallets(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        if (isset($request->user_id)) {
            $user = User::findOrFail($request->user_id);
        }

        $data = WalletComission::with(['user', 'package'])->where('user_id', $user->id)->get();

        // $data = new Collection();
        // foreach ($wallets as $wallet) {
        //     $buyer = User::find($wallet->buyer_id);

        //     switch ($wallet->type) {
        //         case '0':
        //             $type = 'Referral I';
        //             break;

        //         case '1':
        //             $type = 'Assigned';
        //             break;

        //         case '2':
        //             $type = 'Referral II';
        //             break;

        //         default:
        //             $type = 'Refund';
        //             break;
        //     }

        //     $object = new \stdClass();
        //     $object->id = $wallet->id;
        //     if($wallet->order_id) {
        //         $object->buyer = 'FYT ' . $wallet->order->packageMembership->getTypeName();
        //     } else {
        //         $object->buyer = ucwords(strtolower($buyer->name . " " . $buyer->last_name));
        //     }
        //     $object->type = $type;
        //     $object->amount = $wallet->amount;
        //     $object->status = $wallet->status;
        //     $object->date = $wallet->created_at;
        //     $object->program = $wallet->order != null ? "{$wallet->order->packageMembership->getTypeName()} {$wallet->order->packageMembership->account}" : "";
        //     $data->push($object);
        // }
        return response()->json($data, 200);
    }

    public function getWalletsAdmin()
    {
        $data = WalletComission::with(['user','package'])->get();
        return response()->json($data, 200);
    }

    public function getTotalAvailable(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        if (isset($request->user_id)) {
            $user = User::findOrFail($request->user_id);
        }

        $amount = WalletComission::where('user_id', $user->id)->where('status', '0')->sum('amount_available');

        $data = [
            'text' => number_format($amount, 2, '.', ','),
            'number' => $amount
        ];

        return response()->json($data, 200);
    }

    public function getTotalDirects(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        if (isset($request->user_id)) {
            $user = User::findOrFail($request->user_id);
        }

        $amount = WalletComission::where('user_id', $user->id)
            ->where('status', '0')
            ->where('type', '0')
            ->where('level', 1)
            ->sum('amount_available');

        return response()->json(number_format($amount, 2, '.', ','), 200);
    }

    public function checkWalletUser(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        if (isset($request->user_id)) {
            $user = User::findOrFail($request->user_id);
        }

        $data = [];

        if ($user->wallet) {

            $data['bool'] = true;
            $data['wallet'] = Crypt::decrypt($user->wallet);

            return response()->json($data, 200);
        } else {

            $data['bool'] = false;
            $data['wallet'] = '';

            return response()->json($data, 200);
        }
    }
}
