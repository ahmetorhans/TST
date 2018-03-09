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
    public function updateCihaz ()
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
    public function listCihaz()
    {
        $cihaz = \App\Cihaz::
             leftJoin('caris', 'cihazs.cari_id', '=', 'caris.id')
            ->orderBy('cihazs.id', 'DESC')
            ->get(['cihazs.id','cihazs.adi','marka','model','serino','cihazs.aciklama','caris.adi as cariAdi']);
      

        return response()->json($cihaz);

    }

    public function getCihaz($id){
       
        return response()->json(\App\Cihaz::find($id));
    }

    public function deleteCihaz()
    {
        $cihaz = \App\Cihaz::find( Request::input('id'));
        $cihaz->delete();
        return response()->json(array('status' => true, 'msg' => 'Kayıt Silindi','id'=>  $cihaz->id));

    }

}
