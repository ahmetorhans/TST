<?php

namespace App\Http\Controllers;

use App\User;
use App\Yetki;
use App\yetkiDefault;
use DB;
use JWTAuth;
use Request;
use Validator;

class Users extends Controller
{
    /**
     * yeni kullanıcı ekle
     *
     * @return json array
     */
    public function newUser()
    {

        $sonuc = Request::all();

        //id varsa update et..
        if (isset($sonuc['id'])) {
            return $this->updateUser();
        }

        //gerekli alanlar
        $validator = Validator::make(Request::all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            $e = $validator->errors();
            return response()->json(array('status' => false, 'msg' => $e));
        }

        //password değerini şifrelememiz gerekiyor. Laravel auth işlemlerinde default olarak bcrypt ile şifrelenmiş değere bakıyor
        $sonuc['password'] = bcrypt($sonuc['password']);
        

        //kullanıcımızı oluşturalım.
        $user = User::create($sonuc);
        
        $user = $user->toArray();

        $yetkiler = Request::input('yetkiler');

        foreach ($yetkiler as $yetki) {
            $yetki['user_id'] = $user['id'];
            $yetki['id'] = null;

            Yetki::create($yetki);
        }
        return response()->json(array('status' => true, 'msg' => 'Kayıt eklendi'));
    }

    /**
     * Kullanıcı düzelt
     *
     * @return json array
     */
    public function updateUser()
    {

        $user = User::find(Request::input('id'));

        $validator = Validator::make(Request::all(), [
            'name' => 'required|min:3,',
            'email' => 'required|email|unique:users,email,' . $user['id'],

        ]);

        if ($validator->fails()) {

            $e = $validator->errors();

            return response()->json(array('status' => false, 'msg' => $e));
        }

        $input = Request::all();

        if (!empty($input['password'])) {
            $input['password'] = bcrypt(Request::input('password'));
        }

        DB::table('yetkis')->where('user_id', '=', $user['id'])->delete();

        $yetkiler = Request::input('yetkiler');
        foreach ($yetkiler as $yetki) {
            $yetki['user_id'] = $user['id'];
            $yetki['id'] = null;

            Yetki::create($yetki);
        }
        /*
        $yetkiler = Request::input('yetkiler');
        foreach ($yetkiler as $yetki) {
        $yetkiObj = Yetki::find($yetki['id']);

        $yetkiObj->fill($yetki)->save();
        }
         */

        $input['role'] = ($user['role'] == 'super') ? 'super' : $input['role']; 
        
        

        $user->fill($input)->save();

        //kullanıcımızı oluşturalım.
        return response()->json(array('status' => true, 'msg' => 'İşlem Tamam'));
    }

    /**
     * Kullanıcı listesi
     *
     * @return json array
     */
    public function listUser()
    {

        $users = User::orderBy('id', 'DESC')->where('musteri','!=',"1")->with('yetkiler')->get();
        return response()->json($users);

    }

    public function listeleTeknisyen()
    {

        $users = User::orderBy('id', 'DESC')->where('musteri','!=',"1")->get(['id as value','name as label']);
        return response()->json($users);

    }

    public function yetkiDefault()
    {

        $user = User::get();

        $yetkiler = yetkiDefault::all('bolum', 'bolumAdi')->toArray();

        foreach ($user as $val) {

            foreach ($yetkiler as $ny) {

                $yetki = Yetki::where('user_id', '=', $val['id'])
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

    public function deleteUser()
    {
        $id = Request::input('id');

        $user = User::find($id);
       
        if ($user['role'] != 'super') {
           $sonuc = $user->delete();
        }else{
            $sonuc=0;
        }
        
        if ($sonuc) {
            return response()->json(array('status' => true, 'msg' => 'Kayıt Silindi'));
        } else {
            return response()->json(array('status' => false, 'msg' => 'Kayıt Silinemedi'));
        }
    }

    public function yetki()
    {
       //default yetkilerler db'yi donat
        $this->yetkiDefault();

        $bolum = Request::input('bolum');
        $access_token = Request::header('Authorization');
        $user = JWTAuth::toUser(substr($access_token, 7));
        $yetkiler = User::
            leftJoin('yetkis', 'users.id', '=', 'yetkis.user_id')
            ->where('users.id', '=', $user['id'])
            ->where('yetkis.bolum', '=', $bolum)
            ->first(['role', 'giris', 'bolum', 'bolumAdi', 'yeni', 'duzelt', 'sil']);

            
        return response()->json($yetkiler);

    }
}
