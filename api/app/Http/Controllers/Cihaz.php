<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Guard;
use Request;
use Validator;

class Cihaz extends Controller
{
    /**
     * Ekle
     *
     * @return json array
     */
    public function cihazKaydet()
    {

        if (Request::get('gUser')->musteri === '1') {
            $cari = \App\Cari::where('user_id', Request::get('gUser')->id)->first(['id']);
            Request::merge(['cari_id' => $cari['id']]);
            Request::merge(['musteriKaydi' => 1]);
        } else {
            if (Guard::yetki('cihaz')->yeni !== '1') {
                return response()->json(array('status' => false, 'guard' => false, 'msg' => 'Erişim yok!'), 401);
            }
        }

        $sonuc = Request::all();

        //id varsa update et..
        if (isset($sonuc['id'])) {
            return $this->cihazGuncelle();
        }

        $validator = Validator::make(Request::all(), [
            'cari_id' => 'required',
        ]);

        if ($validator->fails()) {
            $e = $validator->errors();
            return response()->json(array('status' => false, 'msg' => $e));
        }

        $cihaz = new \App\Cihaz;

        $cihaz->fill($sonuc)->save();

        return response()->json(array('status' => true, 'msg' => 'Kayıt eklendi'));
    }

    /**
     * Düzelt
     *
     * @return json array
     */
    public function cihazGuncelle()
    {

        $cihaz = \App\Cihaz::find(Request::input('id'));

        if (Request::get('gUser')->musteri === '1') {
            $cari = \App\Cari::where('user_id', Request::get('gUser')->id)->first(['id']);
            if ($cari['id'] != $cihaz['cari_id']) {
                return response()->json(array('status' => false, 'guard' => false, 'msg' => 'Erişim yok!'), 401);
            }
        } else {
            if (Guard::yetki('cihaz')->duzelt !== '1') {
                return response()->json(array('status' => false, 'guard' => false, 'msg' => 'Erişim yok!'), 401);
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

        $cihaz->fill($input)->save();

        return response()->json(array('status' => true, 'msg' => 'İşlem Tamam'));
    }

    /**
     * Liste
     *
     * @return json array
     */
    public function cihazListele()
    {

        if (Guard::mertebe() === 'musteri') {
            $cihaz = \App\Cihaz::
                leftJoin('caris', 'cihazs.cari_id', '=', 'caris.id')
                ->where('caris.user_id', Request::get('gUser')->id)
                ->orderBy('cihazs.id', 'DESC')
                ->get(['cihazs.id', 'cihazs.adi', 'marka', 'model', 'serino', 'cihazs.aciklama', 'caris.adi as cariAdi', 'barkod', 'caris.id as cari_id', 'musteriKaydi']);

        } else {
            $cihaz = \App\Cihaz::
                leftJoin('caris', 'cihazs.cari_id', '=', 'caris.id')
                ->orderBy('cihazs.id', 'DESC')
                ->get(['cihazs.id', 'cihazs.adi', 'marka', 'model', 'serino', 'cihazs.aciklama', 'caris.adi as cariAdi', 'barkod', 'caris.id as cari_id', 'musteriKaydi']);

        }

        return response()->json($cihaz);

    }

    public function cihazListeleCari($cari_id)
    {
        $cihaz = \App\Cihaz::
            leftJoin('caris', 'cihazs.cari_id', '=', 'caris.id')
            ->where('cihazs.cari_id', $cari_id)
            ->orderBy('cihazs.id', 'DESC')
            ->get(['cihazs.id', 'cihazs.adi', 'marka', 'model', 'serino', 'cihazs.aciklama', 'caris.adi as cariAdi', 'barkod', 'caris.id as cari_id']);

        return response()->json($cihaz);

    }

    public function cihazGetir($id)
    {
        $cihaz = \App\Cihaz::find($id);

        $cihaz->cari;
        return response()->json($cihaz);
    }

    public function listShortCihaz()
    {
        $cihaz = \App\Cihaz::
            orderBy('id', 'asc')
            ->get(['id', 'adi', 'marka', 'model', 'serino']);

        return response()->json($cihaz);

    }

    public function listShortCihazId($id)
    {
        $cihaz = \App\Cihaz::
            orderBy('id', 'asc')
            ->where('cari_id', $id)
            ->get(['id', 'adi', 'marka', 'model', 'serino', 'aciklama', 'lokasyon']);

        return response()->json($cihaz);

    }
    /**
     * Delete
     *
     * @return json array
     */
    public function cihazSil()
    {
        $cihaz = \App\Cihaz::find(Request::input('id'));

        if (Guard::mertebe() == 'musteri') {
            $cari = \App\Cari::where('user_id', Request::get('gUser')->id)->first(['id']);
            if ($cari['id'] != $cihaz['cari_id']) {
                return response()->json(array('status' => false, 'guard' => false, 'msg' => 'Erişim yok!'), 401);
            }
        } else {
            if (Guard::yetki('cihaz')->sil !== '1') {
                return response()->json(array('status' => false, 'guard' => false, 'msg' => 'Erişim yok!'), 401);
            }
        }

        $cihaz->delete();
        return response()->json(array('status' => true, 'msg' => 'Kayıt Silindi', 'id' => $cihaz->id));

    }

}
