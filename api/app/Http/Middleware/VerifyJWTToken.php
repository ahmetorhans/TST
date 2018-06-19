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
            $user = JWTAuth::toUser(substr($access_token,7));
         
            
       
        }catch (JWTException $e) {
 
            //Token değeri hatalıysa ..
            if($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json(['token_expired'], 401);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json(['token_invalid'], 401);
            }else{
                return response()->json(['status'=>false, 'msg'=>'Token Gerekli'],401);
            }
        }
        if (empty($user->id)){
            return response()->json(['status'=>false, 'msg'=>'Hatalı Kullanıcı'],401);
        }
        $request->attributes->set('gUser', $user);

       return $next($request);
    }
}