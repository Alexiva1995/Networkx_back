<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Controllers\TreController;
use App\Http\Requests\UserStoreRequest;
use App\Mail\ForgotPasswordNotification;
use App\Mail\PasswordChangedNotification;
use App\Models\Market;
use App\Models\MarketPurchased;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Validator;
use App\Models\Prefix;
use App\Models\ReferalLink;
use App\Services\CoinpaymentsService;
use Exception;
use App\Services\BonusService;
use Illuminate\Support\Facades\Http;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    protected $treController, $CoinpaymentsService;


    public function __construct(TreController $treController, CoinpaymentsService $CoinpaymentsService)
    {
        $this->treController = $treController;
        $this->CoinpaymentsService = $CoinpaymentsService;
    }
    /**
     * The method for registering a new user
     * @param Request
     */
    public function register(UserStoreRequest $request)
    {

        try {
            // En $sponsor_id esta el id del padre (el dueño del link) aplicar logica correspondiente y obtener el lado adecuado (tarea processes de auth back)
            $binary_side = 'R';
            $sponsor_id = 1;
            $binary_id = 1;
            $link = null;
            // Aca valida si el link de referido es valido, es decir el link de la matrix.Si no lo es, termina la ejecución acá.
            if ($request->link_code) {
                $validation = $this->checkMatrix($request->link_code, $request->binary_side, false);
                if (!$validation['status']) {
                    $response = ['Error' => 'Invalid referral link'];
                    return response()->json($response, 400);
                }
                $sponsor_id = $validation['sponsor_id'];
                $link = $validation['link'];
            }

            if ($request->has('binary_side')) $binary_side = $request->binary_side;

            $userFather = User::findOrFail($sponsor_id);

            if (gettype($sponsor_id) == 'integer') {
                $binary_id = $this->treController->getPosition(intval($sponsor_id), $binary_side);
            }

            DB::beginTransaction();
            $data = [
                'name' => $request->user_name,
                'last_name' => $request->user_lastname,
                'password' => $request->password,
                'password_confirmation' => $request->password_confirmation,
                'email' => $request->email
            ];


            $user = User::create([
                'name' => $request->user_name,
                'last_name' => $request->user_lastname,
                'binary_id' => $binary_id,
                'email' => $request->email,
                'email_verified_at' => now(),
                'binary_side' => $binary_side,
                'buyer_id' => $sponsor_id,
                'prefix_id' => $request->prefix_id,
                'status' => '0',
                'code_security' => Str::random(12),
                'phone' => $request->phone,
            ]);
            $user->user_name = strtolower(explode(" ", $request->user_name)[0][0] . "" . explode(" ", $request->user_lastname)[0]) . "#" . $user->id;

            $url = config('services.backend_auth.base_uri');

            $response = Http::withHeaders([
                'apikey' => config('services.backend_auth.key'),
            ])->post("{$url}register", $data);
            Log::alert($response);
            if ($response->successful()) {
                $res = $response->object();
                $user->update(['id' => $res->user->id]);
                $dataEmail = ['user' => $user];

                // Actualizamos el link si existe en el proceso
                if ($link) {
                    if ($binary_side == 'R') $link->right = 1;
                    if ($binary_side == 'L') $link->left = 1;
                    if ($link->right == 1 && $link->left == 1) $link->status = ReferalLink::STATUS_INACTIVE;
                    $link->save();
                }

                DB::commit();

                Mail::send('mails.verification',  ['data' => $dataEmail], function ($msj) use ($request) {
                    $msj->subject('Email verification.');
                    $msj->to($request->email);
                });

                return response()->json([$user], 201);
            }
            DB::rollback();
            $response = ['errors' => ['register' => [0 => 'Error registering users']]];

            return response()->json($response, 500);
        } catch (\Throwable $th) {
            Log::error($th);
            DB::rollback();
            // $response = ['Error' => 'Error registering user'];
            $response = ['errors' => ['register' => [0 => 'Error registering users']]];;
            return response()->json($response, 500);
        }
    }
    /**
     * Get a JWT via given credentials.
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $url = config('services.backend_auth.base_uri');

        $credentials = $request->only('email', 'password');

        $response = Http::withHeaders([
            'apikey' => config('services.backend_auth.key'),
        ])->post("{$url}login", $credentials);

        $responseObject = $response->object();

        $user = User::where('email', $request->email)->first();

        if ($responseObject->success && $user) {
            if (!$user->email_verified_at) {
                $user->update(['code_security' => Str::random(12)]);
                $dataEmail = ['user' => $user];

                Mail::send('mails.verification',  ['data' => $dataEmail], function ($msj) use ($request) {
                    $msj->subject('E-mail verification.');
                    $msj->to($request->email);
                });

                return response()->json(['message' => 'Unverified email', 'email' => $user->email], 200);
            }

            DB::beginTransaction();
            $user->token_jwt = $responseObject->token;
            $user->save();
            $data = [
                'id' => $user->id,
                'user_name' => $user->user_name,
                'name' => $user->name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'affiliate' => $user->affiliate,
                'admin' => $user->admin,
                'profile_picture' => $user->profile_picture ?? '',
                'email_verified_at' => $user->email_verified_at,
                'api_token' => $responseObject->token,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'message' => 'Successful login.'
            ];

            DB::commit();
            return response()->json($data, 200);
        }

        DB::rollBack();
        return response()->json(['message' => $responseObject->message], 400);
    }

    public function logout(Request $request)
    {
        $token = request()->bearerToken();

        $user = User::findOrFail($request->auth_user_id);

        $url = config('services.backend_auth.base_uri');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'apikey' => config('services.backend_auth.key'),
        ])->post("{$url}logout");

        if ($response->successful()) {
            $user->update(['token_jwt' => null]);
            return response()->json(['status' => 'success', 'message' => 'You have successfully logged out'], 200);
        }
    }
    /**
     * Genera un token de seguridad que es enviado al usuario via email para confirmar el restablecimiento de contraseña
     * @param  \Iluminate\Http\Request $request
     * @return Json La respuesta en formato Json
     */
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 200);
        }

        $user = User::where('email', $request->email)->first();

        $token = Str::random(12);
        $user->update(['code_security' => $token]);

        Mail::to($user->email)->send(new ForgotPasswordNotification($token, $user->email));

        $response = ['status' => 'success', 'message' => 'We have sent a security code to your email address.'];

        return response()->json($response, 200);
    }
    /**
     * Confirma el token de seguridad y envia la petición al authJWT para cambiar la contraseña
     * @param  \Iluminate\Http\Request $request
     * @return Json La respuesta en formato Json
     */
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => [
                'required', 'string', 'confirmed',
                Password::min(8) // Debe tener por lo menos 8 caracteres
                    ->mixedCase() // Debe tener mayúsculas + minúsculas
                    ->letters() // Debe incluir letras
                    ->numbers() // Debe incluir números
                    ->symbols(), // Debe incluir símbolos,
            ],
            'code_security' => 'required|exists:users'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()], 400);
        }

        $user = User::where('code_security', $request->code_security)->first();

        $data = ['email' => $user->email, 'password' => $request->password];

        $url = config('services.backend_auth.base_uri');
        // Enviamos la data al backend auth para actualizarla
        $response = Http::withHeaders([
            'apikey' => config('services.backend_auth.key'),
        ])->post("{$url}update-password", $data);

        $responseObject = $response->object();

        if ($responseObject->status) {

            Mail::to($user->email)->send(new PasswordChangedNotification());

            $user->update(['code_security' => null]);

            $response = ['status' => 'success', 'message' => $responseObject->message];

            return response()->json($response, 200);
        }
    }
    // Ruta para probar el funcionamiento de la validación doble con JWT en otro servidor
    public function test(Request $request)
    {
        $user = User::findOrFail($request->auth_user_id);
        dd($user);
    }
    /**
     * Confirma el correo del usuario al momento de crearse una cuenta.
     * @param  \Iluminate\Http\Request $request
     * @return Json La respuesta en formato Json
     */
    public function verifyEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code_security' => 'required|exists:users,code_security',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::where('code_security', $request->code_security)->first();

        $data = ['email' => $user->email];

        $url = config('services.backend_auth.base_uri');

        $response = Http::withHeaders([
            'apikey' => config('services.backend_auth.key'),
        ])->post("{$url}verify-email", $data);

        if ($response->successful()) {
            $resObj = $response->object();

            $user->update([
                'email_verified_at' => $resObj->time,
                'code_security' => null
            ]);

            $dataEmail = ['user' => $user->fullName()];

            Mail::send('mails.welcome',  ['data' => $dataEmail], function ($msj) use ($user) {
                $msj->subject('Welcome to B2B.');
                $msj->to($user->email);
            });

            return response()->json(['status' => 'success', 'message' => 'Mail successfully verified'], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Failed mail verification'], 400);
    }
    /**
     * Obtiene la lista de prefijos (paises) para el formulario de registro.
     * @param  \Iluminate\Http\Request $request
     * @return Json La respuesta en formato Json
     */
    public function getPrefixes(Request $request)
    {
        $data = ['prefixes' => Prefix::all()];
        return response()->json($data, 200);
    }
    /**
     * Genera un nuevo codigo de seguridad y lo envia al usuario para confirmar su correo.
     * @param  \Iluminate\Http\Request $request
     * @return Json La respuesta en formato Json
     */
    public function sendEmailVerificationCode(Request $request)
    {
        $request->validate([
            'email' => 'required|exists:users'
        ]);

        $user = User::where('email', $request->email)->first();

        $user->update(['code_security' => Str::random(12)]);

        $dataEmail = ['user' => $user];

        Mail::send('mails.verification',  ['data' => $dataEmail], function ($msj) use ($request) {
            $msj->subject('Email verification.');
            $msj->to($request->email);
        });


        return response()->json(['message' => 'We have sent a new code to your email'], 200);
    }

    public function sendVerificationCode(Request $request)
    {
        $request->validate([
            'email' => 'required|exists:users'
        ]);

        $user = User::where('email', $request->email)->first();

        $user->update(['code_security' => Str::random(12)]);

        $dataEmail = ['code' => $user->code_security];

        Mail::send('mails.CodeSecurity',  $dataEmail, function ($msj) use ($request) {
            $msj->subject('Email verification.');
            $msj->to($request->email);
        });


        return response()->json(['message' => 'We have sent a new code to your email'], 200);
    }

    /**
     * Ruta para verificar que el token es valido
     * @param \Iluminate\Http\Request $request
     * @return Json
     */
    public function verifyToken(Request $request)
    {
        $user = User::findOrFail($request->auth_user_id);

        $data = [
            'id' => $user->id,
            'user_name' => $user->user_name,
            'nickname_referral_link' => $user->nickname_referral_link,
            'name' => $user->name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'admin' => $user->admin,
            'affiliate' => $user->affiliate,
            'email_verified_at' => $user->email_verified_at,
            'api_token' => $user->token_jwt,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
            'message' => 'Successful login.'
        ];
        return response()->json($data, 200);
    }

    public function getSponsorName($identifier)
    {
        $user = User::where('id', $identifier)
            ->orWhere('nickname_referral_link', $identifier)
            ->first();
        if ($user) {
            $data_sponsor = [
                "name" => "$user->name $user->last_name",
                "id" => $user->id,
            ];
            return response()->json($data_sponsor, 200);
        }
        return response()->json(['message' => 'invalid referral id'], 400);
    }
    public function getAuthUser()
    {
        $user = JWTAuth::parseToken()->authenticate();
        return response()->json($user, 200);
    }

    public function checkMatrix(String $code, String $side, $come_from_front = true)
    {
        $link = ReferalLink::where('link_code', $code)->with('user')->first();

        if ($come_from_front) {
            if (!$link) return response()->json(['message' => 'Invalid link code'], 400);

            if ($link->status == ReferalLink::STATUS_INACTIVE) {
                return response()->json(['message' => 'This matrix is already complete'], 400);
            }

            if ($side == 'R' && $link->right == '1' || $side == 'L' && $link->left == '1') {
                return response()->json(['message' => 'Invalid link'], 400);
            }

            return response()->json(['sponsor' => $link->user], 200);
        } else {
            $response = ['status' => true, 'link' => $link, 'sponsor_id' => null];

            if (!$link) return $response['status'] = false;

            if ($link->status == ReferalLink::STATUS_INACTIVE) $response['status'] = false;

            if ($side == 'R' && $link->right == '1' || $side == 'L' && $link->left == '1')  $response['status'] = false;

            $response['status'] = true;
            $response['sponsor_id'] = $link->user->id;

            return $response;
        }
    }


    public function firstPurchase(Request $request)
    {
        try {
            $user = User::where('email', $request->email)->first();
            $cyborg = Market::find(1);

            if (is_null($user->type_service)) {
                $user->type_service = $request->type_service == 'service' ? 2 : 0;
                $user->save();
            }

            // Crear la orden en la tabla "orders"
            $order = new Order();
            $order->user_id = $user->id;
            $order->cyborg_id = $cyborg->id;
            $order->status = '0';
            $order->amount = $cyborg->amount;
            $order->save();

            // Ejecutar la lógica de la pasarela de pago y obtener la respuesta
            $response = $this->CoinpaymentsService->create_transaction($cyborg->amount, $cyborg, $request, $order, $user);
            if ($response['status'] == 'error') {
                Log::debug($response);
                throw new Exception("Error processing purchase", 400);
            }
            // $bonusService = new BonusService;
            //$bonusService->generateBonus(20,$user, $order, $buyer = $user, $level = 2, $user->id);
            return response()->json($response, 200);
            //code...
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], $th->getCode());
        }
    }
    public function createComission(int $id)
    {
        $user = User::find($id);

        $order = Order::create([
            'user_id' => $user->id,
            'amount' => 50,
            'hash' => null,
            'status' => '1',
            'cyborg_id' => '1'
        ]);

        $marketPurchased = MarketPurchased::create([
            'user_id' => $user->id,
            'cyborg_id' => 1,
            'order_id' => $order->id
        ]);

        ReferalLink::create([
            'user_id' => $user->id,
            'link_code' => Str::random(6),
            'cyborg_id' => 1,
            'right' => 0,
            'left' => 0,
            'status' => ReferalLink::STATUS_INACTIVE,
        ]);

        $bonusService = new BonusService;

        $bonusService->generateBonus(20, $user, $order, $buyer = $user, $level = 2, $user->id);

        return response()->json(':D', 200);
    }
}
