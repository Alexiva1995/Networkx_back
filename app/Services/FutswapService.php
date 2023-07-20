<?php

namespace App\Services;

use App\Models\Environment;
use App\Models\FutswapTransaction;
use App\Models\Order;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FutswapService
{
    private $futswap;

    const  COINSYMBOL = "USDT";
    const  NETWORK = "TRON";

    public function __construct(FutswapTransaction $futswap = null)
    {
        $this->futswap = $futswap;
    }

    public function createOrden($user, $amount, $ordenId)
    {
        $token = Environment::first();
        $token = Crypt::decrypt($token->futswap_token);
        $url = config('futswap.apiUrl');
        $secret = $this->generateKey();
        $response = Http::withHeaders([
            'x-api-key' => $token,
        ])->post("{$url}/bill", [
            'companyId' => config('futswap.companyId'),
            'usdValue' => $amount,
            'coinSymbol' => self::COINSYMBOL,
            'network' => self::NETWORK,
            'customerId' => strval($user->id),
            'customerUserName' => $user->name,
            'secret' => $secret,
            'externalTxId' => strval($ordenId),
        ]);
        if($response->successful())
        {
            $json = $response->json();
            $json['data']['order_id'] = $ordenId;
            $json['data']['recoveryFeeTransaction'] = null;
            $json['data']['transactionToMasterWallet'] = null;


            $futswap_transaction = $this->futswap->storeFutswapTransaction($json['data']);
            return $futswap_transaction->paymentUrl;
            // return $this->updateHash($futswap_transaction, $ordenId);


        }else{

            Log::info('Error Futswap- '.$response);
            $order = Order::where('id', $ordenId)->first();
            $order->delete();
            $error = ['error'];
            $error[] = $response->json()['message'];
            return $error;
        }
    }

    public function checkStatusFutswap()
    {
        $transactions = FutswapTransaction::where('status', 'PENDING_PAYMENT')->get();
        foreach ($transactions as $trans) {
            $this->getBillStatus($trans->billId);
        }
    }

    private function getBillStatus($billId) {
        $token = Environment::first();
        $token = Crypt::decrypt($token->futswap_token);
        $url = config('futswap.apiUrl');
        $response = Http::withHeaders([
            'x-api-key' => $token,
        ])->get("{$url}/bill/status", [
            'companyId' => config('futswap.companyId'),
            'billId' => $billId,
        ]);
        if($response->successful())
        {
            $data = $response->json();
            return $this->updateStatusCanceled($data['data']);

        }else{
            $error = ['error'];
            $error[] = $response->json()['message'];
            return $error;
        }
    }

    private function updateHash($transaction, $ordenId)
    {
        Order::where(['id' => $ordenId])->update(['hash' => $transaction['billId']]);
        return $transaction->paymentUrl;
    }

    private function generateKey()
    {
        do {
            $token = Str::uuid();
        } while (FutswapTransaction::where("secret", $token)->first() instanceof FutswapTransaction);

        return $token;
    }

    private function updateStatusCanceled($data)
    {

        if ($data['status'] == 'CANCELED') {
            $transaction = FutswapTransaction::where('billId', $data['billId'])->first();
            $transaction->status = $data['status'];
            $transaction->update();
            $transaction->orderPurchase->status = "3";
            $transaction->orderPurchase->save();
        }
    }

   public function withdrawal($user, $amount)
   {

        $url = config('futswap.apiUrl');
        $object = new \stdClass();
        $object->userId = strval($user->id);
        $object->username = $user->name;
        $object->secret = $this->generateKey();
        $object->address = $user->decryptWallet();
        $object->amount = $amount;
        $token = Environment::first();
        $token = Crypt::decrypt($token->futswap_token);

        $response = Http::withHeaders([
            'x-api-key' => $token,
        ])->post("{$url}/wth/massive", [
            'companyId' => config('futswap.companyId'),
            'value' => $amount,
            'coinSymbol' => self::COINSYMBOL,
            'network' => self::NETWORK,
            'data' => [$object]
        ]);

        if($response->successful())
        {
            $data = $response->json();
            $jsonData = $data['data'];
            $jsonData['secret'] = $object->secret;
            $json = [['success'], $jsonData];
            return $json;

        }else{
            Log::info('Error Withdrawal Futswap- '.$response);
            $error = ['error'];
            $error[] = $response->json()['message'];
            return $error;
        }
    }
}
