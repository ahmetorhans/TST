<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Guard;
use Request;
use Validator;

class Urun extends Controller
{
    /**
     * Ekle
     *
     * @return json array
     */
    public function urunKaydet()
    {

        if (Guard::yetki('urun')->yeni !== '1') {
            return response()->json(array('status' => false, 'guard' => false, 'msg' => 'Erişim yok!'), 401);
        }

        $sonuc = Request::all();

        //id varsa update et..
        if (isset($sonuc['id'])) {
            return $this->urunGuncelle();
        }

        $validator = Validator::make(Request::all(), [
            'stok_kodu' => 'required',
        ]);

        if ($validator->fails()) {
            $e = $validator->errors();
            return response()->json(array('status' => false, 'msg' => $e));
        }

        $urun = new \App\Urunler;

        $urun->fill($sonuc)->save();

        return response()->json(array('status' => true, 'msg' => 'Kayıt eklendi'));
    }

    /**
     * Düzelt
     *
     * @return json array
     */
    public function urunGuncelle()
    {

        $urun = \App\Urunler::find(Request::input('id'));

        if (Guard::yetki('urun')->duzelt !== '1') {
            return response()->json(array('status' => false, 'guard' => false, 'msg' => 'Erişim yok!'), 401);
        }

        $input = Request::all();

        $validator = Validator::make(Request::all(), [
            'stok_kodu' => 'required',
        ]);

        if ($validator->fails()) {
            $e = $validator->errors();
            return response()->json(array('status' => false, 'msg' => $e));
        }

        $urun->fill($input)->save();

        return response()->json(array('status' => true, 'msg' => 'İşlem Tamam'));
    }

    /**
     * Liste
     *
     * @return json array
     */
    public function urunListele()
    {

        $urun = \App\Urunler::all();
        return response()->json($urun);

    }

    public function urunGetir($id)
    {
        $urun = \App\Urunler::find($id);

        return response()->json($urun);
    }

    /**
     * Delete
     *
     * @return json array
     */
    public function urunSil()
    {
        $urun = \App\Urunler::find(Request::input('id'));

        if (Guard::yetki('urun')->sil !== '1') {
            return response()->json(array('status' => false, 'guard' => false, 'msg' => 'Erişim yok!'), 401);
        }

        $urun->delete();
        return response()->json(array('status' => true, 'msg' => 'Kayıt Silindi', 'id' => $urun->id));

    }

    public function xlsSifirla()
    {

        if (Guard::mertebe() !== 'admin') {
            return response()->json(array('status' => false, 'guard' => false, 'msg' => 'Erişim yok!'), 401);
        }
        $kayit = \App\Urunler::where('otoKayit', '1');
        $kayit->delete();
        return response()->json(array('status' => true, 'msg' => 'İşlem Tamam'));
    }
    public function xlsKaydet()
    {
        if (Guard::mertebe() !== 'admin') {
            return response()->json(array('status' => false, 'guard' => false, 'msg' => 'Erişim yok!'), 401);
        }
        $kayit = [];
        foreach (Request::all() as $key => $value) {
            $kayit['stok_kodu'] = $value[0];
            $kayit['aciklama'] = $value[1];
            $kayit['bayiFiyat'] = $value[2];
            $kayit['fiyat'] = $value[3];
            $kayit['marka'] = $value[4];
            $kayit['model'] = $value[5];
            $kayit['ekBilgi'] = $value[6];
            $kayit['otoKayit'] = 1;
            $urun = \App\Urunler::where('stok_kodu', $kayit['stok_kodu'])->first();
            if (!$urun) {
                $ekle = new \App\Urunler;
                $ekle->fill($kayit)->save();

            } else {
                
                    $urun->fill($kayit)->save();
                

            }
        }

        return response()->json(array('status' => true, 'msg' => 'İşlem Tamam'));

    }

}
