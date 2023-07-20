<?php

namespace App\Http\Controllers;


use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Models\MessageTicket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;


class TicketsController extends Controller
{
    public function getTickets(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        if ($user->admin == '1') {
            $tickets = Ticket::with('user')->with('messages')->orderBy('updated_at', 'desc')->get();
        } else {
            $tickets = Ticket::where('user_id', $user->id)->with('user')->with('messages')->orderBy('updated_at', 'desc')->get();
        }
        foreach ($tickets as $ticket) {
            $ticket->user->name = ucwords(strtolower($ticket->user->name));
            $ticket->user->last_name = ucwords(strtolower($ticket->user->last_name));
            $ticket->user->user_name = strtolower($ticket->user->user_name);
            $ticket->user->email = strtolower($ticket->user->email);
        }

        return response()->json($tickets, 200);
    }

    public function createTicket(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required|string',
            'message' => 'required|string',
            'categories' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->toArray()[0]], 400);
        }

        $user = JWTAuth::parseToken()->authenticate();

        try {
            DB::beginTransaction();

            $ticket = Ticket::create([
                'user_id' => $user->id,
                'subject' => request('subject'),
                'categories' => request('categories')
                // 'priority' => request('priority')
            ]);
            $request['ticket_id'] = $ticket->id;
            $this->createMessage($request);
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Ticket Created!'], 200);
        } catch (\Throwable $th) {
            Log::error($th);
            DB::rollBack();
            return response()->json(["message" => "Please try again later"], 500);
        }

    }

    public function createMessage(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $ticket = Ticket::find($request->ticket_id);

        if (isset($ticket) && $ticket->status == 0) {
            if ($request->hasFile('image')) {
                $validator = Validator::make($request->all(), [
                    'image' => 'mimes:jpg,png,jpeg,gif,svg,pdf|max:2048',
                ]);

                if($validator->fails()){
                    return response()->json(['message' =>  $validator->errors()->toArray()['image'][0]], 400);
                }
                
                $image = $request->file('image');
                $name = $user->id.'-'.time().'.'.$image->getClientOriginalExtension();
                if($image->getClientOriginalExtension() != "pdf")
                {
                    $path = 'storage/support/ticket-'.$ticket->id.'/images/'.$user->id.'/';
                } else {
                    $path = 'storage/support/ticket-'.$ticket->id.'/documents/'.$user->id.'/';
                }
                $image->move(public_path($path), $name);
                MessageTicket::create([
                    'support_id' => '1',
                    'ticket_id' => $ticket->id,
                    'user_id' => $user->id,
                    'type' => $user->admin,
                    'message' => request('message'),
                    'image' => $path.$name
                ]);
            } 
            else {
                MessageTicket::create([
                    'support_id' => '1',
                    'ticket_id' => $ticket->id,
                    'user_id' => $user->id,
                    'type' => $user->admin,
                    'message' => request('message'),
                ]);
            }
    
            return response()->json(['status' => 'success', 'message' => 'Message sended!'], 200);
        }
        return abort(403, 'You can not reply to this ticket!');
    }

    public function closeTicket(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if ($user->admin == '1') {
            $ticket = Ticket::find($request->ticket_id);
            $ticket->update(['status' => 1]);

            return response()->json(['status' => 'success', 'message' => 'Message closed!'], 200);
        }
        return abort(403, 'You can not close tickets!');
    }
}