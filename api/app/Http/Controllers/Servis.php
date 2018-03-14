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
    public function yeniServis()
    {

        $sonuc = Request::all();

        //id varsa update et..
        if (isset($sonuc['id'])) {
            return $this->guncelleServis();
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

        $servis->fill($sonuc)->save();

        return response()->json(array('status' => true, 'msg' => 'Kayıt eklendi'));
    }

    /**
     * Düzelt
     *
     * @return json array
     */
    public function guncelleServis()
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

        $servis->fill($input)->save();

        return response()->json(array('status' => true, 'msg' => 'İşlem Tamam'));
    }

    /**
     * Liste
     *
     * @return json array
     */
    public function listeServis()
    {
        $servisler = \App\Servis::
            leftJoin('cihazs', 'servis.cihaz_id', '=', 'cihazs.id')
            ->leftJoin('caris', 'servis.cari_id', '=', 'caris.id')
            ->leftJoin('durum', 'servis.islemDurumu', '=', 'durum.value')
            ->orderBy('id','DESC')
            ->get(['servis.id','caris.adi AS cariAdi','caris.telefon','cihazs.adi','cihazs.model','cihazs.serino','cihazs.marka','servis.aciklama','servis.islemDurumu','durum.label as islemDurumLabel','durum.icon','servis.created_at as tarih']);

        return response()->json($servisler);
    }

    /*public function listeleIslemDurumlari(){
        return \App\Durum::get();
    }*/
    public function getirServis($id)
    {

        $servisler = \App\Servis::find($id);
        $servisler->getCari;
        $servisler->getCihaz;

        $islemDurumlari= \App\Durum::get();
        $teknisyenler=  \App\User::orderBy('id', 'DESC')->where('musteri','!=',"1")->get(['id as value','name as label']);

        return response()->json(array( 'servis' => $servisler,'teknisyenler'=>$teknisyenler,'islemDurumlari'=>$islemDurumlari));
    }
    /**
     * Delete
     *
     * @return json array
     */
    public function silServis()
    {

        $servis = \App\Servis::find(Request::input('id'));

        $servis->delete();
        return response()->json(array('status' => true, 'msg' => 'Kayıt Silindi', 'id' => $servis->id));

    }

}
