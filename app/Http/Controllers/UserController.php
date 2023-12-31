<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\OrdersResource;
use App\Mail\CodeSecurity;
use App\Models\Liquidaction;
use App\Models\User;
use App\Models\Prefix;
use App\Models\ProfileLog;
use App\Models\WalletComission;
use App\Models\Order;
use App\Models\Formulary;
use App\Models\Inversion;
use App\Models\ReferalLink;
use App\Repositories\OrderRepository;
use App\Rules\ChangePassword;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\Services\BrokereeService;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Illuminate\Filesystem\Filesystem;

class UserController extends Controller
{
    protected $orderRepository;

    public function __construct(OrderRepository $orderRepository) {
        $this->orderRepository = $orderRepository;
    }

    public function showReferrals()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $referrals = $this->getReferrals($user);

        return response()->json($referrals, 200);
    }

    public function listReferrals()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $referrals = $this->getReferrals($user);

        $referralList = $referrals->map(function ($referral) {
            $buyerUser = User::find($referral['buyer_id']);
            $buyerName = $buyerUser ? $buyerUser->name : '';

            $user = User::find($referral['id']);
            $plan = $user ? $user->matrix_type : '';

            // Si el plan es nulo, asignarle el valor 20
            $plan = $plan ?? 20;

            return [
                'Name' => $referral['name'],
                'Buyer_ID' => $buyerName,
                'User_ID' => $referral['id'],
                'Side' => ($referral['side'] === 'L') ? 'Left' : 'Right',
                'Date' => date('Y-m-d H:i:s'),
                'Plan' => $plan,
            ];
        });

        return $referralList;
    }

    public function getReferrals(User $user, $level = 1, $maxLevel = 4, $parentSide = null): Collection
    {
        $referrals = new Collection();

        if ($level <= $maxLevel) {
            // Obtener los referidos del usuario actual en el lado izquierdo (binary_side = 'L')
            $leftReferrals = User::where('buyer_id', $user->id)
                ->where('binary_side', 'L')
                ->get(['id', 'name', 'profile_picture', 'buyer_id'])
                ->map(function ($referral) use ($level, $parentSide) {
                    return [
                        'id' => $referral->id,
                        'name' => $referral->name,
                        'level' => $level,
                        'side' => $parentSide ?: 'L',
                        'profile_picture' => $referral->profile_picture,
                        'buyer_id' => $referral->buyer_id,
                    ];
                });

            // Obtener los referidos del usuario actual en el lado derecho (binary_side = 'R')
            $rightReferrals = User::where('buyer_id', $user->id)
                ->where('binary_side', 'R')
                ->get(['id', 'name', 'profile_picture', 'buyer_id'])
                ->map(function ($referral) use ($level, $parentSide) {
                    return [
                        'id' => $referral->id,
                        'name' => $referral->name,
                        'level' => $level,
                        'side' => $parentSide ?: 'R',
                        'profile_picture' => $referral->profile_picture,
                        'buyer_id' => $referral->buyer_id,
                    ];
                });

            // Agregar los referidos a la colección
            $referrals = $referrals->concat($leftReferrals)->concat($rightReferrals);

            // Recorrer los referidos y obtener sus referidos recursivamente
            foreach ($referrals as $referral) {
                $subReferrals = $this->getReferrals(User::find($referral['id']), $level + 1, $maxLevel, $referral['side']);

                $referrals = $referrals->concat($subReferrals);
            }
        }

        // Ordenar los referidos por nivel
        $sortedReferrals = $referrals->sortBy('level');

        return $sortedReferrals;
    }

    public function getLast10Withdrawals()
    {
        $user = JWTAuth::parseToken()->authenticate();

        $withdrawals = WalletComission::select('id', 'description', 'amount', 'created_at')
            ->where('user_id', $user->id)
            ->where('avaliable_withdraw', '=', 0)
            ->take(15)
            ->get();

        $data = $withdrawals->map(function ($item) {
            $item['created_at'] = $item['created_at']->format('Y-m-d');
            return $item;
        });

        return response()->json($data, 200);
    }

    public function getUserOrders()
    {
        $user = JWTAuth::parseToken()->authenticate();
        return response()->json(OrdersResource::collection($this->orderRepository->getOrdersByUserId($user->id)));
    }

    public function getMonthlyOrders()
    {
        $user = JWTAuth::parseToken()->authenticate();

        $orders = Order::selectRaw('YEAR(created_at) AS year, MONTH(created_at) AS month, SUM(amount) AS total_amount')
            ->where('user_id', $user->id)
            ->groupBy('year', 'month')
            ->get();

        $data = [];

        foreach ($orders as $order) {
            $month = $order->month;
            $year = $order->year;
            $totalAmount = $order->total_amount;

            $date = Carbon::create($year, $month)->format('M');

            // Agregar los datos al arreglo de la gráfica
            $data[$date] = $totalAmount;
        }

        return response()->json($data, 200);
    }

    public function getMonthlyEarnings()
    {
        $user = JWTAuth::parseToken()->authenticate();

        $commissions = WalletComission::selectRaw('YEAR(created_at) AS year, MONTH(created_at) AS month, SUM(amount) AS total_amount')
            ->where('user_id', $user->id)
            ->groupBy('year', 'month')
            ->get();

        $data = [];

        foreach ($commissions as $commission) {
            $month = $commission->month;
            $earnings = $commission->total_amount;

            // Formatear la fecha para que coincida con el formato del método getMonthlyCommissions()
            $date = Carbon::create($month)->format('M');

            $data[$date] = $earnings;
        }

        return response()->json($data, 200);
    }


    public function getMonthlyCommissions()
    {
        $user = JWTAuth::parseToken()->authenticate();

        $commissions = WalletComission::selectRaw('YEAR(created_at) AS year, MONTH(created_at) AS month, SUM(amount) AS total_amount')
            ->where('user_id', $user->id)
            ->groupBy('year', 'month')
            ->get();

        $data = [];

        foreach ($commissions as $commission) {
            $month = $commission->month;
            $year = $commission->year;
            $totalAmount = $commission->total_amount;

            // Formatear la fecha para que coincida con el formato del método gainWeekly()
            $date = Carbon::create($year, $month)->format('M');

            // Agregar los datos al arreglo de la gráfica
            $data[$date] = $totalAmount;
        }

        return response()->json($data, 200);
    }


    public function myBestMatrixData()
    {
        $user = JWTAuth::parseToken()->authenticate();

        $lastApprovedCyborg = Order::where('user_id', $user->id)
            ->where('status', '1')
            ->latest('cyborg_id')
            ->first();

        $profilePicture = $user->profile_picture ?? '';

        $userPlan = User::where('id', $user->id)->value('type_matrix');

        $userPlan = $userPlan ?? 20;

        $referrals = $this->getReferrals($user);

        $userLevel = $referrals->max('level');


        $earning = 0;

        $earning = WalletComission::where('user_id', $user->id)
            ->sum('amount');

        $cyborg = $lastApprovedCyborg->cyborg_id;

        $data = [
            'id' => $user->id,
            'profilePhoto' =>  $profilePicture,
            'userPlan' => $userPlan,
            'userLevel' => $userLevel,
            'Cyborg' => $cyborg ?? 1,
            'earning' => $earning,
        ];

        return response()->json($data, 200);
    }

    public function getAllWithdrawals()
    {
        $user = JWTAuth::parseToken()->authenticate();

        $data = WalletComission::select('amount', 'created_at')
            ->where('user_id', $user->id)
            ->where('avaliable_withdraw', '=', 0)
            ->get();

        return response()->json($data, 200);
    }

    public function getUserBalance()
    {
        $user = JWTAuth::parseToken()->authenticate();

        $data = WalletComission::where('status', 0)
            ->where('user_id', $user->id)
            ->sum('amount');

        return response()->json($data, 200);
    }

    public function getUserBonus()
    {
        $user = JWTAuth::parseToken()->authenticate();

        $data = WalletComission::where('status', 0)
            ->where('user_id', $user->id)
            ->where('avaliable_withdraw', 1)
            ->sum('amount');

        return response()->json($data, 200);
    }

    public function getUsersWalletsList()
    {
        $users = User::with('wallets')->where('admin', '!=', '1')->orderBy('id', 'desc')->get();
        $data = [];
        foreach ($users as $user) {
            $amount = $user->wallets->where('status', '0')->sum('amount_available');

            $comission = $user->wallets()->where('status', '0')
                ->where(function ($query) {
                    $query->where('type', '0')
                        ->orWhere('type', '2');
                })
                ->sum('amount');

            $refund = $user->wallets->where('status', '0')
                ->where('type', '3')
                ->sum('amount');

            $trading = $user->wallets->where('status', '0')
                ->where('type', '1')
                ->sum('amount');


            $data[] = [
                'id' => $user->id,
                'userName' => $user->user_name,
                'email' => $user->email,
                'status' => $user->status,
                'affiliate' => $user->getAffiliateStatus(),
                'balance' => $comission + $refund + $trading,
                'comissions' => $comission,
                'refund' => $refund,
                'trading' => $trading
            ];
        }

        return response()->json($data, 200);
    }

    public function getFilterUsersWalletsList(Request $request)
    {
        $query = User::with('wallets')->where('admin', '0');
        $params = false;

        if ($request->has('email') && $request->email !== null) {
            $query->where('email', $request->email);
            $params = true;
        }

        if ($request->has('id') && $request->id !== null) {
            $query->where('id', $request->id);
            $params = true;
        }

        $users = $query->get();

        $data = [];

        foreach ($users as $user) {
            $amount = $user->wallets->where('status', '0')->sum('amount_available');
            $comissions =  $user->wallets()
                ->where('status', '0')
                ->where(function ($query) {
                    $query->where('type', '0')
                        ->orWhere('type', '2');
                })
                ->sum('amount');

            $refund = $user->wallets->where('status', '0')
                ->where('type', '3')
                ->sum('amount');

            $trading = $user->wallets->where('status', '0')
                ->where('type', '1')
                ->sum('amount');
            $data[] = [
                'id' => $user->id,
                'userName' => $user->user_name,
                'email' => $user->email,
                'status' => $user->status,
                'affiliate' => $user->getAffiliateStatus(),
                'balance' => round($amount, 2),
                'comissions' => $comissions,
                'refund' => $refund,
                'trading' => $trading

            ];
        }
        return response()->json($data, 200);
    }

    public function filterUsersWalletsList(Request $request)
    {
        $query = Liquidaction::where('user_id', '>', 1)->with('user', 'package');
        $params = false;

        if ($request->has('email') && $request->email !== null) {
            $email = $request->email;
            $query->whereHas('user', function ($q) use ($email) {
                $q->where('email', $email);
            });
            $params = true;
        }

        if ($request->has('id') && $request->id !== null) {
            $id = $request->id;
            $query->whereHas('user', function ($q) use ($id) {
                $q->where('id', $id);
            });
            $params = true;
        }

        $withdrawals = $query->get();

        $data = [];

        if ($withdrawals->count() == 0 || !$params) {
            return response()->json($data, 200);
        }

        foreach ($withdrawals as $withdrawal) {
            $withdrawal->wallet_used = Crypt::decrypt($withdrawal->wallet_used) ?? $withdrawal->wallet_used;
            $withdrawal->hash = $withdrawal->hash ?? str_pad($withdrawal->id, 4, '0', STR_PAD_LEFT);
        }

        return response()->json($withdrawals, 200);
    }

    public function filterUsersList(Request $request)
    {
        $user = User::where('admin', '0')
            ->get()
            ->values('id', 'user_name', 'email', 'affiliate', 'created_at');

        $query = User::where('admin', '0');
        $params = false;

        if ($request->has('email') && $request->email !== null) {
            $query->where('email', $request->email);
            $params = true;
        }

        if ($request->has('id') && $request->id !== null) {
            $query->where('id', $request->id);
            $params = true;
        }

        $user = $query->get()->values('id', 'user_name', 'email', 'affiliate', 'created_at');


        if (!$user || !$params) {
            return response()->json($user, 200);
        }
        return response()->json($user, 200);
    }

    public function GetCountry()
    {
        $paises = Prefix::all();
        return response()->json($paises, 200);
    }

    public function findUser(String $id)
    {
        $user = User::find($id);

        if ($user) return response()->json($user, 200);

        return response()->json(['message' => "User Not Found"], 400);
    }

    public function getUser(Request $request)
    {
        $user = User::with('prefix')->findOrFail($request->auth_user_id);
        return response()->json($user, 200);
    }

    public function ChangeData(UpdateProfileRequest $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $log = new ProfileLog;

        $data = [
            'id' => $user->id,
            'name' => $request->validated()['name'],
            'last_name' => $request->validated()['last_name'],
            'email' => $request->validated()['email']
        ];

        $url = config('services.backend_auth.base_uri');

        $response = Http::withHeaders([
            'apikey' => config('services.backend_auth.key'),
        ])->post("{$url}change-data", $data);

        $responseObject = $response->object();
        if ($responseObject->status) {
            $request->email == null || $request->email == ''
                ? $user->email = $user->email
                : $user->email = $request->email;
            $user->code_security = null;
            if ($request->hasFile('profile_picture')) {

                $picture = $request->file('profile_picture');
                $name_picture = $picture->getClientOriginalName();
                $file = new Filesystem;
                $file->cleanDirectory(public_path('storage') . '/profile/picture/' . $request->auth_user_id . '/' . '.', $name_picture);
                $picture->move(public_path('storage') . '/profile/picture/' . $request->auth_user_id . '/' . '.', $name_picture);

                $user->profile_picture = $name_picture;
            }

            $request->name == null || $request->name == ''
                ? $user->name = $user->name
                : $user->name = $request->name;

            $request->last_name == null || $request->last_name == ''
                ? $user->last_name = $user->last_name
                : $user->last_name = $request->last_name;

            $request->user_name == null || $request->user_name == ''
                ? $user->user_name = $user->user_name
                : $user->user_name = $request->user_name;

            $request->phone == null || $request->phone == ''
                ? $user->phone = $user->phone
                : $user->phone = $request->phone;

            $request->prefix_id == null || $request->prefix_id == ''
                ? $user->prefix_id = $user->prefix_id
                : $user->prefix_id = $request->prefix_id;

            $user->update();

            $log->create([
                'user' => $user->id,
                'subject' => 'Profile Data updated',
            ]);

            return response()->json('Profile Data updated', 200);
        }
    }

    public function ChangePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new ChangePassword($request->auth_user_id)],
            'new_password' => [
                'required', 'string',
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols(),
            ],
            'confirm_password' => ['same:new_password'],
        ]);

        $log = new ProfileLog;

        $data = ['id' => $request->auth_user_id, 'password' => $request->new_password];

        $url = config('services.backend_auth.base_uri');

        $response = Http::withHeaders([
            'apikey' => config('services.backend_auth.key'),
        ])->post("{$url}change-password", $data);

        $responseObject = $response->object();

        if ($responseObject->status) {
            $log->create([
                'user' => $responseObject->user_id,
                'subject' => 'Password Updated',
            ]);

            return response()->json('Password Updated', 200);
        } else {
            return response()->json('error', 401);
        }
    }

    public function CheckCodeToChangeEmail(Request $request)
    {
        $request->validate([
            'code_security' => 'required|string',
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::find($request->auth_user_id);

        if (Carbon::parse($user->code_verified_at)->addHour()->isPast()) {
            $user->update([
                'code_security' => null,
            ]);
            $response = ['message' => 'Expired code'];
            return response()->json($response, 422);
        }

        if (Hash::check($request->code_security, $user->code_security)) {
            $data = [
                'id' => $request->auth_user_id,
                'email' => $request->email,
                'password' => $request->password,
            ];

            $url = config('services.backend_auth.base_uri');

            $response = Http::withHeaders([
                'apikey' => config('services.backend_auth.key'),
            ])->post("{$url}check-credentials-email", $data);

            $responseObject = $response->object();

            if ($responseObject->status) {
                return response()->json('Authorized credentials', 200);
            }
        } else {
            $response = ['message' => 'Code is not valid'];
            return response()->json($response, 422);
        }
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
    /**
     * Obtiene la lista de los usuarios para el admin b2b
     */
    public function getUsers()
    {
        $users = User::where('admin', '0')->withSum(['wallets as total_gain' => function ($query) {
            $query->where('status', WalletComission::STATUS_AVAILABLE);
        }], 'amount_available')
            ->with('inversions', function ($query) {
                $query->where('status', Inversion::STATUS_APPROVED)->orderBy('id', 'desc');
            })->get();

        return response()->json($users, 200);
    }

    public function getUsersDownload()
    {
        $users = User::where('admin', '0')->get()->values('id', 'user_name', 'email', 'affiliate', 'created_at');
        foreach ($users as $user) {

            $data[] = [
                'id' => $user->id,
                'date' => $user->created_at->format('Y-m-d'),
                'user_name' => strtolower(explode(" ", $user->name)[0] . " " . explode(" ", $user->last_name)[0]),
                'status' => $user->getStatus(),
                'afilliate' => $user->getAffiliateStatus(),
            ];
        }

        return response()->json($data, 200);
    }

    public function auditUserWallets(Request $request)
    {
        $wallets = WalletComission::where('user_id', $request->user_id)->get();

        if (count($wallets) > 0) {
            $data = new Collection();

            foreach ($wallets as $wallet) {
                $buyer = User::find($wallet->buyer_id);

                switch ($wallet->status) {
                    case 'Requested':
                        $tag = 'warning';
                        break;

                    case 'Paid':
                        $tag = 'primary';
                        break;

                    case 'Voided':
                        $tag = 'danger';
                        break;

                    case 'Subtracted':
                        $tag = 'secondary';
                        break;

                    default:
                        $tag = 'success';
                        break;
                }

                $object = new \stdClass();
                $object->id = $wallet->id;
                $object->buyer = ucwords(strtolower($buyer->name . " " . $buyer->last_name));
                $object->amount = $wallet->amount;
                $object->status = ['title' => $wallet->status, 'tag' => $tag];
                $object->date = $wallet->created_at->format('m/d/Y');
                $data->push($object);
            }
            return response()->json($data, 200);
        }
        return response()->json(['status' => 'warning', 'message' => "This user don't have any wallet"], 200);
    }

    public function auditUserProfile(Request $request)
    {
        $user = User::with('prefix')->findOrFail($request->user_id);
        return response()->json($user, 200);
    }

    public function auditUserDashboard(Request $request)
    {
        $user = User::with('prefix')->findOrFail($request->user_id);
        // Falta presentar las metricas del usuario
        // Esto devuelve datos generales
        return response()->json($user, 200);
    }

    public function update(Request $request, User $user)
    {
        if (is_null($user)) {
            return response()->json(['message' => 'User not found'], 400);
        }

        $input = $request->all();
        $user->fill($input);
        $user->save();

        return response()->json($user, 200);
    }

    public function getReferalLinks()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $referal_links = ReferalLink::where('user_id', $user->id)->where('status', ReferalLink::STATUS_ACTIVE)->with('cyborg')->get();
        return response()->json($referal_links, 200);
    }
}
