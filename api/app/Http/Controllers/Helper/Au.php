<?php
namespace App\Http\Controllers\Helper;
use App\Http\Controllers\Controller;
use JWTAuth;
use Request;
class Au extends Controller
{
    
    public function user()
    {

        $access_token = Request::header('Authorization');
        $user = JWTAuth::toUser(substr($access_token,7));
        return $user;
    }
}
