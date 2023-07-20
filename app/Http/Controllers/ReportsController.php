<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Liquidaction;
use App\Models\Order;
use App\Models\PagueloFacilTransaction;
use App\Models\Project;
use App\Models\WalletComission;
use App\Models\Prefix;
use App\Models\PackageMembership;
use App\Models\Formulary;
use App\Services\BonusService;
use App\Services\BrokereeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

use App\Http\Requests\FormularyStoreRequest;
// use App\Traits\FormularyCreateTrait;

class ReportsController extends Controller
{
    protected $bonusService;

    // use FormularyCreateTrait;

    public function __construct(BonusService $bonusService)
    {
        $this->bonusService = $bonusService;
    }

    public function updateOrders(Request $request)
    {
        Log::info($request->all());

        $request = json_decode(json_encode($request->all()));

        $data = [
            'order_id' => $request->customFields->xtrn_or_id,
            'user_id' => $request->customFields->xtrn_us_id,
            'status' => $request->status,
            'total_pay' => $request->totalPay,
            'request_pay_amount' => $request->requestPayAmount,
            'operation_code' => $request->codOper,
            'display_num' => isset($request->displayNum) ? $request->displayNum : null,
            'date' => $request->date,
            'operation_type' => $request->operationType,
            'return_url' => $request->returnUrl
        ];

        // try {
        // DB::beginTransaction();
        // DB::transaction(function () use ($data, $request) {

        Log::info("Entrando a la transacción", [$data]);

        $order = null;
        try {
            $order = Order::findOrFail($data['order_id']);
        } catch (\Throwable $th) {
            Log::info("Error finding order");
            Log::error($th);
        }

        if ($data['status'] == 1) {
            $order->status = '1';
            $order->hash = $data['operation_code'];
            if ($order->user->affiliate == '0') {
                $order->user->affiliate = '1';
            }
            $order->user->status = '1';
            $order->user->save();
        } else {
            $order->status = '3';
            $order->hash = $data['operation_code'];
        }

        $order->save();

        $pagueloFacilTransaction = null;
        try {
            $pagueloFacilTransaction = PagueloFacilTransaction::where('order_id', $order->id)->first();
        } catch (\Throwable $th) {
            Log::info("Error finding order paguelofacil transaction");
            Log::error($th);
        } 
        
        Log::info("Actualizada PagueloFacilTransaction order");

        $pagueloFacilTransaction->fill($data);

        $pagueloFacilTransaction->save();

        //Inicializando las variables que deben mandarse en el email de credenciales y deben persistir el if 
        // $project = null;
        // $package = null;
        // $mtCompleteUserName = null;
        // $masterPassword = null;
        // $createTradingUserResponse = null;
        // $serverName = null;

        //Si la orden fué pagada en su totalidad
        if ($data['status'] == 1) {
            $projectStatus = ($order->membership_packages_id < 5 && $order->membership_packages_id > 7)
                ? 2
                : 0;

            Log::info("Creando nueva entrada en la tabla projects", [$order->id, $order->amount, $projectStatus]);

            $project = null;
            try {
                //Almacena lo que retorna el objeto project en una variable
                $project = Project::create([
                    'order_id' => $order->id,
                    'amount' => $order->amount,
                    'status' => $projectStatus,
                    'phase1' => 1,
                ]);
            } catch (\Throwable $th) {
                Log::info("Error creating Project");
                Log::error($th);
            } 

            Log::info("Creada nueva entrada en la tabla projects", [$project]);

            $this->bonusService->directBonus($order->user, $order->amount, $order->user_id, $order);

            Log::info("Actualizando el objeto project de la base de datos con la data generada");

            Log::info("Buscando el objeto package membership a partir del membership_packages_id de la orden");

            $package = null;
            try {
                $package = PackageMembership::find($order->membership_packages_id);
            } catch (\Throwable $th) {
                Log::info("Error finding package membership");
                Log::error($th);
            }

            Log::info("Respuesta objeto package membership", [$package]);

            Log::info("Instanciando brokeree service");
            $brokeree = new BrokereeService();

            // Si el tipo de paquete es ACCELERATED (type == 3)
            // Si el tipo de paquete es EVALUATION (type == 1) o FAST (type == 2)
            // $package->type == 1 || $package->type == 2

            $packageTypeName = match ((string)$package->type) {
                '1'  => 'EVALUATION',
                '2' => 'FAST',
                '3' => 'ACCELERATED',
                '4' => 'FLASH',
            };

            $groupName = match ((string)$package->type) {
                '1' => 'demo\\EUROSTREET Capital\\RAW_USD',
                '2' => 'demo\\EUROSTREET Capital\\RAW_USD',
                '3' => 'real\\EUROSTREET Capital Unique\\FYTFUNDING_USD_B',
                '4' => 'demo\\EUROSTREET Capital\\RAW_USD',
            };

            $isAccountReal = null;

            if ($package->type == 3) {
                $isAccountReal = true;
            }

            $mtName = 'FYT - ' . $packageTypeName;
            $mtCompleteUserName = $mtName . ' ' . $order->user->name . ' ' . $order->user->last_name;

            $userPrefix = null;
            try {
                $userPrefix = Prefix::findOrFail($order->user->prefix_id);
            } catch (\Throwable $th) {
                Log::info("Error finding user country prefix");
                Log::error($th);
            }

            $userCountry = $userPrefix->pais;
            Log::info("Inicializando objeto tradingUserInfo");
            $tradingUserInfo = [
                'group' => $groupName,
                'rights' => "USER_RIGHT_ENABLED, USER_RIGHT_PASSWORD, USER_RIGHT_TRAILING, USER_RIGHT_EXPERT, USER_RIGHT_REPORTS, USER_RIGHT_OTP_ENABLED",
                'name' => $mtCompleteUserName,
                'country' => $userCountry,
                'eMail' => $order->user->email,
                'leverage' => substr($package->available_Leverage, 2),
                'firstName' => $mtName,
                'lastName' => $order->user->name,
                //removing the last 3 characters of the balance to transform it to K notation
                'company' => substr((string) (intval($package->account)), 0, -3). 'K',
                'comment' => $mtName,
            ];

            Log::info("LEVERAGE", [(int)substr($package->available_Leverage, 2)]);

            //Generar passwords min 2 letters and 2 digits
            function passwordGenerator($length)
            {
                $password = '';
                $numberCharset = '0123456789';
                $letterCharset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $generalCharset = $numberCharset . $letterCharset;
                $numbersCount = 0;
                $lettersCount = 0;

                while ($numbersCount < 2) {
                    $random_char = $numberCharset[mt_rand(0, strlen($numberCharset) - 1)];
                    $password .= $random_char;
                    $numbersCount++;
                }

                while ($lettersCount < 2) {
                    $random_char = $letterCharset[mt_rand(0, strlen($letterCharset) - 1)];
                    $password .= $random_char;
                    $lettersCount++;
                }

                for ($i = $numbersCount + $lettersCount;
                    $i < $length;
                    $i++
                ) {
                    $random_char = $generalCharset[mt_rand(0, strlen($generalCharset) - 1)];
                    $password .= $random_char;
                }

                return $password;
            }

            $masterPassword = passwordGenerator(10);
            $investorPassword = passwordGenerator(10);
            Log::info("TradinUserInfo scan", [$tradingUserInfo]);
            Log::info("Masterpassword scan", [$masterPassword]);
            Log::info("Investorpassword scan", [$investorPassword]);

            $createTradingUserResponse = null;
            $addBalanceResponse = null;
            try {
                //llamar a método de creación de cuentas de mt5
                Log::info("Brokeree create user");

                $createTradingUserResponse = $brokeree->createUser($tradingUserInfo, $masterPassword, $investorPassword, $isAccountReal);

                Log::info("Create user respuesta: ", [$createTradingUserResponse]);
                Log::info("Create user login: ", [$createTradingUserResponse['data']->login]);

                $addBalanceResponse = $brokeree->addBalance($createTradingUserResponse['data']->login, $package->account, $isAccountReal);
                Log::info("Add balance respuesta: ", [$addBalanceResponse]);
            } catch (\Throwable $th) {
                Log::error('Error creating account with balance');
                $adminDataMail = [
                    'email' => $order->user->email,
                    'date' => now()->format('Y-m-d'),
                    'user' => $order->user->fullName(),
                    'program' => $order->packageMembership->getTypeName(),
                ];
    
                Mail::send('mails.mtAccountCreationFailed', ['data' => $adminDataMail],  function ($msj) {
                    $msj->subject('Auto account creation failed!');
                    $msj->to('admin@fyt.com');
                });
                Log::error($th);
            }

            $serverName = 'EUROSTREETCapital-Server / MetaTrader 5';
            $masterPasswordEncrypted = Crypt::encryptString($masterPassword);

            Log::info("Inicializando el objeto request para crear formulario");
            // $formularyRequest = new FormularyStoreRequest([
            // $formularyRequest = [
            //     'project_id' => $project->id,
            //     'name' => $mtCompleteUserName,
            //     //obtener login y password desde MT5 API
            //     'login' => (string)$createTradingUserResponse['data']->login,
            //     'password' => $masterPasswordEncrypted,
            //     //obtener leverage y balance desde el package que compró
            //     'leverage' => $package->available_Leverage,
            //     'balance' => $package->account,
            //     'server' => $serverName,
            //     'date' => $data['date'],
            // ];

            $formatBalance = intval($package->account);
            // Log::info("objeto request para crear formulario", [$formularyRequest]);
            Log::info("variables para crear formulario", [
                'project_id' => $project->id,
                'name' => $mtCompleteUserName,
                'login' => (string)$createTradingUserResponse['data']->login,
                'password' => $masterPasswordEncrypted,
                'leverage' => $package->available_Leverage,
                'balance' => $formatBalance,
                'server' => $serverName,
                'date' => $data['date'],
            ]);

            $formulary = null;
            try {
                $formulary = Formulary::create([
                    'project_id' => $project->id,
                    'name' => $mtCompleteUserName,
                    'login' => (string)$createTradingUserResponse['data']->login,
                    'password' => $masterPasswordEncrypted,
                    'leverage' => $package->available_Leverage,
                    'balance' => $formatBalance,
                    'server' => $serverName,
                    'date' => $data['date'],
                ]);
            } catch (\Throwable $th) {
                Log::info("Error finding creating formulary");
                Log::error($th);
            }

            Log::info("Objeto respuesta de creacion de formulary", [$formulary]);
            // $formularyRequest = new FormularyStoreRequest([
            //     'project_id' => 90,
            //     'name' => 'FYT - EVALUATION Program Kendall Kant',
            //     'login' => '2100244261',
            //     'password' => 'eyJpdiI6Ikt3V05NMTFXQ3pBOTlzNWlTajhGUFE9PSIsInZhbHVlIjoiV0NFT3pteGVpbnRVaUlyMEZpYWtuQT09IiwibWFjIjoiOWExNmY1MmMzYjUwNjc0MmYyNjc2ZjA1YWU5MDVjOWM5ZDUyM2Y5NzI3ZTZhZmYxNDI4OTBmZjVmYWQ4ZTI5MCIsInRhZyI6IiJ9',
            //     'leverage' => '1:100',
            //     'balance' => 10000,
            //     'server' => 'EUROSTREETCapital-Server / MetaTrader 5',
            //     'date' => '2023-05-08',
            // ]);
            // Formulary::create($formularyRequest);
            // $formulary->save();

            // $response = $this->formularyCreate($formularyRequest);
        }

        // DB::commit();
        Log::info("La orden {$order->id} y transacción {$pagueloFacilTransaction} han sido actualizadas correctamente");

        if ($data['status'] == 1) {
            try {

                Log::info("Inicializando dataemail");
                $dataEmail = [
                    'user' => $order->user->fullName(),
                    'name' => $mtCompleteUserName,
                    'login' => $createTradingUserResponse['data']->login,
                    'password' => $masterPassword,
                    'leverage' => $package->available_Leverage,
                    'balance' => $package->account,
                    'server' => $serverName,
                    'date' => $request->date
                ];

                Mail::send('mails.sendCredentials',  ['data' => $dataEmail], function ($msj) use ($project) {
                    $msj->subject('Project Credentials.');
                    $msj->to($project->order->user->email);
                });

                $adminDataMail = [
                    'email' => $order->user->email,
                    'date' => now()->format('Y-m-d'),
                    'user' => $order->user->fullName(),
                    'program' => $order->packageMembership->getTypeName(),
                ];

                Mail::send('mails.approvedOrder', ['data' => $adminDataMail],  function ($msj) use ($order) {
                    $msj->subject('Order Approved!');
                    $msj->to('admin@fyt.com');
                });

                $dataEmail = [
                    'user' => $order->user->fullName(),
                    'program' => $order->packageMembership->getTypeName(),
                ];
                Mail::send('mails.approvedOrder',  ['data' => $dataEmail], function ($msj) use ($order) {
                    $msj->subject('Order Approved.');
                    $msj->to($order->user->email);
                });
            } catch (\Throwable $th) {
                Log::info("Error sending approved order mail");
                Log::error($th);
            }
        }
        // });
        // } catch (\Throwable $th) {
        //     DB::rollback();
        //     Log::error('Error updating the order from PageloFacilHook');
        //     Log::error($th);
        // }
    }
    public function commision()
    {
        $comisions = WalletComission::with(['user' => function ($query) {
            $query->select('id', 'email', 'name', 'user_name');
        }])
            ->with(['buyer' => function ($query) {
                $query->select('id', 'email', 'name', 'last_name');
            }])
            ->with(['order' => function ($query) {
                $query->select('id', 'amount');
            }])
            ->with('package')
            ->where('type', '!=', 3)
            ->orderBy('id', 'desc')->get();

        return response()->json($comisions, 200);
    }
    public function refund()
    {
        $refund = WalletComission::with('user')->with('package')->where('type', 3)->get();

        return response()->json($refund, 200);
    }
    public function filterComissionList(Request $request)
    {

        $query = WalletComission::with(['user' => function ($query) {
            $query->select('id', 'email', 'name', 'user_name');
        }])
            ->with(['buyer' => function ($query) {
                $query->select('id', 'email', 'name', 'last_name');
            }])
            ->with(['order' => function ($query) {
                $query->select('id', 'amount');
            }])
            ->with('package');
        $params = false;
        if ($request->has('email') && $request->email !== null) {
            $email = $request->email;
            $query->whereHas('user', function ($q) use ($email) {
                $q->where('email', $email);
            });
            $params = true;
        }

        if ($request->has('status') && $request->status !== null && $request->status != 'All') {
            $query->where('status', $request->status);
            $params = true;
        }

        $comission = $query->get();


        if (!$comission || !$params) {
            return response()->json($comission, 200);
        }
        return response()->json($comission, 200);
    }
    public function liquidaction()
    {
        $liquidactions = Liquidaction::with('user')->get();

        return response()->json($liquidactions, 200);
    }

    public function coupons()
    {
        $coupons = Coupon::with('user')->get();

        return response()->json($coupons, 200);
    }
}
