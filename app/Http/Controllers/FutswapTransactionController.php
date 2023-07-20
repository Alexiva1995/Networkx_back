<?php

namespace App\Http\Controllers;

use App\Models\Bonus;
use App\Models\Environment;
use App\Models\Liquidaction;
use App\Models\Order;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WithdrawalErrors;
use App\Models\Project;
use App\Models\FutswapTransaction;
use App\Models\WalletComission;
use App\Models\Transaction;
use App\Services\BonusService;
use App\Services\FutswapService;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;

class FutswapTransactionController extends Controller
{
    private $futswap;
    private $bonusService;

    public function __construct(BonusService $bonusService, FutswapService $futswapService = null)
    {
        $this->futswap = $futswapService;
        $this->bonusService = $bonusService;
    }

    public function paymentConfirmation(Request $request)
    {

        $data = $request->all();

        Log::info("Request de confirmación de futswap:");
        Log::info($data);

        $this->validate($request, [
            'billId' =>  'required|string|exists:futswap_transactions',
            'hash' =>  'required|string',
            'secret' => 'required|string|exists:futswap_transactions',
            'externalTxId' => 'required|string|exists:futswap_transactions,order_id',
            'totalPaid' => 'required'
        ]);

        $transaction = FutswapTransaction::where('billId', $data['billId'])->first();

        if ($transaction->status == 'PAID') {
            return response()->json(['error' => 'This payment has already been confirmed'], 400);
        }
        $user = $transaction->orderPurchase->user;

        $transaction->status = $data['status'];
        $transaction->hash = $data['hash'];
        $transaction->totalPaid = $data['totalPaid'];
        $transaction->update();
        $transaction->orderPurchase->status = "1";
        $transaction->orderPurchase->hash = $request->hash;
        $transaction->orderPurchase->save();

        if ($transaction->orderPurchase->user->affiliate == '0') {
            $transaction->orderPurchase->user->affiliate = '1';
            $transaction->orderPurchase->user->save();
        }

        $amount = $transaction->orderPurchase->amount;
        $order_id  = $transaction->orderPurchase->id;
        $type = $transaction->orderPurchase->packageMembership->type;
        $order = Order::find($order_id);
        $user = User::where('id', $order->user_id)->first();
        $user->update(['status' => '1']);
        $amount = $order->packageMembership->amount;
        $buyer_id = $order->user_id;

        $projectStatus = ($order->membership_packages_id < 5 && $order->membership_packages_id > 7) ? 2 : 0;

        Project::create([
            'order_id' => $order_id,
            'amount' => $amount,
            'status' => $projectStatus,
        ]);

        $this->bonusService->directBonus($user, $amount, $buyer_id, $order);

        $dataEmail = [
            'user' => $user->fullName(),
            'program' => $order->packageMembership->getTypeName(),
        ];
        
        Mail::send('mails.approvedOrder',  ['data' => $dataEmail], function ($msj) use ($order) {
            $msj->subject('Order Approved.');
            $msj->to($order->user->email);
        });

        return response()->json(['message' => 'Confirmation succesfully'], 201);
    }

    public function procesarLiquidacion(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
        ]);
        // try {
        $amount = $request->amount;
        $user = JWTAuth::parseToken()->authenticate();
        $saldo = WalletComission::where([['user_id', $user->id], ['status', 0]])->get();
        $saldo_total = $saldo->sum('amount_available');
        if ($user->wallet != null) {
            // DB::beginTransaction();
            $wallet = Crypt::decrypt($user->wallet);
            $liquidacionOld = Liquidaction::where([['user_id', $user->id], ['status', 0]])->orderBy('id', 'desc')->first();
            if ($liquidacionOld != null) {
                $date = now()->format('Y-m-d');
                $liquidacionOldDate = $liquidacionOld->created_at->format('Y-m-d');
                if ($liquidacionOldDate == $date) {
                    return response()->json(['error' => 'You can no longer request withdrawals for today.'], 403);
                }
            }
            if ($amount >= 100) {

                if ($amount > $saldo_total) {
                    return response()->json(["error" => 'insufficient balance'], 403);
                }

                $total = $amount;
                $withdrawalRequest = $this->futswap->withdrawal($user, $total);
                Log::debug("Respuesta de solicitud de retiro futswap");
                Log::debug($withdrawalRequest);

                if ($withdrawalRequest[0] != 'error') {
                    $data_liquidaction = [
                        'user_id' => $user->id,
                        'total' => $total,
                        'monto_bruto' => $amount,
                        'wallet_used' => $user->wallet,
                        'feed' => 0,
                        'status' => 0,
                        'secret' => $withdrawalRequest[1]['secret'],
                        'processId' => $withdrawalRequest[1]['processId']
                    ];

                    $liquidacion = Liquidaction::create($data_liquidaction);

                    for ($i = 0; $i < $saldo->count(); $i++) {
                        $saldo[$i]['amount_available'];
                        if ($saldo[$i]['amount_available'] <= $amount) {
                            $amount =  $amount - $saldo[$i]['amount_available'];
                            $saldo[$i]['status'] = 1;
                            $saldo[$i]['amount_retired'] = $saldo[$i]['amount'];
                            //creando la transaccion
                            $data_transaction = [
                                'liquidation_id' => $liquidacion['id'],
                                'wallets_user_id' => $saldo[$i]['user_id'],
                                'wallets_commissions_id' => $saldo[$i]['id'],
                                'amount' => $saldo[$i]['amount'],
                                'amount_retired' => $saldo[$i]['amount_available'],
                                'amount_available' => 0,
                            ];

                            $saldo[$i]['amount_available'] = 0;
                            $saldo[$i]->update();
                            Transaction::create($data_transaction);
                        } else {
                            if ($amount > 0) {
                                $saldo[$i]['amount_available'] =  $saldo[$i]['amount_available'] - $amount;
                                $saldo[$i]['amount_retired'] = $amount;
                                $saldo[$i]->update();
                                $data_transaction = [
                                    'liquidation_id' => $liquidacion['id'],
                                    'wallets_user_id' => $saldo[$i]['user_id'],
                                    'wallets_commissions_id' => $saldo[$i]['id'],
                                    'amount' => $saldo[$i]['amount'],
                                    'amount_retired' => $saldo[$i]['amount_retired'],
                                    'amount_available' => $saldo[$i]['amount_available'],
                                ];
                                Transaction::create($data_transaction);
                                $i = $saldo->count();
                            }
                        }
                    }


                    try {
                        $dataMail = [
                            'name' => $user->name,
                            'amount' => $total,
                            'date' => now()->format('Y-m-d'),
                            'wallet' => Crypt::decrypt($user->wallet)
                        ];

                        Mail::send('mails.withdrawMail', $dataMail,  function ($msj) use ($user) {
                            $msj->subject('Withdrawal request');
                            $msj->to($user->email);
                        });

                        return response()->json(['The withdrawal request was made successfully'], 200);
                    } catch (\Throwable $th) {
                        \Log::error($th);
                        return response()->json(['The withdrawal request was made successfully, but there was an error sending the mail'], 200);
                    }
                } else {
                    return response()->json(['error' => 'Futswap is not available at this time please try again later.'], 403);
                }
            } else {
                return response()->json(['error' => 'The minimum amount to withdraw is 100 USD.'], 403);
            }
            // DB::commit();
        } else {
            return response()->json(['error' => 'You must register a wallet'],403);
        }
        // } catch (\Throwable $th) {
        //     DB::rollBack();
        //     Log::error('Liquidaction - procesarLiquidacion -> Error: ' . $th->getBody());
        //     return response()->json(['error' => 'An error occurred, contact the administrator.'], 500);
        // }
    }

    public function generateCode()
    {
        $user = JWTAuth::parseToken()->authenticate();

        $code = Str::random(6);

        $codeEncrypt = crypt::encrypt($code);

        $user->update(['code_security' => $codeEncrypt]);

        $dataMail = [
            'code' => $code,
        ];
        Mail::send('mails.CodeSecurity', $dataMail,  function ($msj) use ($user) {
            $msj->subject('Wallet creation security code');
            $msj->to($user->email);
        });

        return response()->json(['success' => 'Codigo enviado con exito']);
    }

    public function saveWallet(Request $request)
    {
        $rules = [
            'wallet' => 'required',
            'code_security' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = JWTAuth::parseToken()->authenticate();

        $codeEncryp = $user->code_security;

        $code = Crypt::decrypt($codeEncryp);

        if ($code === $request->code_security) {
            $walletEncrypt = Crypt::encrypt($request->wallet);

            $user->update([
                'wallet' => $walletEncrypt,
                'code_security' => null,
            ]);

            return response()->json(['Wallet successfully registered'], 200);
        } else {
            return response()->json(['error' => 'The code does not match'], 400);
        }
    }

    public function withdrawalConfirmation(Request $request)
    {
        //return $request->to;
        Log::info("RetiroFutswap");
        Log::info($request->all());

        $this->validate($request, [
            'processId' =>  'required|string|exists:liquidactions',
        ]);
        foreach ($request->to as $to) {
            $liquidaction = Liquidaction::where([['processId', $request->processId], ['secret', $to['secret']]])->first();
            if ($liquidaction != null) {
                $liquidaction->status = 1;
                $liquidaction->hash = $to['hash'];
                $liquidaction->update();

                $this->updatecompletewallets($liquidaction);
                // $dataEmail = [
                //     'name' => $liquidaction->getUserLiquidation->name,
                //     'amount' =>$liquidaction->total,
                //     'logo' => public_path('/images') . '/login/connect.png'
                // ];
                // Mail::send('mails.withdrawConfirmation', $dataEmail,  function ($msj) use ($liquidaction) {
                // $msj->subject('Retiro Confirmado');
                // $msj->to($liquidaction->getUserLiquidation->email);
                // $msj->cc('retirosconnect@gmail.com');
                // });
            } else {
                return response()->json(['message' => 'No settlement found error'], 400);
            }  //enviar correo del retiro succeess $liquidation->total   //enviar correo del retiro canceled $liquidation->total */
        }
        if ($request->errors != null) {
            foreach ($request->errors as $error) {
                $liquidactionFail = Liquidaction::where([['secret', $error['secret']], ['processId', $request->processId]])->first();
                if ($liquidactionFail != null) {
                    $liquidactionFail->status = 2;
                    $liquidactionFail->update();
                    WithdrawalErrors::create([
                        'user_id' => $error['userId'],
                        'processId' => $request['processId'],
                        'name' => $error['userName'],
                        'wallet' => Crypt::encryptString($error['address']),
                        'value' => $error['value'],
                        'secret' => $error['secret'],
                        'error_code' => $error['errorCode'],
                        'error_message' => $error['errorMessage']
                    ]);
                    $this->updatecancelwallets($liquidaction);
                }
            }
        }

        return response()->json(['message' => 'Confirmation succesfully'], 201);
    }

    private function updatecompletewallets($liquidation)
    {
        $transaccion = Transaction::where([['liquidation_id', $liquidation->id], ['status', 0]])->get();
        for ($j = 0; $j < count($transaccion); $j++) {
            if (isset($transaccion[$j])) {
                $wallets_id = $transaccion[$j]['wallets_commissions_id'];
                $transaccion[$j]['status'] = 1;
                $transaccion[$j]->update();
                $wallets = WalletComission::where('id', $wallets_id)->get();
                for ($l = 0; $l < count($wallets); $l++) {
                    if (!empty($wallets[$l])) {
                        if ($wallets[$l]['amount_available'] == 0) {
                            $wallets[$l]['status'] = 2;
                        }
                        $wallets[$l]->update();
                    }
                }
            }
        }
        // $idliquidation = $liquidation->id;
        // $liquidation = Liquidaction::find($idliquidation);
        // Liquidaction::where('id', $idliquidation)->update([
        //     'status' => 1,
        // ]);



        $accion = 'Aprobada';

        $arrayLog = [
            'liquidation_id' => $liquidation->id,
            'accion' => $accion,
            'created_at' => Carbon::now()
        ];
        DB::table('log_liquidactions')->insert($arrayLog);
    }

    private function updatecancelwallets($liquidation)
    {
        $transaccion = Transaction::where([['liquidation_id', $liquidation->id], ['status', 0]])->get();
        for ($i = 0; $i < count($transaccion); $i++) {
            if (isset($transaccion[$i])) {
                $wallets_id = $transaccion[$i]['wallets_commissions_id'];
                $transaccion[$i]['status'] = 2;
                $transaccion[$i]->update();
                $wallets = WalletComission::where('id', $wallets_id)->get();
                for ($u = 0; $u < count($wallets); $u++) {
                    if (!empty($wallets[$u])) {
                        $wallets[$u]['amount_available'] =  $wallets[$u]['amount_available'] + $wallets[$u]['amount_retired'];
                        $wallets[$u]['amount_retired'] = 0;
                        $wallets[$u]['status'] = 0;
                        $wallets[$u]->update();
                    }
                }
            }
        }
    }

    public function verify_wallet(Request $request)
    {

        $this->validate($request, [
            'id' =>  'required|string|exists:wallet_users,user_id',
            'wallet' => 'required|string',
        ]);

        $user = User::where('id', $request->id)->first();
        $walletdescript = $user->wallet_decrypt();

        if ($walletdescript == $request->wallet) {
            return response()->json(['message' => 'Confirmation succesfully'], 201);
        } else {
            return  response()->json(['message' => 'Expectation Failed.', 'error' => 'Unknown wallet'], 417);
        }
    }
    public function saveTokenAuth(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'futswap_key' => 'required|string|min:36',
                'code' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $user = JWTAuth::parseToken()->authenticate();

            if ($user->code_security != $request->code) {
                return response()->json(['errors' => ['Invalid code']], 403);
            }
            $token = Environment::first();
            $tokenEncrypt = Crypt::encrypt($request->futswap_key);
            $token->futswap_token = $tokenEncrypt;
            $token->save();
            return response()->json(['success' => 'Token Update', 201]);
        } catch (\Throwable $th) {
            return response()->json(['errors' => ['Please contact support']], 500);
        }
    }
}
