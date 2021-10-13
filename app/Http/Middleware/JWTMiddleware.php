<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use App\Http\Traits\HttpResponse;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;



class JWTMiddleware extends BaseMiddleware
{
    use HttpResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            if(!$request->bearerToken()){
                if($request->hasCookie('_token')){
                    $token = $request->cookie('_token');
                    $request->headers->add([
                        'Authorization' => 'Bearer' . $token
                    ]);
                }
            }
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            // if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
            //     return response()->json(['status' => 'Token is Invalid']);
            // }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
            //     return response()->json(['status' => 'Token is Expired']);
            // }else{
            //     return response()->json(['status' => 'Authorization Token not found']);
            // }
            return $this->failure('Please Specify Your Credentials');
        }
        return $next($request);
    }
}
