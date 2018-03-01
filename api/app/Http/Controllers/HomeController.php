<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
      //  $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function upload(Request $request){
        $path = Storage::putFile('upload', $request->file('file'));  

        return response()->json(array('status'=>true,'file'=>$path));
    }
}
