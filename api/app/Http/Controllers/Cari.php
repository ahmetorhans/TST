<?php

namespace App\Http\Controllers;

use App\User;
use Request;
use Validator;

class Cari extends Controller
{

    /**
     * yeni kullanıcı ekle
     *
     * @return json array
     */
    public function cariKaydet()
    {

        $sonuc = Request::all();

        //id varsa update et..
        if (isset($sonuc['id'])) {
            return $this->cariGuncelle();
        }

        

        //kullanıcı kaydı açılacak  mı?
        if (!empty(Request::input('email')) && !empty(Request::input('password'))) {
            $validator = Validator::make(Request::all(), [
                'adi' => 'required|min:3',
                'email' => 'required|email|unique:users',
                'password' => 'required',
            ]);
        } else {
            $validator = Validator::make(Request::all(), [
                'adi' => 'required|min:3',
            ]);
        }

        if ($validator->fails()) {
            $e = $validator->errors();
            return response()->json(array('status' => false, 'msg' => $e));
        }

        //email ve password varsa yeni kullanıcı aç
        if (!empty(Request::input('email')) && !empty(Request::input('password'))) {

            $sonuc['password'] = bcrypt($sonuc['password']);

            $user = new User;
            $user->name = $sonuc['adi'];
            $user->email = $sonuc['email'];
            $user->password = $sonuc['password'];
            $user->musteri = "1";

            //kullanıcımızı oluşturalım.
            $user->save();
            $sonuc['user_id'] = $user->id;
        }

        $cari = new \App\Cari;
        $cari->fill($sonuc)->save();

        return response()->json(array('status' => true, 'msg' => 'Kayıt eklendi'));
    }

    /**
     * Kullanıcı düzelt
     *
     * @return json array
     */
    public function cariGuncelle()
    {

        $cari = \App\Cari::find(Request::input('id'));

        $input = Request::all();

        if (!empty($input['password'])) {
            $input['password'] = bcrypt(Request::input('password'));
        }

        $input['name'] = $input['adi'];

        $validator = Validator::make(Request::all(), [
            'adi' => 'required|min:3,',
        ]);

        if ($validator->fails()) {
            $e = $validator->errors();
            return response()->json(array('status' => false, 'msg' => $e));
        }

        $cari->fill($input)->save();

        //cari için user kaydı var users update et..
        if (isset($cari->user_id)) {

            $user = User::find($cari->user_id);

            $validator = Validator::make(Request::all(), [
                'adi' => 'required|min:3,',
                'email' => 'required|email|unique:users,email,' . $user['id'],

            ]);

            if ($validator->fails()) {
                $e = $validator->errors();
                return response()->json(array('status' => false, 'msg' => $e));
            }

            $user->fill($input)->save();
        } else {
            //user kaydı yok..
            //login ol seçilmişsee.
            if (!empty(Request::input('email')) && !empty(Request::input('password'))) {
                $validator = Validator::make(Request::all(), [
                    'adi' => 'required|min:3,',
                    'email' => 'required|email|unique:users',
                    'password' => 'required',

                ]);

                if ($validator->fails()) {
                    $e = $validator->errors();
                    return response()->json(array('status' => false, 'msg' => $e));
                }
                $user = new User;
                $user->musteri = "1";
                $user->fill($input)->save();

                //kullanıcı eklendi. Cariyi güncelle..
                $cariUserId['user_id'] = $user->id;
                $cari->fill($cariUserId)->save();

            }
        }

        //kullanıcımızı oluşturalım.
        return response()->json(array('status' => true, 'msg' => 'İşlem Tamam'));
    }

    /**
     * Kullanıcı listesi
     *
     * @return json array
     */
    public function cariListele()
    {
        $cari = \App\Cari::
            leftJoin('users', 'caris.user_id', '=', 'users.id')
            ->orderBy('caris.id', 'DESC')
            ->get(['caris.id', 'adi',  'yetkili','adres','telefon']);

        return response()->json($cari);

    }
    public function cariGetir($id)
    {
        $cari = \App\Cari::
            leftJoin('users', 'caris.user_id', '=', 'users.id')
            ->orderBy('caris.id', 'asc')
            ->where('caris.id',$id)
            ->first(['caris.id', 'adi', 'adres', 'telefon', 'email', 'eposta','vergiNo', 'vergiDairesi', 'vergiNo', 'yetkili', 'aciklama', 'durum', 'login','turu','user_id']);

        return response()->json($cari);

    }

    public function listShortCari()
    {
        $cari = \App\Cari::
            orderBy('id', 'asc')
            ->get(['id', 'adi','telefon','yetkili']);

        return response()->json($cari);

    }

    public function cariSil()
    {
        $id = Request::input('id');

        $cari = \App\Cari::find($id);
        $cari->delete();

        if ($cari->user_id) {
            $user = \App\User::find($cari->user_id);
            $user->delete();
        }

        return response()->json(array('status' => true, 'msg' => 'Kayıt Silindi'));

    }

}
