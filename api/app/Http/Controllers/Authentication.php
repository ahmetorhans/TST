<?php
 
namespace App\Http\Controllers;
 
use Request;
use JWTAuth;
use App\User;
use Auth;
use Validator;
use App\Http\Controllers\Helper\Error;
 
class Authentication extends Controller
{
 
	public function register(){
        
       
        
	    //vue üzerinden form verisi bilgileri json olarak gönderdiği için biz de json->all() ile çekiyoruz.    
        $sonuc = Request::all();

        if (isset($sonuc['id'])){
            
            return $this->updateUser();
        }
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
    
    public function updateUser(){
        
        //$access_token = Request::header('Authorization');
     
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
       //pg $user->name = Request::input('name');
        if ( ! empty($input['password']))
        {
            $input['password']= bcrypt(Request::input('password'));
        }
        
       
        $user->fill($input)->save();
        //kullanıcımızı oluşturalım.
        return response()->json(array('status'=>true,'msg'=>'İşlem Tamam'));
	}
 
    public function login()
    {
        
        //post'dan gelen email ve password değerlerini kontrol et. Doğruysa token oluştur ve gönder
 
    	$credentials = Request::only('email', 'password');
        try {
        
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Bilgiler yanlış'], 401);
            }
        } catch (JWTException $e) {
        
            return response()->json(['error' => 'token oluşturulamadı'], 500);
        }
        return response()->json(compact('token'));
    }

    public function loginTest()
    {
        
        //post'dan gelen email ve password değerlerini kontrol et. Doğruysa token oluştur ve gönder
 
    	$credentials = array('email'=>'asd@as.com','password'=>'123456');
        try {
        
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Bilgiler yanlış'], 401);
            }
        } catch (JWTException $e) {
        
            return response()->json(['error' => 'token oluşturulamadı'], 500);
        }
        return response()->json(compact('token'));
    }

    public function userList(){
        return  response()->json(User::all());
        $validator = Validator::make(Request::all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);
        
        $m = $validator->errors()->getMessages();
        
     
        $err=array();
        foreach($m as $msg){
          foreach($msg as $key=>$val){
            $err[]= $val;
           
          }
        }
        
      return json_encode($err);
    }
 
   
}