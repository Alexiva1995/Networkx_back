<?php

namespace App\Http\Controllers;

use App\Http\Requests\BuyPackageRequest;
use App\Http\Requests\FormularyStoreRequest;
use App\Models\Coupon;
use App\Models\PackageMembership;
use App\Models\User;
use App\Models\Order;
use App\Models\Project;
use App\Models\Formulary;
use App\Models\WalletComission;
use App\Services\FutswapService;
use App\Http\Resources\ProjectResource;
use App\Models\UserCoupon;
use App\Services\PagueloFacilService;
use App\Services\BonusService;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class PackageMembershipController extends Controller
{
    private $pagueloFacil;
    protected $bonusService;

    public function __construct(BonusService $bonusService, PagueloFacilService $pagueloFacil)
    {
        $this->pagueloFacil = $pagueloFacil;
        $this->bonusService = $bonusService;
    }

    public function GetPackageMemberships($email)
    {
        $user = User::where('email', $email)->first();
        $orders = Order::where('user_id', $user->id)->with('project', 'packageMembership')->get();
        $packageMemberships = PackageMembership::all();

        foreach ($packageMemberships as $package) {
            foreach ($orders as $order) {
                if ($package->id == $order->membership_packages_id) {
                    $package->status = $order->status;
                    break;
                } else {
                    $package->status = null;
                }
            }
        }

        return response()->json(['status' => 'success', 'data' => ['packages' => $packageMemberships]], 201);
    }

    public function GetPackagesList()
    {
        $packages = PackageMembership::all();

        foreach ($packages as $package) {
            switch ($package->type) {
                case '1':
                    $package->type = "EVALUATION";
                    break;
                case '2':
                    $package->type = "FAST";
                    break;
                case '3':
                    $package->type = "ACCELERATED";
                    break;
                case '4':
                    $package->type = "FLASH";
                    break;
            }
        }

        return response()->json($packages, 200);
    }

    public function filterAdminReports(Request $request)
    {
        $query = Project::with('order');

        $params = false;

        if ($request->has('email') && $request->email !== null) {

            $email = $request->email;

            $query->whereHas('order', function ($q) use ($email) {
                $q->whereHas('user', function ($a) use ($email) {
                    $a->where('email', $email);
                });
            });

            $params = true;
        }

        if ($request->has('id') && $request->id !== null) {

            $id = $request->id;

            $query->whereHas('order', function ($q) use ($id) {
                $q->whereHas('user', function ($a) use ($id) {
                    $a->where('id', $id);
                });
            });
            $params = true;
        }

        $data = $query->get();

        $projects = ProjectResource::collection($data);
        return response()->json(['status' => 'success', 'data' => $projects, 201]);
    }

    public function formularyUpdate(Request $request)
    {
        $project = Project::find($request->project_id);


        $project->formulary->update([
            'name' => $request->name != "" ? $request->name : $project->formulary->name,
            'login' => $request->login != "" ? $request->login : $project->formulary->login,
            'password' => $request->password != "" ? Crypt::encryptString($request->password) : $project->formulary->password,
            'leverage' => $request->leverage != "" ? $request->leverage : $project->formulary->leverage,
            'balance' => $request->balance && $request->balance != 0 ? $request->balance : $project->formulary->balance,
            'server' => $request->serverr != "" ? $request->serverr : $project->formulary->server,
            'date' => $request->date != "" ? $request->date : $project->formulary->date,
        ]);
        $dataEmail = [
            'user' => $project->order->user->fullName(),
            'name' => $request->name,
            'login' => $request->login,
            'password' => $request->password,
            'leverage' => $request->leverage,
            'balance' => $request->balance,
            'server' => $request->serverr,
            'date' => $request->date
        ];
        Mail::send('mails.sendCredentials',  ['data' => $dataEmail], function ($msj) use ($project) {
            $msj->subject('Update Project Credentials.');
            $msj->to($project->order->user->email);
        });
        return response()->json(['status' => 'success', 'Successful, Formulary Updated!', 201]);
    }

    public function BuyPackage(BuyPackageRequest $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $package = PackageMembership::find($request->package);

        if ($package->type == PackageMembership::FTY_FAST && !$user->can_buy_fast) {
            return response()->json(["message" => "You do not have permission to purchase these packages please contact support."], 403);
        }

        $amount = $package->amount;
        $percent = $package->amount * 0.10;
        $userCoupon = UserCoupon::with('coupon')->where('user_id', $user->id)->where('used',false)->first();

        if ($userCoupon) {
            $amount = $amount - (($userCoupon->coupon->percentage * $amount) / 100);
        }

        $order = Order::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'membership_packages_id' => $package->id,
            'type' => $request->status == 3 ? 'renovacion' : 'inicio'
        ]);

        if ($request->platform === 'paguelofacil') {
            $amount_with_commission = $order->amount * 1.05;
            $response = $this->pagueloFacil->makeTransaction($user->id, $order->id, $amount_with_commission);
        }

        if (isset($response) && $response[0] != 'error') {
            if ($userCoupon) {
                $userCoupon->used = true;
                $userCoupon->save();
                $order->coupon_id = $userCoupon->coupon_id;
                $order->save();
            }
            $dataMail = [
                'email' => $user->email,
                'date' => now()->format('Y-m-d')
            ];

            Mail::send('mails.orderCreate', $dataMail,  function ($msj) {
                $msj->subject('Order Create!');
                $msj->to('admin@fyt.com');
            });

            //redirecciona a la url del pago
            return response()->json(['status' => 'success', 'data' => ['url' => $response, 'message' => "Successful, redirecting to $request->platform"]], 201);
        } else {
            return response()->json(['status' => 'error', 'message' => 'There was an error', 'data' => ['url' => $response[1]]], 400);
        }
    }

    public function addOrderToUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|exists:users',
            'package_membership_id' => 'required|exists:package_memberships,id',
            'hash' => 'required',
            'generates_comission' => 'required',
            'discount' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()], 400);
        }

        try {
            DB::beginTransaction();

            $user = User::whereEmail($request->email)->first();

            $package = PackageMembership::find($request->package_membership_id);

            if ($request->discount != 0) $package->amount = $package->amount - ($request->discount / 100) * $package->amount;

            $order = Order::create([
                'user_id' => $user->id,
                'amount' => round($package->amount,2),
                'membership_packages_id' => $package->id,
                'type' => 'inicio',
                'hash' => $request->hash,
                'status' => '1'
            ]);

            $projectStatus = ($order->membership_packages_id < 5 && $order->membership_packages_id > 7)
                ? 2
                : 0;

            Project::create([
                'order_id' => $order->id,
                'amount' => $order->amount,
                'status' => $projectStatus,
            ]);

            if ($request->generates_comission) {
                $this->bonusService->directBonus($order->user, $order->amount, $order->user_id, $order);
            }

            DB::commit();

            return response()->json(['message' => 'Order generated correctly'], 201);
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error($th);
            return response()->json(["message" => "An error ocurred please try again later"], 500);
        }
    }

    public function GetProjectsAdmin()
    {
        $projects = ProjectResource::collection(Project::with(['order.user', 'order.packageMembership','formulary'])->orderBy('id', 'desc')->get());
        return response()->json(['status' => 'success', 'data' => $projects, 200]);
    }

    public function GetProjectAdmin(Request $request)
    {
        $project = new ProjectResource(Project::findOrFail($request->id));
        return response()->json(['status' => 'success', 'data' => $project, 201]);
    }

    public function updateStatusProject(Request $request)
    {
        $project = Project::whereId($request->id)->with('order.packageMembership')->first();
        $user_referred = User::whereId($project->order->user_id)->first();
        $user_coupons = Coupon::whereUser_id($project->order->user_id)->get();
        $coupons = count($user_coupons);
        if ($request->status == '2') {
            if ($project->order->packageMembership->type == '3') {
                $project->status = $request->status;
                $project->complete = 1;
                $project->save();
                $dataEmail = [
                    'user' => $project->order->user->fullName(),
                    'program' => 'ACCELERATED CHALLENGE',
                ];
                Mail::send('mails.programApproved',  ['data' => $dataEmail], function ($msj) use ($project) {
                    $msj->subject('Accelerated Chanllenge Approved.');
                    $msj->to($project->order->user->email);
                });
            } elseif ($project->order->packageMembership->type == '2' || $project->order->packageMembership->type == '4') {
                if ($project->phase1 == null) {
                    $project->status = 1;
                    $project->phase1 = 1;
                    $project->save();
                    // PHASE 1 aprobada del programa FAST o FLASH

                    $dataEmail = [
                        'user' => $project->order->user->fullName(),
                        'program' => $project->order->packageMembership->type == '2' ? 'FAST CHALLENGE' : 'FLASH CHALLENGE',
                    ];
                    if($project->order->packageMembership->type == '2') {
                        Mail::send('mails.fast1Approved',  ['data' => $dataEmail], function ($msj) use ($project) {
                            $msj->subject('Fast Challenge Phase 1 Approved.');
                            $msj->to($project->order->user->email);
                        });
                    } else {
                        Mail::send('mails.flash1Approved',  ['data' => $dataEmail], function ($msj) use ($project) {
                            $msj->subject('Flash Challenge Phase 1 Approved.');
                            $msj->to($project->order->user->email);
                        });
                    }

                } else {
                    $project->status = 2;
                    $project->complete = 1;
                    $project->save();

                    $dataEmail = [
                        'user' => $project->order->user->fullName(),
                        'program' => $project->order->packageMembership->type == '2' ? 'FAST CHALLENGE' : 'FLASH CHALLENGE',
                    ];
                    
                    $subject = $project->order->packageMembership->type == '2' ? 'Fast Chanllenge Approved.' : 'Flash Chanllenge Approved.';

                    Mail::send('mails.programApproved',  ['data' => $dataEmail], function ($msj) use ($project, $subject) {
                        $msj->subject($subject);
                        $msj->to($project->order->user->email);
                    });
                }
            } elseif($project->order->packageMembership->type == '1') {
                if ($project->phase2 == null) {
                    if ($project->phase1 == 1) {
                        $project->phase2 = 1;
                        $project->save();

                        // PHASE 2 aprovada del programa Evaluation
                        $dataEmail = [
                            'user' => $project->order->user->fullName(),
                        ];
                        Mail::send('mails.evaluation2Approved',  ['data' => $dataEmail], function ($msj) use ($project) {
                            $msj->subject('Evaluation Chanllenge Phase 2 Approved.');
                            $msj->to($project->order->user->email);
                        });
                    } else {
                        $project->status = 1;
                        $project->phase1 = 1;
                        $project->save();
                        // PHASE 1 aprovada del programa Evaluation
                        $dataEmail = [
                            'user' => $project->order->user->fullName(),
                        ];
                        Mail::send('mails.evaluation1Approved',  ['data' => $dataEmail], function ($msj) use ($project) {
                            $msj->subject('Evaluation Chanllenge Phase 1 Approved.');
                            $msj->to($project->order->user->email);
                        });
                    }
                } else {
                    $project->status = 2;
                    $project->complete = 1;
                    $project->save();

                    $dataEmail = [
                        'user' => $project->order->user->fullName(),
                    ];

                    Mail::send('mails.evaluationCompleted',  ['data' => $dataEmail], function ($msj) use ($project) {
                        $msj->subject('Evaluation Chanllenge Completed.');
                        $msj->to($project->order->user->email);
                    });
                }
            }
        } else {
            $project->status = $request->status;
            $project->save();

            // Pograma rechazado
            $phase = isset($project->phase2) ? ' PHASE 2' : (isset($project->phase1) ? ' PHASE 1' : '');
            $dataEmail = [
                'user' => $project->order->user->fullName(),
                'program' => $project->order->packageMembership->getTypeName() . $phase,
                'description' => $request->description,
            ];
            Mail::send('mails.unsuccessfulTest',  ['data' => $dataEmail], function ($msj) use ($project) {
                $msj->subject('Unsuccessful Test.');
                $msj->to($project->order->user->email);
            });
        }
        if ($project->order->packageMembership->type == '1') {
            if ($project->complete == 1) {
                if ($user_referred->buyer_id == '1' && is_null($project->order->coupon_id)) {
                    $this->createWallet($project);
                }
            }
        }

        return response()->json(['status' => 'success', 'data' => ['project' => $project, 'message' => 'Successful, updated status'], 201]);
    }

    public function formularyCreate(FormularyStoreRequest $request)
    {

        $project = Project::with('order.user')->whereId($request->project_id)->first();

        Formulary::create([
            'project_id' => $project->id,
            'name' => $request->name,
            'login' => $request->login,
            'password' => Crypt::encryptString($request->password),
            'leverage' => $request->leverage,
            'balance' => $request->balance,
            'server' => $request->serverr,
            'date' => $request->date,
        ]);
        $dataEmail = [
            'user' => $project->order->user->fullName(),
            'name' => $request->name,
            'login' => $request->login,
            'password' => $request->password,
            'leverage' => $request->leverage,
            'balance' => $request->balance,
            'server' => $request->serverr,
            'date' => $request->date
        ];

        Mail::send('mails.sendCredentials',  ['data' => $dataEmail], function ($msj) use ($project) {
            $msj->subject('Project Credentials.');
            $msj->to($project->order->user->email);
        });


        return response()->json(['status' => 'success', 'Successful, Formulary Created!', 201]);
    }

    private function createWallet($project)
    {
        WalletComission::create([
            'user_id' => $project->order->user_id,
            'buyer_id' => 1,
            'membership_id' => $project->id,
            'order_id' => $project->order->id,
            'description' => 'Retorno 100%',
            'type' => 3, //tipo retorno de inversión
            'level' => '1',
            'status' => 0,
            'available_withdraw' => 0,
            'amount_available' => $project->order->amount,
            'amount' => $project->order->amount,
        ]);
    }
}
