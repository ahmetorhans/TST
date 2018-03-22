<?php

namespace App\Http\Controllers;

use JWTAuth;
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

       // print_r(Request::get('gUser'));
        if (Request::get('gUser')->musteri === '1') {
            $servisler = \App\Servis::
                leftJoin('cihazs', 'servis.cihaz_id', '=', 'cihazs.id')
                ->leftJoin('caris', 'servis.cari_id', '=', 'caris.id')
                ->leftJoin('durum', 'servis.islemDurumu', '=', 'durum.value')
                ->where('caris.user_id',Request::get('gUser')->id)
                ->orderBy('id', 'DESC')
                ->get(['servis.id', 'caris.adi AS cariAdi', 'caris.telefon', 'cihazs.adi', 'cihazs.model', 'cihazs.serino', 'cihazs.marka', 'servis.aciklama', 'servis.islemDurumu', 'durum.label as islemDurumLabel', 'durum.icon', 'servis.tarih', 'caris.id as cari_id']);
        } else {
            $servisler = \App\Servis::
                leftJoin('cihazs', 'servis.cihaz_id', '=', 'cihazs.id')
                ->leftJoin('caris', 'servis.cari_id', '=', 'caris.id')
                ->leftJoin('durum', 'servis.islemDurumu', '=', 'durum.value')
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
    public function servisGetir($id)
    {

        $servisler = \App\Servis::find($id);
        $servisler->getCari;
        $servisler->getCihaz;
        $servisler->getIslem;
        $servisler->getDurum;
        $servisler->getTeknisyen;

        //$islemDurumlari= \App\Durum::get();
        //$teknisyenler=  \App\User::orderBy('id', 'DESC')->where('musteri','!=',"1")->get(['id as value','name as label']);

        return response()->json(array('servis' => $servisler));
    }
    /**
     * Delete
     *
     * @return json array
     */
    public function servisSil()
    {

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
        $users = new \App\Http\Controllers\Users;
        $data['yetkiler'] = $users->yetkiler();

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

        $islem->fill($sonuc)->save();

        return response()->json(array('status' => true, 'msg' => 'Kayıt eklendi', 'islemSonuc' => $islem));
    }

}
