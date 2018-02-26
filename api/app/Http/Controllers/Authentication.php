<?php
 
namespace App\Http\Controllers;
 
use Request;
use JWTAuth;
use App\User;
 
class Authentication extends Controller
{
 
	public function register(Request $request){
	    //angular üzerinden form verisi bilgileri json olarak gönderdiği için biz de json->all() ile çekiyoruz.    
        $sonuc = Request::json()->all();
 
        //password değerini şifrelememiz gerekiyor. Laravel auth işlemlerinde default olarak bcrypt ile şifrelenmiş değere bakıyor
        $sonuc['password']=bcrypt($sonuc['password']);
 
        //kullanıcımızı oluşturalım.
        return User::create($sonuc);
	}
 
    public function login()
    {
        
        //post'dan gelen email ve password değerlerini kontrol et. Doğruysa token oluştur ve gönder
 
    	$credentials = Request::only('email', 'password');
        try {
        
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Bilgiler yanlış'], 401);
            }
        } catch (JWTException $e) {
        
            return response()->json(['error' => 'token oluşturulamadı'], 500);
        }
        return response()->json(compact('token'));
    }

    public function loginTest()
    {
        
        //post'dan gelen email ve password değerlerini kontrol et. Doğruysa token oluştur ve gönder
 
    	$credentials = array('email'=>'a@a.com','password'=>'123456');
        try {
        
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Bilgiler yanlış'], 401);
            }
        } catch (JWTException $e) {
        
            return response()->json(['error' => 'token oluşturulamadı'], 500);
        }
        return response()->json(compact('token'));
    }
 
   
}