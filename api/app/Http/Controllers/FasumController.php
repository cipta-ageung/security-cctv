<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AppsLogs;
use App\Models\Contact;
use Tymon\JWTAuth\JWTAuth;

use DB;

class FasumController extends Controller
{
    protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    public function index(Request $request){

        $getFasum = Contact::select('nama','gambar','no_telp','alamat')->where('product_id', $this->jwt->user()->product_id)->get();

        DB::transaction(function() use($request){
            $save = new AppsLogs;
            $save->occupant_id = $this->jwt->user()->id;
            $save->logs_name = 'Melihat Kontak Fasilitas Umum';
            $save->save();
        });

        return $this->showAllWithToken('berhasil',$getFasum, $request);
    }

}