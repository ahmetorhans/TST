<?php

namespace App\Http\Controllers;

use JWTAuth;
use Request;

class Authentication extends Controller
{

    /**
     * Jwt Login
     *
     * @return json
     */
    public function login()
    {

        //post'dan gelen email ve password değerlerini kontrol et. Doğruysa token oluştur ve gönder

        $credentials = Request::only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['status' => false, 'msg' => 'Bilgiler hatalı']);
            }
        } catch (JWTException $e) {

            return response()->json(['status' => false, 'msg' => 'Token oluşturulamadı'], 500);
        }
        return response()->json(compact('token'));
    }

}
