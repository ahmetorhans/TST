<?php

namespace App\Http\Controllers;

use App\User;
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
    public function cihazGuncelle ()
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
    public function cihazListele()
    {
        $cihaz = \App\Cihaz::
             leftJoin('caris', 'cihazs.cari_id', '=', 'caris.id')
            ->orderBy('cihazs.id', 'DESC')
            ->get(['cihazs.id','cihazs.adi','marka','model','serino','cihazs.aciklama','caris.adi as cariAdi','barkod','caris.id as cari_id']);
      

        return response()->json($cihaz);

    }

    public function cihazGetir($id){
        $cihaz =\App\Cihaz::find($id);
        $cihaz->cari; 
        return response()->json($cihaz);
    }

    public function listShortCihaz()
    {
        $cihaz = \App\Cihaz::
            orderBy('id', 'asc')
            ->get(['id', 'adi','marka','model','serino']);

        return response()->json($cihaz);

    }

    public function listShortCihazId($id)
    {
        $cihaz = \App\Cihaz::
            orderBy('id', 'asc')
            ->where('cari_id',$id)
            ->get(['id', 'adi','marka','model','serino','aciklama','lokasyon']);

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

        $cihaz->delete();
        return response()->json(array('status' => true, 'msg' => 'Kayıt Silindi','id'=>  $cihaz->id));

    }

}
