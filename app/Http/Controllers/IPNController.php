<?php

namespace App\Http\Controllers;

use App\Models\WalletComission;
use Illuminate\Http\Request;
use App\Services\CoinpaymentsService;
use Hexters\CoinPayment\Entities\CoinpaymentTransaction;
use App\Jobs\CoinpaymentListener;
use App\Models\CoinpaymentWithdrawal;
use App\Models\Liquidaction;
use App\Models\Order;
use App\Models\Package;
use App\Models\User;
use Hexters\CoinPayment\Emails\IPNErrorMail as SendEmail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Hexters\CoinPayment\Traits\ApiCallTrait;

class IPNController extends Controller
{
    use ApiCallTrait;

    protected $CoinpaymentService, $OrderController;


    public function __construct(CoinpaymentsService $coinpaymentsService, OrderController $orderController)
    {
        $this->CoinpaymentService = $coinpaymentsService;
        $this->OrderController = $orderController;

    }

    public function __invoke(Request $req){

        $cp_merchant_id   = config('coinpayment.ipn.config.coinpayment_merchant_id');
        $cp_ipn_secret    = config('coinpayment.ipn.config.coinpayment_ipn_secret');
        $cp_debug_email   = config('coinpayment.ipn.config.coinpayment_ipn_debug_email');
        /* Filtering */
        if(!empty($req->merchant) && $req->merchant != trim($cp_merchant_id)){
            if(!empty($cp_debug_email)) {
                Mail::to($cp_debug_email)->send(new SendEmail([
                    'message' => 'No or incorrect Merchant ID passed'
                ]));
            }
            return response('No or incorrect Merchant ID passed', 401);
        }
        $request = file_get_contents('php://input');
        if ($request === FALSE || empty($request)) {
            if(!empty($cp_debug_email)) {
                Mail::to($cp_debug_email)->send(new SendEmail([

                    'message' => 'Error reading POST data'
                ]));
            }
            return response('Error reading POST data', 401);
        }
        $hmac = hash_hmac("sha512", $request, trim($cp_ipn_secret));
        if (!hash_equals($hmac, $_SERVER['HTTP_HMAC'])) {
            if(!empty($cp_debug_email)) {
                Mail::to($cp_debug_email)->send(new SendEmail([
                    'message' => 'HMAC signature does not match'
                ]));
            }
            return response('HMAC signature does not match', 401);
        }
        if($req->ipn_type == 'deposit' || $req->ipn_type == 'api'){
            $transactions = CoinpaymentTransaction::where('txn_id', $req->txn_id)->first();

            if($transactions){

                    $order = Order::where('id', $transactions->order_id)->first();
                    $info = $this->api_call('get_tx_info', ['txid' => $req->txn_id]);

                    if($info['error'] != 'ok'){
                        Mail::to($cp_debug_email)->send(new SendEmail([
                            'message' => date('Y-m-d H:i:s ') . $info['error']
                        ]));
                    }
                    try {
                        if ($req->ipn_type == 'deposit' || $req->ipn_type == 'api') {
                            if ($info['result']['status'] >= 100) {
                                $order->hash = $req->txn_id;
                                $order->save();
                                $this->OrderController->processOrderApproved($order);
                            }
                            if ($info['result']['status'] < 0) {
                                $order->status = '2';
                                $order->hash = $req->txn_id;
                                $order->save();
                            }
                            if ($info['result']['status'] == 2) {
                                $order->status = '3';
                                $order->hash = $req->txn_id;
                                $order->save();
                                $this->OrderController->processOrderApproved($order);
                            }
                        }
                        $transactions->update($info['result']);
                    } catch (\Exception $e) {
                        Mail::to($cp_debug_email)->send(new SendEmail([
                            'message' => date('Y-m-d H:i:s ') . $e->getMessage()
                        ]));
                    }

                    dispatch(new CoinpaymentListener(array_merge($transactions->toArray(), [
                        'transaction_type' => 'old'
                    ])));

                } else {
                    if(!empty($cp_debug_email)) {
                        Mail::to($cp_debug_email)->send(new SendEmail([
                            'message' => 'Txn ID ' . $req->txn_id . ' not found from database ?'
                        ]));
                    }
                }

        }

        if($req->ipn_type == 'withdrawal'){
            $transactions = CoinpaymentWithdrawal::where('tx_id', $req->id)->first();
            if($transactions){
                $liquidation = Liquidaction::where('id', $transactions->liquidation_id)->first();
                $info = $this->api_call('get_withdrawal_info', ['id' => $req->id]);

                try {
                        if ($info['result']['status'] == 2) {
                            $liquidation->status = 2;
                            $liquidation->hash = $req->id;
                            $liquidation->save();
                            WalletComission::where('status', 1)->where('liquidation_id', $liquidation->id)->update([
                                'status' => 2
                            ]);
                            $user = User::find($liquidation->user_id);

                            $dataEmail = [
                                'user' => $user,
                            ];
                            Mail::send('mail.withdrawal',  ['data' => $dataEmail], function ($msj) use ($user) {
                                $msj->subject('Retiro realizado con éxito');
                                $msj->to($user->email);
                            });
                        }
                        if ($info['result']['status'] == -1 ) {
                            $liquidation->status = 3;
                            $liquidation->hash = $req->id;
                            $liquidation->save();
                            $wallets =  WalletComission::where('status', 1)->where('liquidation_id', $liquidation->id)->get();
                            foreach ($wallets as $wallet) {
                                $wallet->update([
                                    'status' => 0, // Actualizar el estado a 3 (Rechazado)
                                    'amount_available' => $wallet->amount,
                                    'amount_retired' => 0
                                ]);
                            } 
                        }
                        // if ($info['result']['status'] == 1) {
                        //     $liquidation->status = 3;
                        //     $liquidation->hash = $req->id;
                        //     $liquidation->save();
                        // }

                    $transactions->update($info['result']);
                } catch (\Exception $e) {
                    Mail::to($cp_debug_email)->send(new SendEmail([
                        'message' => date('Y-m-d H:i:s ') . $e->getMessage()
                    ]));
                }

            }else {
                if(!empty($cp_debug_email)) {
                    Mail::to($cp_debug_email)->send(new SendEmail([
                        'message' => 'Whitdrawal ID ' . $req->id . ' not found from database ?'
                    ]));
                }
            }
        }
        }
}
