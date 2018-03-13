<?php

namespace App\Http\Controllers;

use Request;
use Validator;

class Servis extends Controller
{
    /**
     * Ekle
     *
     * @return json array
     */
    public function newCihaz()
    {

        $sonuc = Request::all();

        //id varsa update et..
        if (isset($sonuc['id'])) {
            return $this->updateCihaz();
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
    public function updateCihaz()
    {

        $cihaz = \App\Cihaz::find(Request::input('id'));

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
    public function listServis()
    {
        $servisler = \App\Servis::
            leftJoin('cihazs', 'servis.cihaz_id', '=', 'cihazs.id')
            ->leftJoin('caris', 'servis.cari_id', '=', 'caris.id')
            ->get(['servis.id','caris.adi AS cariAdi','caris.telefon','cihazs.adi','cihazs.model','cihazs.serino','cihazs.marka','servis.aciklama','servis.islemDurumu']);

        return response()->json($servisler);
    }

    public function getServis($id)
    {

        $servisler = \App\Servis::find($id);

        return response()->json(array('cari' => $servisler->getCari, 'cihaz' => $servisler->getCihaz, 'servis' => $servisler));
    }
    /**
     * Delete
     *
     * @return json array
     */
    public function deleteCihaz()
    {

        $cihaz = \App\Cihaz::find(Request::input('id'));

        $cihaz->delete();
        return response()->json(array('status' => true, 'msg' => 'Kayıt Silindi', 'id' => $cihaz->id));

    }

}
