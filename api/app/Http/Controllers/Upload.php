<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;


class Upload extends Controller
{
    

    /**
     * Upload file
     *
     * @param Request $request
     * @return json
     */
    public function uploadFile(Request $request){
        
        $path = Storage::putFile('upload', $request->file('file'));  

        return response()->json(array('status'=>true,'file'=>$path));
    }
}
