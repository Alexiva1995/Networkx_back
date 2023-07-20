<?php

namespace App\Services;

use App\Models\Order;
use App\Models\PagueloFacilTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Class PagueloFacilService.
 */
class PagueloFacilService
{
    public function makeTransaction($user_id, $order_id, $amount = 80)
    {
        try {
            $url_base = config('services.paguelo_facil.url');

            $array_data = [
                [
                    'id' => 'XTRN_OR_ID',
                    'nameOrLabel' => 'xtrn_or_id',
                    'type' => 'hidden',
                    'value' => strval($order_id)
                ],
                [
                    'id' => 'XTRN_US_ID',
                    'nameOrLabel' => 'xtrn_us_id',
                    'type' => 'hidden',
                    'value' => strval($user_id)
                ],
            ];

            $order = Order::findOrFail($order_id);

            $description = "Purchase of {$order->packageMembership->getTypeName()} {$order->packageMembership->account} account";

            $data = [
                "CCLW" => config('services.paguelo_facil.cclw'),
                "CMTN" => $amount,
                "CDSC" => $description,
                // "RETURN_URL" => '68747470733A2F2F70616775656C6F666163696C73612E7A656E6465736B2E636F6D2F6167656E742F66696C746572732F3439313933393538',
                'PF_CF' => bin2hex(json_encode($array_data)),
                'CARD_TYPE' => 'CARD,CRYPTO',
                // "PARM_1" => '19816201',
                "EXPIRES_IN" => 900, // 15 minutes
            ];

            $postR = "";

            foreach ($data as $mk => $mv) {
                $postR .= "&" . $mk . "=" . $mv;
            }

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "$url_base/LinkDeamon.cfm");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_AUTOREFERER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', 'Accept: */*'));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postR);

            $res = json_decode($result = curl_exec($ch));
            if($res->success) {
                $this->store($amount, $user_id,$order_id, $res);
                $order->update(['hash' => $res->data->code]);
                return $res->data->url;
            }

            new Exception($result);

        } catch (\Throwable $th) {
            Log::error('Fallo en pagueloFacil');

            Log::error($th);

            $order = Order::where('id', $order_id)->delete();

            $error = ['error'];

            $error[] = 'An error has occurred please contact support';

            return $error;
        }
    }

    public function store($amount, $user_id, $order_id, $res)
    {
        try {
            DB::beginTransaction();
            // data for pending status 
            $data = [
                'user_id' => $user_id,
                'order_id' => $order_id,
                'amount' => $amount,
                'code' => $res->data->code,
                'status' => '2',
                'return_url' => $res->data->url,
                'expiration_time' => now()->addMinutes(15),
            ];

            PagueloFacilTransaction::create($data);

            DB::commit();

        } catch (\Throwable $th) {
            Log::info("Error creando registro de paguelo Facil Transaction");
            Log::error($th);
            DB::rollback();
        }
    }

    public function cancelTransactions()
    {
        try {

            $transactions = PagueloFacilTransaction::whereIn('status', ['2','3'])->get();

            foreach($transactions as $transaction)
            {
                if( now() > $transaction->expiration_time ) {
                   $transaction->update(['status' => '0']);
                   $transaction->order->status = "3";
                   $transaction->order->save();
                }
            }
            
        } catch (\Throwable $th) {
            Log::error('Error al actualizar transaciones de PagueloFacil');
            Log::error($th);
        } 
    }
}
