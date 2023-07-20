<?php

namespace App\Services;

use App\Models\CoinpaymentTransaction;
use App\Models\CoinpaymentWithdrawal;
use App\Jobs\CoinpaymentListener;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use Exception;


/**
 * Class CoinpaymentsService.
 */
class CoinpaymentsService
{

    protected $model, $withdrawal;

    public function __construct(CoinpaymentTransaction $model, CoinpaymentWithdrawal $withdrawal) {
        $this->model = $model;
        $this->withdrawal = $withdrawal;
    }

    public function create_transaction($amount, $item, $request, $order, $user = null)
    {
        try {
            DB::beginTransaction();

            if (empty($amount)) {
                throw new Exception('Amount total not found!');
            }

            if (empty($order->id)) {
                throw new Exception('Order ID cannot be null, please fill it with invoice number or other');
            }

            $check_transaction = $this->model->where('order_id', $order->id)->whereNotNull('txn_id')->first();

            if ($check_transaction) {
                throw new Exception('Order ID: ' . $check_transaction->order_id . ' already exists, and the current status is ' . $check_transaction->status_text);
            }

            $data = [
                'amount' => $amount,
                'currency1' => config('coinpayment.default_currency'),
                'currency2' => config('coinpayment.default_currency'),
                'buyer_email' => isset($request->user()->email) ? $request->user()->email : $request->email,
                'buyer_name' => isset($request->user()->name) ? $request->user()->name. ' '. $request->user()->last_name: $user->name . ' '. $user->last_name,
                'item_name' => $item->product_name,
            ];

            $create = $this->api_call('create_transaction', $data);
            if ($create['error'] != 'ok') {
                throw new Exception($create['error']);
            }

            $info = $this->api_call('get_tx_info', ['txid' => $create['result']['txn_id']]);
            if ($info['error'] != 'ok') {
                throw new Exception($info['error']);
            }
            $result = array_merge($create['result'], $info['result'], [
                'order_id' => $order->id,
                'amount_total_fiat' => $amount,
                'payload' => $request->payload,
                'buyer_name' => $request->user()->name . ' ' . $request->user()->last_name ?? '-',
                'buyer_email' => $request->user()->email ?? '-',
                'currency_code' => config('coinpayment.default_currency'),
            ]);

            /**
             * Save to database
             */
            $transaction = $this->model->whereNull('txn_id')->where('order_id', $order->id)->first();
            if ($transaction) {
                /**
                 * Update existing transaction
                 */
                $transaction->update($result);
            } else {

                /**
                 * Create new transaction
                 */
                $transaction = $this->model->create($result);
                /**
                 * Create item transaction
                 */


            }

            /**
             * Dispatching job
             */
            dispatch(new CoinpaymentListener(array_merge($result, [
                'transaction_type' => 'new'
            ])));

            DB::commit();
            return $result;

        } catch (Exception $e) {
            DB::rollback();
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }

    }

    public function get_info($txn_id) {
		try {
			 $status = $this->api_call('get_tx_info', ['txid' => $txn_id]);
			if($status['error'] != 'ok') {
				throw new \Exception($status['error']);
			}

			return (Array) $status['result'];

		} catch (\Exception $e) {
			return $e->getMessage();
		}
	}

    public function create_withdrawal($amount, $address, $liquidationID)
    {
        try {
            DB::beginTransaction();

            if (empty($amount)) {
                throw new Exception('Amount total not found!');
            }

            if (empty($address)) {
                throw new Exception('address not found');
            }

            $data = [
                'amount' => $amount,
                'currency' => config('coinpayment.default_currency'),
                'currency2' => config('coinpayment.default_currency'),
                'address' => $address,
                'auto_confirm' => 1,
                'note' => 'Enviando $ '.$amount.' USDT a la billetera: '.$address ,
            ];

            $create = $this->api_call('create_withdrawal', $data);
            if ($create['error'] != 'ok') {
                throw new Exception($create['error']);
            }

            $info = $this->api_call('get_withdrawal_info', ['id' => $create['result']['id']]);

            if ($info['error'] != 'ok') {
                throw new Exception($info['error']);
            }
            $result = array_merge($create['result'], $info['result'],[
                'tx_id' => $create['result']['id'],
                'liquidation_id' => $liquidationID,
            ]);

            /**
             * Save to database
             */
            $transaction = $this->withdrawal->whereNull('tx_id')->where('liquidation_id', $liquidationID)->first();
            if ($transaction) {
                /**
                 * Update existing transaction
                 */
                $transaction->update($result);
            } else {

                /**
                 * Create new transaction
                 */
                $transaction = $this->withdrawal->create($result);

            }

            /**
             * Dispatching job
             */
            DB::commit();
            return response()->json($result, 200);

        } catch (Exception $e) {
            DB::rollback();
            Log::error($e);
            return response()->json([
                'status' => 'error',
                'msj' => $e->getMessage(),
            ], 400);
        }

    }

    public function get_balances() {
        try {
            $status = $this->api_call('balances');
           if($status['error'] != 'ok') {
               throw new \Exception($status['error']);
           }

           return (Array) $status['result'];

       } catch (\Exception $e) {
           return $e->getMessage();
       }
    }

    public function api_call($cmd, $req = array())
    {
        $public_key   = config('coinpayment.public_key');
        $private_key  = config('coinpayment.private_key');

        $url = 'https://www.coinpayments.net/';
        $req['cmd'] = $cmd;
        $req['version'] = 1;
        $req['key'] = $public_key;
        $req['format'] = 'json';


        $post_data = http_build_query($req, '', '&');
        $hmac = hash_hmac('sha512', $post_data, $private_key);
        $client = new Client(['base_uri' => $url]);

        $rsult = $client->request('POST', 'api.php', [
            'headers'        => ['Content-Type' => 'application/x-www-form-urlencoded',
                                'X-Requested-With' => ' MLHttpRequest',
                                'HMAC' => $hmac,
            ],
            'form_params'          => $req
        ]);

        $resp = (Array) json_decode(stripslashes($rsult->getBody()->getContents()));

     if($resp['error'] != 'ok'){
        throw new Exception($resp['error']);
        }

        if($resp['error'] == 'ok'){
            $r = $resp;
            $error = ['error' => $r['error']];
            $result = ['result' => (array) $r['result']];
            $response = array_merge($error, $result);
            return $response;

         }

         return $resp;

    }

}
