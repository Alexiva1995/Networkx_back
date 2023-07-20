<?php

namespace App\Http\Controllers;

use App\Http\Requests\KycStoreRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Kyc;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KycController extends Controller
{
    public function admin() {
    	$kyc = Kyc::where('status', '>=', '0')->with('getUser')->orderBy('created_at', 'desc')->get();

    	return response()->json($kyc, 200);
    }
	public function filterKycList(Request $request) {
        $query = Kyc::where('status', '>=', '0')->with('getUser');
        $params = false;

        if ($request->has('email') && $request->email !== null) {
            $email = $request->email;
            $query->whereHas('getUser', function($q) use($email){
                $q->where('email', $email);
            });
            $params = true;
        }

        if ($request->has('id') && $request->id !== null) {
            $query->where('user_id', $request->id);
            $params = true;
        }

        $user = $query->get();


        if(!$user || !$params) {
            return response()->json($user, 200);
        }
        return response()->json($user, 200);
    }

    public function store(KycStoreRequest $request)
    {
        $user = User::find($request->auth_user_id);
        try {
            DB::beginTransaction();
            if ($this->validateStatus($user)) {

                //* Storage Image
                $nameFile = $this->storageImage($request);

                $solicitudKycRechazada = Kyc::where([
                    ['user_id', $request->auth_user_id],
                    ['status', '2']
                ])->get()->last();

                if (!empty($solicitudKycRechazada)) {
                    $solicitudKycRechazada->delete();
                }

                Kyc::create([
                    'user_id' => $request->auth_user_id,
                    'document' => $request->document,
                    'file_front' => $nameFile['front'],
                    'file_back' => $nameFile['back'],
                    'status' => 0,
                ]);

                $user->update(['kyc' => 0]);
                DB::commit();
                return response()->json(['msg' => 'Your KYC request has been send'], 200);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['msg' => $th->getMessage()], $th->getCode());
        }
    }

    public function updateStatus(Request $request) {
    	$request->validate([
    		'id_user' => 'required|integer',
    		'id_kyc' => 'required|integer',
    		'status' => 'required|integer'
    	]);

    	if($request->status == 1) {
    		$user = User::find($request->id_user);
    		$kyc = Kyc::find($request->id_kyc);

    		$user->kyc = $request->status;
    		$user->save();

    		$kyc->status = $request->status;
    		$kyc->save();

    		return response()->json(['msg' => 'The KYC was confirmed'], 200);
    	} else if ($request->status == 2) {
    		$user = User::find($request->id_user);
    		$kyc = Kyc::find($request->id_kyc);

    		$user->kyc = null;
    		$user->save();

    		$kyc->delete();

    		return response()->json(['msg' => 'The KYC was canceled'], 200);
    	} else {
    		return response()->json(['msg' => 'Status not found'], 400);
    	}
    }

    private function validateStatus($user)
    {
        if ($user->kyc == 1) {
            throw new Exception('Your KYC request has been approved already', 200);
        }

        if ($user->kyc === 0) {
            throw new Exception('Your KYC request is in process to be checkedy', 200);
        }

        if ($user->kyc == null || $user->kyc == 2) {
            return true;
        }
    }

    private function storageImage($request)
    {
        //parte frontal del documento
        $frontal = $request->file('file_front');
        $nombre_frontal = $request->auth_user_id . '.' . time() . '.front.' . $frontal->getClientOriginalExtension();
        $frontal->move(public_path('storage') . '/KYC/frontal/' . $request->auth_user_id . '/' . '.', $nombre_frontal);

        //parte trasera del documento
        $trasera = $request->file('file_back');
        $nombre_trasera = $request->auth_user_id . '.' . time() . '.trasera' . '.' . '.trasera.' . $trasera->getClientOriginalExtension();
        $trasera->move(public_path('storage') . '/KYC/trasera/' . $request->auth_user_id . '/' . '.', $nombre_trasera);

        return ['front' => $nombre_frontal, 'back' => $nombre_trasera];
    }
}
