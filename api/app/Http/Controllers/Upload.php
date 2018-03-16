<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use Intervention\Image\ImageManagerStatic as Image;



class Upload extends Controller
{
    

    /**
     * Upload file
     *
     * @param Request $request
     * @return json
     */
    public function uploadFile(Request $request){
        
        $path = Storage::putFile('', $request->file('file')); 
        
        $image = Image::make('files/'.$path)->resize(300, 200)->save('files/thumb/'.$path);


        return response()->json(array('status'=>true,'file'=>$path));
    }
}
