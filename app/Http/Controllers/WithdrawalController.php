<?php

namespace App\Http\Controllers;

use App\Models\Liquidaction;
use App\Models\Transaction;
use App\Models\User;
use App\Services\CoinpaymentsService;
use Illuminate\Support\Facades\DB;
use App\Models\ProfileLog;
use Illuminate\Support\Facades\Validator;
use App\Models\WalletComission;
use Illuminate\Support\Facades\Mail;
// use app\Services\CoinpaymentsService;
use App\Mail\CodeSecurity;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Tymon\JWTAuth\Facades\JWTAuth;

class WithdrawalController extends Controller
{
    protected $CoinpaymentsService;
    public function __construct(CoinpaymentsService $CoinpaymentsService)
    {
        $this->CoinpaymentsService = $CoinpaymentsService;
    }

    public function getWithdrawals($id = null)
    {
        $user = JWTAuth::parseToken()->authenticate();
        if ($user->admin == 1) {
            if ($id != null) {
                $withdrawals = Liquidaction::where('user_id', $id)->with('user', 'package')->get();
                foreach ($withdrawals as $withdrawal) {
                    $withdrawal->wallet_used = Crypt::decrypt($withdrawal->wallet_used) ?? $withdrawal->wallet_used;
                    $withdrawal->hash = $withdrawal->hash ?? "";
                }
            }
            else {
                $withdrawals = Liquidaction::with('user', 'package')->get();
                foreach ($withdrawals as $withdrawal) {
                    $withdrawal->wallet_used = Crypt::decrypt($withdrawal->wallet_used) ?? $withdrawal->wallet_used;
                    $withdrawal->hash = $withdrawal->hash ?? "";
                }
            }
        }
        else {
            $withdrawals = Liquidaction::where('user_id', $user->id)->with('user', 'package')->get();
            foreach ($withdrawals as $withdrawal) {
                $withdrawal->wallet_used = Crypt::decrypt($withdrawal->wallet_used) ?? $withdrawal->wallet_used;
                $withdrawal->hash = $withdrawal->hash ?? "";
            }
        }

        return response()->json($withdrawals, 200);
    }
    public function getWithdrawalsDownload() {
        $withdrawals = Liquidaction::with('package', 'user')
            ->get();
        $data = array();
        foreach ($withdrawals as $withdrawal) {
            $item = [
                'id' => $withdrawal->id,
                'created_at' => $withdrawal->created_at->format('d-m-Y'),
                'user' => $withdrawal->user->name,
                'hash' => $withdrawal->hash,
                'wallet' => Crypt::decrypt($withdrawal->wallet_used) ?? $withdrawal->wallet_used,
                'status' => $withdrawal->getStatus(),
                'amount' => $withdrawal->total,
            ];
            array_push($data, $item);
        }
        return response()->json($data, 200);
    }

    public function SendSecurityCode(Request $request)
    {
        $user = User::find($request->auth_user_id);
        $log = new ProfileLog;
        $code = Str::random(12);
        $code_encrypted = Hash::make($code);

        $user->update([
            'code_security' => $code_encrypted,
            'code_verified_at' => Carbon::now(),
        ]);

        $log->create([
            'user' => $user->id,
            'subject' => 'Request code security',
        ]);

        Mail::to($user->email)->send(new CodeSecurity($code));

        return response()->json('Code send succesfully', 200);
    }

    public function processWithdrawal(Request $request)
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
    
        $encryptedWallet = Crypt::encrypt($request->wallet);

        $wallets = WalletComission::where('user_id', $user->id)->where('status', 0)->get();

        $walletsAmount = $wallets->sum('amount_available');
    
        $amount = $request->amount;
    
        $feed = 2 + ($amount * 0.01); // Restar 2 y el 1% del amount
    
        $codeEncryp = $user->code_security;
    
        $code = Crypt::decrypt($codeEncryp);
    
        $fechaCode = date('Y-m-d H:i:s'); // Obtener la fecha y hora actual
    

        if ($code == $request->code_security) {
            $user->update([
                'code_security' => null,
            ]);
            $liquidAction = Liquidaction::create([
                'user_id' => $user->id,
                'reference' => 'Pago de Comisiones Matrix',
                'total' => $walletsAmount,
                'monto_bruto' => $walletsAmount - $feed,
                'feed' => $feed,
                'wallet_used' => $encryptedWallet,
                'fecha_code' => $fechaCode,
                'type' => 0,
                'status' => 0,
            ]);
    
            // Actualizar la columna liquidation_id en las filas de walletcomissions
            foreach ($wallets as $wallet) {
                $wallet->update([
                    'liquidation_id' => $liquidAction->id,
                    'status' => 1,
                    'amount_available' => 0,
                    'amount_retired' => $wallet->amount
                ]);
            }
            return response()->json(['message' => 'Retiro registrado y pendiente de aprobación'], 200);
        } else {
            return response()->json(['message' => 'Withdrawal request failure'], 400);
        }

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
                   'password' => 'required',
                 ];
                

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 400);
                }

                $user = JWTAuth::parseToken()->authenticate();

                $codeEncryp = $user->code_security;
                $code = Crypt::decrypt($codeEncryp);
                

                // Obtén la contraseña del formulario
                $password = $request->password;

                $storedPassword = DB::connection('b2b_auth')
                    ->table('users')
                    ->where('id', $user->id)
                    ->select('password')
                    ->first();
              
            
            
                if (!Hash::check($password, $storedPassword->password)) {
                return response()->json(['error' => 'Incorrect password'], 400);
                }
                
                
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

            public function liquidationData()
            {
                $liquidations = Liquidaction::with('user:id,name')
                    ->select('id', 'user_id', 'total', 'status')
                    ->get();
            
                $data = $liquidations->map(function ($liquidation) {
                    return [
                        'id' => $liquidation->id,
                        'user_name' => $liquidation->user->name,
                        'user_id' => $liquidation->user_id,
                        'amount' => $liquidation->total,
                        'status' => $liquidation->status,
                    ];
                });
            
                return response()->json($data, 200);
            }
            
            

            public function withdrawalUpdate(Request $request)
            {
                $user = JWTAuth::parseToken()->authenticate();

                $codeEncryp = $user->code_security;
                $code = Crypt::decrypt($codeEncryp);


                if ($code === $request->code_security) {

                $user->update([
                    'code_security' => null,
                ]);    

                    
                $status = $request->status;
                $liquidationId = $request->liquidation_id;
                $liquidation = Liquidaction::findOrFail($liquidationId);
                if ($status == 1) {
                    // Actualizar el estado de liquidaciones a aprobado (status = 1)
                   

                    // Buscar datos en walletcomissions con el mismo liquidation_id y actualizar los valores


                    // Obtener los datos de la liquidación
                    
                    $amount = $liquidation->amount;
                    $decryptedWallet = Crypt::decrypt($liquidation->wallet_used);

                    // Lógica para enviar a la pasarela de pago (coinpayment) utilizando el método withdrawal

                    // Ejemplo genérico para enviar a la pasarela de pago
                    $balanceConpaymentStatus = $this->CoinpaymentsService->get_balances();
                    if ($balanceConpaymentStatus['USDT.TRC20']->balancef > $amount) {
                        Liquidaction::where('id', $liquidationId)
                        ->update(['status' => 1]);
                        $this->CoinpaymentsService->create_withdrawal($amount, $decryptedWallet, $liquidation->id);
                    } else {
                        return response()->json(['message' => 'No balance available'], 422);
                    }
                } else {

                     // Actualizar el estado de liquidaciones a aprobado (status = 2)
                     $liquidation->status = 3;
                     $liquidation->save();
                     

                    // Buscar datos en walletcomissions con el mismo liquidation_id y actualizar los valores
                    $wallets = WalletComission::where('liquidation_id', $liquidationId)->where('status', 1)->get();
                    foreach ($wallets as $wallet) {
                        $wallet->update([
                            'status' => 0, // Actualizar el estado a 3 (Rechazado)
                            'amount_available' => $wallet->amount,
                            'amount_retired' => 0
                        ]);
                    } 
                }
            }else {
                return response()->json(['error' => 'The code does not match'], 400);
            }                 


                return response()->json(['message' => 'Proceso de retiro actualizado'], 200);
            }

            

}
