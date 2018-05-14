<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Guard;
use Intervention\Image\ImageManagerStatic as Image;
use JWTAuth;
//use Illuminate\Http\Request as Req;
use Mail;
use Request;
use Validator;

class Servis extends Controller
{

    /**
     * Ekle
     *
     * @return json array
     */
    public function servisKaydet()
    {

        if (Request::get('gUser')->musteri === '1') {
            $cari = \App\Cari::where('user_id', Request::get('gUser')->id)->first(['id']);
            Request::merge(['cari_id' => $cari['id']]);
        } else {
            if (Guard::yetki('servis')->yeni !== '1') {
                return response()->json(array('status' => false, 'guard' => false, 'msg' => 'Erişim yok!'));
            }
        }

        $sonuc = Request::all();

        //id varsa update et..
        if (isset($sonuc['id'])) {
            return $this->servisGuncelle();
        }

        $validator = Validator::make(Request::all(), [
            'cari_id' => 'required',
        ]);

        $sonuc['islemDurumu'] = empty($sonuc['islemDurumu']) ? '1' : $sonuc['islemDurumu'];

        if ($validator->fails()) {
            $e = $validator->errors();
            return response()->json(array('status' => false, 'msg' => $e));
        }

        $servis = new \App\Servis;
        $servis->tarih = date('Y-m-d');
        $servis->key = md5(microtime());
        $servis->kullanici = Request::get('gUser')->id;
        $servis->fill($sonuc)->save();

        $access_token = Request::header('Authorization');
        $user = JWTAuth::toUser(substr($access_token, 7));

        $islem = new \App\Islem;
        $islem->servis_id = $servis->id;
        $islem->tarih = date('Y-m-d');
        $adi = \App\Durum::where('value', $sonuc['islemDurumu'])->first();
        $islem->adi = $adi->label;
        $islem->aciklama = 'Sistem tarafından eklendi';
        $islem->user = $user->name;
        $islem->save();

        if (Request::get('gUser')->musteri === '1') {
            $mail['aciklama'] = isset($sonuc['aciklama']) ? $sonuc['aciklama'] : '';
            $mail['sikayet'] = isset($sonuc['sikayet']) ? $sonuc['sikayet'] : '';
            $mail['cari'] = \App\Cari::where('id', $sonuc['cari_id'])->first(['adi', 'yetkili']);
            $mail['cihaz'] = array();

            if (!empty($sonuc['cihaz_id'])) {
                $mail['cihaz'] = \App\Cihaz::find($sonuc['cihaz_id'])->first();
            }

            $sendto = \App\User::where('role', 'super')->first();

            if ($sendto['bildirimToken']) {
                $this->sendNot($sendto['bildirimToken'], $servis->id, $mail['cari']['adi'] . ' ' . $mail['aciklama']);
            }

            Mail::send('servisMail', $mail, function ($message) use ($sendto) {
                $message->to($sendto['email'], '')->subject
                    ('Servis Talebi');
                $message->from('send@wmatik.com', 'Servis Takip');
            });
        }

        return response()->json(array('status' => true, 'islemler' => $islem, 'msg' => 'Kayıt eklendi'));
    }

    /**
     * Düzelt
     *
     * @return json array
     */
    public function servisGuncelle()
    {
        $servis = \App\Servis::find(Request::input('id'));

        if (Guard::mertebe() == 'musteri') {
            $cari = \App\Cari::where('user_id', Request::get('gUser')->id)->first(['id']);
            if ($cari['id'] != $servis['cari_id']) {
                return response()->json(array('status' => false, 'guard' => false, 'msg' => 'Erişim yok!'));
            }
        } else {
            if (Guard::yetki('servis')->duzelt !== '1') {
                return response()->json(array('status' => false, 'guard' => false, 'msg' => 'Erişim yok!'));
            }
        }

        $input = Request::all();

        $validator = Validator::make(Request::all(), [
            'cari_id' => 'required',
        ]);

        if ($validator->fails()) {
            $e = $validator->errors();
            return response()->json(array('status' => false, 'msg' => $e));
        }
        $islem = new \App\Islem;
        if ($servis['islemDurumu'] != $input['islemDurumu']) {
            $access_token = Request::header('Authorization');
            $user = JWTAuth::toUser(substr($access_token, 7));

            $islem->servis_id = $input['id'];
            $islem->tarih = date('Y-m-d');
            $adi = \App\Durum::where('value', $input['islemDurumu'])->first();
            $islem->adi = $adi->label;
            $islem->aciklama = 'Sistem tarafından eklendi';
            $islem->user = $user->name;
            $islem->save();

            //mail at

            $cari = \App\Cari::where('id', $input['cari_id'])->first();
            if (!empty($cari->eposta)) {
                $mail['aciklama'] = isset($input['aciklama']) ? $input['aciklama'] : '';
                $mail['sikayet'] = isset($input['sikayet']) ? $input['sikayet'] : '';
                $mail['islemAdi'] =  isset($adi->label) ? $adi->label : '';
                $mail['cari'] = $cari;//\App\Cari::where('id', $input['cari_id'])->first(['adi', 'yetkili']);
                $mail['cihaz'] = array();

                if (!empty($input['cihaz_id'])) {
                    $mail['cihaz'] = \App\Cihaz::find($input['cihaz_id'])->first();
                }

                $sendto = \App\User::where('role', 'super')->first();
             

                Mail::send('servisMusteri', $mail, function ($message) use ($sendto,$cari) {
                    $message->to($cari['eposta'], '')->subject
                        ('Servis İşlemi');
                    $message->from('send@wmatik.com', 'Servis Takip');
                });
            }

            //mail at--
        }
        $servis->fill($input)->save();

        return response()->json(array('status' => true, 'islemler' => $islem, 'msg' => 'İşlem Tamam'));
    }

    /**
     * Liste
     *
     * @return json array
     */
    public function servisListele()
    {
        //return response()->json(array('status' => false, 'guard' => false, 'msg' => 'Erişim yok!'),401);
        //  sadece kendi kayıtlarını getir..
        if (Guard::mertebe() === 'musteri') {
            $servisler = \App\Servis::
                leftJoin('cihazs', 'servis.cihaz_id', '=', 'cihazs.id')
                ->leftJoin('caris', 'servis.cari_id', '=', 'caris.id')
                ->leftJoin('durum', 'servis.islemDurumu', '=', 'durum.value')
                ->where('caris.user_id', Request::get('gUser')->id)
                ->orderBy('id', 'DESC')
                ->get(['servis.id', 'caris.adi AS cariAdi', 'caris.telefon', 'cihazs.adi', 'cihazs.model', 'cihazs.serino', 'cihazs.marka', 'servis.aciklama', 'servis.islemDurumu', 'durum.label as islemDurumLabel', 'durum.icon', 'servis.tarih', 'caris.id as cari_id', 'cihazs.lokasyon', 'cihazs.garantiTarih']);
        } else {
            if (Guard::yetki('servis')->giris !== '1') {
                return response()->json(array('status' => false, 'guard' => false, 'msg' => 'Erişim yok!'));
            }
            $servisler = \App\Servis::
                leftJoin('cihazs', 'servis.cihaz_id', '=', 'cihazs.id')
                ->leftJoin('caris', 'servis.cari_id', '=', 'caris.id')
                ->leftJoin('durum', 'servis.islemDurumu', '=', 'durum.value')
                ->orderBy('id', 'DESC')
                ->get(['servis.id', 'caris.adi AS cariAdi', 'caris.telefon', 'cihazs.adi', 'cihazs.model', 'cihazs.serino', 'cihazs.marka', 'servis.aciklama', 'servis.islemDurumu', 'durum.label as islemDurumLabel', 'durum.icon', 'servis.tarih', 'caris.id as cari_id', 'cihazs.lokasyon', 'cihazs.garantiTarih']);
        }

        return response()->json($servisler);
    }

    public function servisListeleKendisi()
    {
        if (Guard::mertebe() === 'musteri') {
            $servisler = \App\Servis::
                leftJoin('cihazs', 'servis.cihaz_id', '=', 'cihazs.id')
                ->leftJoin('caris', 'servis.cari_id', '=', 'caris.id')
                ->leftJoin('durum', 'servis.islemDurumu', '=', 'durum.value')
                ->where('caris.user_id', Request::get('gUser')->id)
                ->orderBy('id', 'DESC')
                ->get(['servis.id', 'caris.adi AS cariAdi', 'caris.telefon', 'cihazs.adi', 'cihazs.model', 'cihazs.serino', 'cihazs.marka', 'servis.aciklama', 'servis.islemDurumu', 'durum.label as islemDurumLabel', 'durum.icon', 'servis.tarih', 'caris.id as cari_id']);
        } else {
            if (Guard::yetki('servis')->giris !== '1') {
                return response()->json(array('status' => false, 'guard' => false, 'msg' => 'Erişim yok!'));
            }
            $servisler = \App\Servis::
                leftJoin('cihazs', 'servis.cihaz_id', '=', 'cihazs.id')
                ->leftJoin('caris', 'servis.cari_id', '=', 'caris.id')
                ->leftJoin('durum', 'servis.islemDurumu', '=', 'durum.value')
                ->where('teknisyen', Request::get('gUser')->id)
                ->orderBy('id', 'DESC')
                ->get(['servis.id', 'caris.adi AS cariAdi', 'caris.telefon', 'cihazs.adi', 'cihazs.model', 'cihazs.serino', 'cihazs.marka', 'servis.aciklama', 'servis.islemDurumu', 'durum.label as islemDurumLabel', 'durum.icon', 'servis.tarih', 'caris.id as cari_id']);
        }

        return response()->json($servisler);
    }

    public function servisListeleBekleyen()
    {
        $servisler = \App\Servis::
            leftJoin('cihazs', 'servis.cihaz_id', '=', 'cihazs.id')
            ->leftJoin('caris', 'servis.cari_id', '=', 'caris.id')
            ->leftJoin('durum', 'servis.islemDurumu', '=', 'durum.value')
            ->whereNotIn('durum.value', array(10, 8, 16))
            ->orderBy('id', 'DESC')
            ->get(['servis.id', 'caris.adi AS cariAdi', 'caris.telefon', 'cihazs.adi', 'cihazs.model', 'cihazs.serino', 'cihazs.marka', 'servis.aciklama', 'servis.islemDurumu', 'durum.label as islemDurumLabel', 'durum.icon', 'servis.tarih']);

        return response()->json($servisler);
    }

    public function islemDurumlariListele()
    {
        return \App\Durum::get();
    }
    public function servisGetirGuest($key)
    {

        $servisler = \App\Servis::where('key', $key)->first();

        $servisler->getCari;
        $servisler->getCihaz;
        $servisler->getIslem;
        $servisler->getDurum;
        $servisler->getTeknisyen;

        return response()->json(array('servis' => $servisler));
    }
    public function servisGetir($id)
    {

        $servisler = \App\Servis::find($id);

        if (Guard::mertebe() === 'musteri') {
            $cari = \App\Cari::where('user_id', Request::get('gUser')->id)->first(['id']);
            if ($servisler['cari_id'] != $cari['id']) {
                return response()->json(array('status' => false, 'guard' => false, 'msg' => 'Erişim yok!'));
            }
        }
        /*if (Guard::mertebe() == 'user') {
        if ($servisler['kullanici'] != Request::get('gUser')->id) {
        return response()->json(array('status' => false, 'guard' => false, 'msg' => 'Erişim yok!'),401);
        }
        }*/
        $servisler->getCari;
        $servisler->getCihaz;
        $servisler->getIslem;
        $servisler->getDurum;
        $servisler->getTeknisyen;

        return response()->json(array('servis' => $servisler));
    }
    /**
     * Delete
     *
     * @return json array
     */
    public function servisSil()
    {

        if (Guard::yetki('servis')->sil !== '1') {
            return response()->json(array('status' => false, 'guard' => false, 'msg' => 'Erişim yok!'));
        }

        $servis = \App\Servis::find(Request::input('id'));

        $servis->delete();
        return response()->json(array('status' => true, 'msg' => 'Kayıt Silindi', 'id' => $servis->id));

    }

    public function islemSil($id)
    {

        $islem = \App\Islem::find($id);
        $islem->delete();
        return response()->json(array('status' => true, 'msg' => 'Kayıt Silindi', 'id' => $islem->id));

    }

    public function servisInit()
    {

        $data['teknisyenler'] = \App\User::orderBy('id', 'DESC')->where('musteri', '!=', "1")->get(['id as value', 'name as label']);
        $data['islemDurumlari'] = \App\Durum::get();
        $data['cari'] = \App\Cari::where('user_id', Request::get('gUser')->id)->first(['id']);

        //$users = new \App\Http\Controllers\Users;
        // $data['yetkiler'] = $users->yetkiler();

        return response()->json($data);

    }
    public function islemKaydet()
    {

        $sonuc = Request::all();

        $validator = Validator::make(Request::all(), [
            'adi' => 'required',
        ]);

        if ($validator->fails()) {
            $e = $validator->errors();
            return response()->json(array('status' => false, 'msg' => $e));
        }

        $islem = new \App\Islem;
        $islem->tarih = date('Y-m-d');
        $access_token = Request::header('Authorization');
        $user = JWTAuth::toUser(substr($access_token, 7));
        $islem->user = $user->name;
        if (Request::input('cameraPhoto')) {
            $url = "cam-" . time() . ".jpg";
            $path = public_path('/app/files/' . $url);
            Image::make(file_get_contents(Request::input('cameraPhoto')))->resize(1000, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path, 75);
            $islem->photo = $url;
        }
        $islem->fill($sonuc)->save();

        return response()->json(array('status' => true, 'msg' => 'Kayıt eklendi', 'islemSonuc' => $islem));
    }
    private function sendNot($registrationIds, $servisId, $mesaj)
    {

        define('API_ACCESS_KEY', 'AAAAG-jSIgA:APA91bFgEXQj4mGNkegraQGDrbrAbgss_XqP5feX4OSFKcC0yKcyAqy2HpniVbt5NA1kRaL7jUPzBytArC7NOiE5OCl_bQP7sIesRrCew1vxz1gPEBGx22b489sEwwrxCtjsc_w6RTrJ');

        $msg = array
            (
            'body' => $mesaj,
            'title' => 'Servis Talebi',
            'icon' => 'icon',
            'sound' => 'mySound',
        );
        $data = array(
            "id" => $servisId,
        );
        $fields = array
            (
            'to' => $registrationIds,
            'notification' => $msg,
            'data' => $data,
        );

        $headers = array
            (
            'Authorization: key=' . API_ACCESS_KEY,
            'Content-Type: application/json',
        );
#Send Reponse To FireBase Server
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;

    }

}
