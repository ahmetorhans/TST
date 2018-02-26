<?php
 
namespace App\Http\Middleware;
 
use Closure;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Request;
 
class VerifyJWTToken
{
    public function handle($request, Closure $next)
    {
        try{
            //Bilgiler get ile gelecekse Request::input('token') olacaktı. 
            // Angular'da token bilgisini Authorization header değeri ile göndereceğiz
            $access_token = Request::header('Authorization');
 
            //Herşey yolundaysa token değeri ile user bilgilerini çekebiliriz.
            $user = JWTAuth::toUser($access_token);
       
        }catch (JWTException $e) {
 
            //Token değeri hatalıysa ..
            if($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json(['token_expired'], $e->getStatusCode());
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json(['token_invalid'], $e->getStatusCode());
            }else{
                return response()->json(['error'=>'Token Gerekli']);
            }
        }
       return $next($request);
    }
}