<?php

namespace App\Http\Controllers;

use App\User;
use App\yetkiDefault;
use DB;
use JWTAuth;
use Request;

class Guard extends Controller
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

    /**
     *
     * genel mertebe
     * admin : tüm kayıtlar
     * user : sadece kendi kayıtları
     * guest : key ile belli bir kayıt
     *
     * @return string
     */
    public static function mertebe()
    {

        $user = Request::get('gUser');
        if ($user->musteri === '1') {
            return 'musteri';
        } else {
            if ($user->role === 'admin' || $user->role === 'super') {
                return 'admin';
            } else {
                return 'user';
            }
        }
        return false;
    }
/**
 * Yetki function
 * Bölüm bazlı yetkiler ve kullanıcı rolü..
 *
 *
 * @param string $bolum hangi bolume ait
 * @return void string
 */
    public static function yetki($bolum = '')
    {

        self::yetkiDefault();

        $user = Request::get('gUser');

        if ($user->musteri === '1') {
            $user['userGroup'] = 'musteri';
        } else {
            $user['userGroup'] = 'kendi';
        }

        $yetkiler = User::
            leftJoin('yetkis', 'users.id', '=', 'yetkis.user_id')
            ->where('users.id', '=', $user['id'])
            ->get(['role', 'giris', 'bolum', 'bolumAdi', 'yeni', 'duzelt', 'sil'])
            ->keyBy('bolum');

        
        if ($user['role'] === 'super') {
            $arr = [];
            foreach ($yetkiler->toArray() as $key => $val) {

                foreach ($val as $k => $v) {
                    if ($k == 'bolum' || $k == 'bolumAdi' || $k == 'role') {
                        $arr[$key][$k] = $v;
                    } else {
                        $arr[$key][$k] = '1';
                    }

                }
            }

            $arr['userGroup'] = 'kendi';
            $arr['name'] = $user['name'];
            $sonuc = empty($bolum) ? (object) $arr : (object) $arr[$bolum];

        } else {
            $yetkiler['userGroup'] = $user['userGroup'];
            $yetkiler['name']=$user['name'];
            $sonuc = empty($bolum) ? $yetkiler : $yetkiler[$bolum];
        }
        return $sonuc;

    }

    private static function yetkiDefault()
    {

        $user = User::get();

        $yetkiler = yetkiDefault::all('bolum', 'bolumAdi')->toArray();

        foreach ($user as $val) {

            foreach ($yetkiler as $ny) {

                $yetki = \App\Yetki::where('user_id', '=', $val['id'])
                    ->where('bolum', '=', $ny['bolum'])
                    ->get();

                if (count($yetki) == 0) {
                    DB::table('yetkis')->insert(
                        ['bolum' => $ny['bolum'], 'user_id' => $val['id'], 'bolumAdi' => $ny['bolumAdi']]
                    );
                }

            }

        }
        $yetki = \App\Yetkidefault::orderBy('id', 'DESC')->get();
        return response()->json($yetki);
    }

}
