<?php
 
namespace App\Http\Controllers;
 
use Request;

use App\User;

use Validator;

 
class Users extends Controller
{
    /**
     * yeni kullanıcı ekle
     *
     * @return json array
     */
	public function newUser(){
        
  
        $sonuc = Request::all();

        //id varsa update et..
        if (isset($sonuc['id'])){
            
            return $this->updateUser();
        }
        //gerekli alanlar
        $validator = Validator::make(Request::all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);
        

        if ($validator->fails()){
            $e= $validator->errors();
            $err =  Error::getError($validator);
            return response()->json(array('status'=>false,'msg'=>$e));
        }

        //password değerini şifrelememiz gerekiyor. Laravel auth işlemlerinde default olarak bcrypt ile şifrelenmiş değere bakıyor
        $sonuc['password']=bcrypt($sonuc['password']);
 
        //kullanıcımızı oluşturalım.
        return User::create($sonuc);
        return response()->json(array('status'=>true,'msg'=>'Kayıt eklendi'));
    }
    
    /**
     * Kullanıcı düzelt
     *
     * @return json array
     */
    public function updateUser(){
        
  
        $user = User::find(Request::input('id'));

        $validator = Validator::make(Request::all(), [
            'name' => 'required|min:3,',
            'email' => 'required|email|unique:users,email,'.$user['id']
           
        ]);

        if ($validator->fails()){
          
            $e= $validator->errors();
            $err =  Error::getError($validator);
        
            return response()->json(array('status'=>false,'msg'=>$e));
        }

        $input = Request::all();
     
        if ( ! empty($input['password']))
        {
            $input['password']= bcrypt(Request::input('password'));
        }
        
       
        $user->fill($input)->save();
        
        //kullanıcımızı oluşturalım.
        return response()->json(array('status'=>true,'msg'=>'İşlem Tamam'));
	}
 

    /**
     * Kullanıcı listesi
     *
     * @return json array
     */
    public function listUser(){

        return  response()->json(User::all());
       
    }
 
   
}