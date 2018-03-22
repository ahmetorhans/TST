<?php

namespace App\Http\Controllers;

use App\User;
use Request;
use Validator;
use JWTAuth;
class Randevu extends Controller
{
    /**
     * Ekle
     *
     * @return json array
     */
    public function randevuKaydet()
    {

        $sonuc = Request::all();

        //id varsa update et..
        if (isset($sonuc['id'])) {
            return $this->randevuGuncelle();
        }

        $validator = Validator::make(Request::all(), [
            'cari_id' => 'required',
            'aciklama' => 'required'
        ]);

        if ($validator->fails()) {
            $e = $validator->errors();
            return response()->json(array('status' => false, 'msg' => $e));
        }

        $randevu = new \App\Randevu;
        
        $access_token = Request::header('Authorization');
        $user = JWTAuth::toUser(substr($access_token,7));

        $randevu->kullanici = $user['name'];
        $randevu->islemTarihi = date('Y-m-d');
        

        $randevu->fill($sonuc)->save();

        return response()->json(array('status' => true, 'msg' => 'Kayıt eklendi'));
    }

    /**
     * Düzelt
     *
     * @return json array
     */
    public function randevuGuncelle ()
    {

        $randevu = \App\Randevu::find(Request::input('id'));

        $input = Request::all();

        $validator = Validator::make(Request::all(), [
            'cari_id' => 'required',
            'aciklama' => 'required'
        ]);

        if ($validator->fails()) {
            $e = $validator->errors();
            return response()->json(array('status' => false, 'msg' => $e));
        }

        $randevu->fill($input)->save();

        return response()->json(array('status' => true, 'msg' => 'İşlem Tamam'));
    }

    /**
     * Liste
     *
     * @return json array
     */
    public function randevuListele()
    {
        $randevu = \App\Randevu::
             leftJoin('caris', 'randevu.cari_id', '=', 'caris.id')
            ->orderBy('randevu.id', 'DESC')
            ->get(['caris.adi as adi','randevu.id','randevu.yetkili','randevu.aciklama','islemTarihi','randevuTarihi','kullanici']);
      
        return response()->json($randevu);

    }

    public function randevuGetir($id){
        $randevu =\App\Randevu::find($id);
        $randevu->cari; 
        return response()->json($randevu);
    }

    public function listShortRandevu()
    {
        $randevu = \App\Randevu::
            orderBy('id', 'asc')
            ->get();

        return response()->json($randevu);

    }

    public function listShortRandevuId($id)
    {
        $randevu = \App\Randevu::
            orderBy('id', 'asc')
            ->where('cari_id',$id)
            ->get();

        return response()->json($randevu);

    }
    /**
     * Delete
     *
     * @return json array
     */
    public function randevuSil()
    {
            
        $randevu = \App\Randevu::find(Request::input('id'));

        $randevu->delete();
        return response()->json(array('status' => true, 'msg' => 'Kayıt Silindi','id'=>  $randevu->id));

    }

}
