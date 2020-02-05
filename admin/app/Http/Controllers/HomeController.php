<?php

namespace App\Http\Controllers;

use App\Models\Jualan;
use App\Models\News;
use App\Models\Occupant;
use App\Models\Security;
use App\Models\User;

use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $checkUser = User::where('id', Auth::user()->id)->where('email_verified_at', null)->first();

        if($checkUser != null){
            $changePassword = TRUE; 
        }else{
            $changePassword = FALSE;
        }

        $getPenghuni = Occupant::where('security_id', null)->get();
        $getBerita = News::get();
        $getSecurity = Security::get();
        $getJualan = Jualan::get();

        return view('home', compact('getPenghuni', 'getBerita', 'getSecurity', 'getJualan','changePassword'));
    }
}
