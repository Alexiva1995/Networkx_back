<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class BrokereeService
{
    public function getToken($isAccountReal)
    {
        $url = config('services.brokeree.url');
        try {
            $response = Http::asForm()->post("{$url}/oauth2/token", [
                'username' => $isAccountReal ? config('services.brokeree.username_real'): config('services.brokeree.username_demo'),
                'password' => $isAccountReal ? config('services.brokeree.password_real'): config('services.brokeree.password_demo'),
                'grant_type' => 'password'
            ]);

            $res = $response->object();

            if (!$response->successful()) {
                Log::error('Fallo en brokeree - unsuccessfull');
                Log::error($response);

                return ['error' => true, 'message' => 'token no response', 'res' => $response];
            }

            return $res->access_token;
        } catch (\Throwable $th) {
            Log::error('Fallo en brokeree');
            Log::error($th);

            return ['error' => true, 'message' => $th->getMessage()]; ;
        }
    }

    public function getUsers($isAccountReal)
    {
        $token = $this->getToken($isAccountReal);

        if (!is_null($token) && isset($token['error'])) {
            return ['error' => true, 'message' => $token['message']];
        } 

        $url = config('services.brokeree.url');
        try {
            $response = Http::withToken($token)->asForm()->get("{$url}/users");

            if (!$response->successful()) {
                return ['error' => true, 'message' => $response['Message']];
            }

            $res = $response->object();
            return ['success' => true, 'data' => $res->data];
        } catch (\Throwable $th) {
            Log::error('Fallo en brokeree');
            Log::error($th);
            
            return ['error' => true, 'message' => $th->getMessage()];
        }
    }

    public function getAccount($login = NULL, $isAccountReal = FALSE) {

        if (is_null($login)) {
            return ['error' => true, 'message' => 'no login provided'];
        }

        $token = $this->getToken($isAccountReal);

        if (!is_null($token) && isset($token['error'])) {
            return ['error' => true, 'message' => $token['message']];
        } 

        $url = config('services.brokeree.url');
        try {
            $response = Http::withToken($token)->asForm()->get("{$url}/accounts/{$login}");

            if (!$response->successful()) {
                return ['error' => true, 'message' => $response['Message']];
            }

            $res = $response->object();
            return ['success' => true, 'data' => $res];
        } catch (\Throwable $th) {
            Log::error('Fallo en brokeree');
            Log::error($th);
            
            return ['error' => true, 'message' => $th->getMessage()];
        }
    }

    public function getUser($login = NULL, $isAccountReal = FALSE) {

        if (is_null($login)) {
            return ['error' => true, 'message' => 'no login provided'];
        }

        $token = $this->getToken($isAccountReal);

        if (!is_null($token) && isset($token['error'])) {
            return ['error' => true, 'message' => $token['message']];
        } 

        $url = config('services.brokeree.url');
        try {
            $response = Http::withToken($token)->asForm()->get("{$url}/users/{$login}", ['forceRequest' => true]);

            if (!$response->successful()) {
                Log::error('Fallo en brokeree - getUser');
                Log::error($response);

                return ['error' => true, 'message' => $response['Message']];
            }

            $res = $response->object();
            return ['success' => true, 'data' => $res];
        } catch (\Throwable $th) {
            Log::error('Fallo en brokeree - getUser', [$th]);
            
            return ['error' => true, 'message' => $th->getMessage()];
        }
    }

    private function getFirstOrder($token, $login, $type = 'closed', $from = '2020-01-01', $to = '2025-01-01') {
        $url = config('services.brokeree.url');
        $response = Http::withToken($token)->asForm()
            ->get("{$url}/orders", [
                'login' => $login,
                'from' => $from,
                'to' => $to,
                'type' => $type,
            ]);
        
        if (!$response->successful()) {
            Log::error('Fallo en brokeree - getFirstOrder');
            Log::error([
                'login' => $login,
                'from' => $from,
                'to' => $to,
                'type' => $type,
            ]);

            return null;
        }

        $res = $response->object();

        return isset($res->data) && count($res->data) > 0 ? $res->data[0] : null;
    }

    public function getFirstTrade($login = NULL, $startDate = '2020-01-01', $isAccountReal = FAlSE) {

        if (is_null($login)) {
            return ['error' => true, 'message' => 'no login provided'];
        }

        $token = $this->getToken($isAccountReal);

        if (!is_null($token) && isset($token['error'])) {
            return ['error' => true, 'message' => $token['message']];
        } 

        try {
            $userResponse = $this->getUser($login, $isAccountReal);

            if(isset($userResponse['error'])) {
                Log::error('Fallo en brokeree - getFirstTrade - catch 1', [$userResponse]);
                
                return ['error' => true, 'message' => 'no puede encontrar usuario'];
            }

            Log::info('getting closed');
            $closed = $this->getFirstOrder($token, $login, 'Closed', $startDate, date("Y-m-d"));
            Log::info('getting opened');
            $opened = $this->getFirstOrder($token, $login, 'Opened', $startDate, date("Y-m-d"));

            if (is_null($opened) && is_null($closed)) {
                Log::info('no orders found');
                return [ 'data' => null];
            }

            if(is_null($opened)) {
                Log::info('getting closed');
                return ['data' => date("Y-m-d", $closed->timeSetup)];
            }
            
            if(is_null($closed)) {
                Log::info('getting opened');

                return ['data' => date("Y-m-d", $opened->timeSetup)];
            }

            if($opened->timeSetup < $closed->timeSetup) {
                Log::info('getting opened');

                return ['data' => date("Y-m-d", $opened->timeSetup)];
            }

            Log::info('getting closed');
            return ['data' => date("Y-m-d", $closed->timeSetup)];
        } catch (\Throwable $th) {
            Log::error('Fallo en brokeree - getFirstTrade - catch 2', [$th]);
            
            return ['error' => true, 'message' => $th->getMessage()];
        }
    }

    /**
     * Crea un user de MT5 a partir de informacion de usuario:
     *  - tradingUserInfo: informacion de usuario, contiene propiedades string: group, rights, name, country, eMail, leverage, firstName, lastName
     */
    public function createUser($tradingUserInfo = NULL, $masterPassword = NULL, $investorPassword = NULL, $isAccountReal)
    {

        if (is_null($tradingUserInfo)) {
            return ['error' => true, 'message' => 'no tradingUserInfo provided'];
        }

        if (is_null($masterPassword)) {
            return ['error' => true, 'message' => 'no masterPassword provided'];
        }

        if (is_null($investorPassword)) {
            return ['error' => true, 'message' => 'no investorPassword provided'];
        }

        $token = $this->getToken($isAccountReal);

        if (!is_null($token) && isset($token['error'])) {
            return ['error' => true, 'message' => $token['message']];
        }

        $url = config('services.brokeree.url');
        try {
            $response = Http::withToken($token)->asJson()->post("{$url}/users", [
                'user' => $tradingUserInfo,
                'masterPassword' => $masterPassword,
                'investorPassword' => $investorPassword,
            ]);

            if (!$response->successful()) {
                Log::info("Create MT5 User Response not successful", [$response, $tradingUserInfo, $investorPassword]);
                return null;
            }

            $res = $response->object();
            return ['success' => true, 'data' => $res];
        } catch (\Throwable $th) {
            Log::error('Fallo en brokeree');
            Log::error($th);

            return ['error' => true, 'message' => $th->getMessage()];
        }
    }


    public function getDeals($loginId = NULL, $dateFrom = NULL, $dateTo = NULL, $isAccountReal = true) {

        if (is_null($loginId)) {
            return ['error' => true, 'message' => 'no loginId provided'];
        } 

        if (is_null($dateFrom)) {
            return ['error' => true, 'message' => 'no dateFrom provided'];
        }

        if (is_null($dateTo)) {
            return ['error' => true, 'message' => 'no dateTo provided'];
        }

        $token = $this->getToken($isAccountReal);

        if (!is_null($token) && isset($token['error'])) {
            return ['error' => true, 'message' => $token['message']];
        }

        $url = config('services.brokeree.url');
        try {
            $response = Http::withToken($token)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->get("{$url}/deals?login={$loginId}&from={$dateFrom}&to={$dateTo}");

            if (!$response->successful()) {
                Log::error('Fallo en brokeree - unsuccessfull deals', [$response]);

                return ['error' => true, 'message' => 'error buscando deals'];
            }

            $res = $response->object();
            
            $out = array_filter(
                $res->data,
                function ($item) {
                    return $item->entry === 'ENTRY_OUT';
                }
            );

            $group = [];
            foreach ($out as $item) {
                $item->time = date("Y-m-d", $item->time);
                
                if(isset($group[$item->time])) {

                    $group[$item->time]["trades"]++;
                    $group[$item->time]["lots"]+= $item->volumeLots;
                    $group[$item->time]["result"]+= $item->profit;

                } else {
                    $group[$item->time] = [ "trades" => 1, "lots" => $item->volumeLots, "result" => $item->profit];
                }
            }
            $summary = [];
            foreach ($group as $key => $value) {
                $summary[] = array_merge(["date" => $key], $value);
            }

            return ['success' => true, 'data' => $summary];
        } catch (\Throwable $th) {
            Log::error('Fallo en brokeree');
            Log::error($th);
            return ['error' => true, 'message' => $th->getMessage()];
        }
    }

    /**
     * Add balance to a user's account.
     *
     * @param string|null $loginId The login ID of the user.
     * @param float|null $balance The balance to add.
     *
     * @return array|null An array with the result of the operation.
     */
    public function addBalance($loginId = NULL, $balance = NULL, $isAccountReal)
    {

        if (is_null($loginId)) {
            return ['error' => true, 'message' => 'no loginId provided'];
        }

        if (is_null($balance)) {
            return ['error' => true, 'message' => 'no balance provided'];
        }

        $token = $this->getToken($isAccountReal);

        if (!is_null($token) && isset($token['error'])) {
            return ['error' => true, 'message' => $token['message']];
        }

        $url = config('services.brokeree.url');
        try {
            $response = Http::withToken($token)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->post("{$url}/dealer/commands/balance", [
                    'login' => $loginId,
                    'value' => $balance,
                    'type' => "DEAL_BALANCE",
                    'comment' => "FYT - Deposit"
                ]);

            if (!$response->successful()) {
                return null;
            }

            $res = $response->object();
            return ['success' => true, 'data' => $res];
        } catch (\Throwable $th) {
            Log::error('Fallo en brokeree');
            Log::error($th);

            return ['error' => true, 'message' => $th->getMessage()];
        }
    }

}
