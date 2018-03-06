<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use App\User;


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

    public function test(){
        $users = User::orderBy('id', 'DESC')->where('musteri','1')->with('cariler')->get();
        print_r($users->toArray());
        
    }
}
