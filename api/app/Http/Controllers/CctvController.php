<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AppsLogs;
use App\Models\Cctv;
use Tymon\JWTAuth\JWTAuth;

use DB;
use Validator;
use Log;

class CctvController extends Controller
{
    protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    public function index(Request $request){

        $getCctv = Cctv::with('product:id,nama')->select('nama_cctv','link_stream','product_id')->where('product_id', $this->jwt->user()->product_id)->get();

        DB::transaction(function() use($request){
            $save = new AppsLogs;
            $save->occupant_id = $this->jwt->user()->id;
            $save->logs_name = 'Mellihat CCTV';
            $save->save();
        });

        return $this->showAllWithToken('berhasil',$getCctv, $request);
    }
}