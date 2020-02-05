<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Security;
use App\Models\SecuritySchedule;
use Tymon\JWTAuth\JWTAuth;

use DB;
use Validator;
use Log;

class SecurityController extends Controller
{
    protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    public function index(Request $request){

        $date = date('Y-m-d');

        $getSchedule = SecuritySchedule::select('name','shift','date')->join('fra_security', 'fra_security.id', '=', 'fra_security_schedule.security_id')->where('fra_security.product_id', $this->jwt->user()->product_id)->where('fra_security_schedule.date', '>=', $date)->orderBy('fra_security_schedule.date', 'asc')->get();

        return $this->showAllWithToken('berhasil',$getSchedule, $request);
    }
}