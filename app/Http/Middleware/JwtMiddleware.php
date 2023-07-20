<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $token = request()->bearerToken();
        
        $url = config('services.backend_auth.base_uri');
        // Enviamos la data al backend auth para actualizarla
        $response = Http::withHeaders([
            'apikey' => config('services.backend_auth.key'),
            'Authorization' => 'Bearer ' . $token,
            ])->get("{$url}me");
            
            if($response->successful()){
                
                $resObj = $response->object();
                $user = User::where('email', $resObj->user->email)->first();
                
                if($user) 
                {
                    if($user->token_jwt === $token) {
                    $request['auth_user_id'] = $user->id;
                    return $next($request);
                }
            }
        }
        return response()->json(['status' => 'error', 'message' => 'Invalid or expired token'], 401);
    }
}
