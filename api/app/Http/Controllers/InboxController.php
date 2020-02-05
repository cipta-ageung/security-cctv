<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AppsLogs;
use App\Models\PaymentInstallment;
use App\Models\PaymentIpl;
use Tymon\JWTAuth\JWTAuth;

use DB;

class InboxController extends Controller
{
    protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    public function inbox(){

        $countIpl = PaymentIpl::where('occupant_id', $this->jwt->user()->id)->where('read_flag', 0)->count();
        $countInstallment = PaymentInstallment::where('occupant_id', $this->jwt->user()->id)->where('read_flag', 0)->count();

        $inbox = $countIpl+$countInstallment;
        
        return $this->trueResponse('berhasil',200,$inbox);
    }

    public function ipl(Request $request){

        PaymentIpl::where('occupant_id', $this->jwt->user()->id)->update(['read_flag' => 1]);
 
        $getIpl = PaymentIpl::select('month','nominal','read_flag')->where('occupant_id', $this->jwt->user()->id)->groupBy('month')->orderBy('month', 'desc')->get();

        DB::transaction(function() use($request){
            $save = new AppsLogs;
            $save->occupant_id = $this->jwt->user()->id;
            $save->logs_name = 'Melihat Inbox IPL';
            $save->save();
        });

        return $this->showAllWithToken('berhasil', $getIpl, $request);
    }

    public function cicilan(Request $request){

        PaymentInstallment::where('occupant_id', $this->jwt->user()->id)->update(['read_flag' => 1]);
 
        $getCicilan = PaymentInstallment::select('month','nominal','read_flag')->where('occupant_id', $this->jwt->user()->id)->orderBy('month', 'desc')->get();

        DB::transaction(function() use($request){
            $save = new AppsLogs;
            $save->occupant_id = $this->jwt->user()->id;
            $save->logs_name = 'Melihat Inbox Iuran';
            $save->save();
        });

        return $this->showAllWithToken('berhasil', $getCicilan, $request);
    }

}