<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class LandingController extends Controller
{
    public function contactUs(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        try {
            $dataEmail = [ 
                'email' => $request->email,
                'name' => $request->name,
                'subject' => $request->subject,
                'message' => $request->message,
            ];

            Mail::send('mails.contactUs',  ['data' => $dataEmail], function ($msj) use ($request){
                $msj->subject("[Contact us] ".$request->subject);
                $msj->to('support@fundyourtrades.com');
            });

            return response()->json(['status' => 'success', 'message' => 'Email sent'], 200);

        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json(['error' => 'success', 'message' => $th], 400);
        }
        
        
    }

    public function subscription(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:subscriptions',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        try {
            DB::beginTransaction();
            Subscription::create([
                'email' => $request->email
            ]);
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Email Subscribed'], 200);
        } catch (\Throwable $th) {
            Log::error($th);
            DB::rollBack();
            return response()->json(['error' => 'success', 'message' => $th], 400);
        }
        
        
    }

    public function downloadTermsAndConditions()
    {
        try {
            $path = public_path('storage/documents/terms_and_conditions_en.pdf');
            $headers = ['Content-Type' => 'application/pdf',];
            return  response()->download($path, 'terms_and_conditions_en', $headers);

        } catch (\Throwable $th) {
            return back()->with('warning', 'The file you want to download was not found');
        }
    }

    public function mailsTermsAndConditions()
    {
        try {
            $path = public_path('storage/documents/terms_and_conditions_en.pdf');
            $headers = ['Content-Type' => 'application/pdf',];
            return  response()->download($path, 'terms_and_conditions_en.pdf', $headers);

        } catch (\Throwable $th) {
            return back()->with('warning', 'The file you want to download was not found');
        }
    }
}
