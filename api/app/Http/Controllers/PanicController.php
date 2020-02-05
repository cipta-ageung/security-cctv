<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AppsLogs;
use Tymon\JWTAuth\JWTAuth;

use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use LaravelFCM\Message\OptionsPriorities;

use App\Models\Occupant;
use App\Models\PanicLogs;

use DB;
use Validator;
use Log;
use FCM;

class PanicController extends Controller
{
    protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    public function pushNotif(Request $request){

        $validator = Validator::make($request->all(),[
            'datetime'     =>'required|date_format:"Y-m-d H:i:s'
        ]);

        if($validator->fails()){
            $data = array();
            foreach($validator->messages()->getMessages() as $field_name => $values){
                $data[$field_name]=$values[0];
            }
            return response()->json(['code'=>400, 'description'=>'Validasi gagal', 'results'=>$data],400);
        }

        $date = substr($request->datetime, 0, 10);
        $time = substr($request->datetime, 11);

        $pagi = '06:00:00';
        $siang = '14:00:00';
        $malam = '22:00:00';

        if(($time >= $pagi) && ($time <= $siang)){
            $shift = "Pagi";
        }elseif(($time >= $siang) && ($time <= $malam)){
            $shift = 'Siang';
        }elseif(($time >= $malam) || ($time <= $pagi)){
            $shift = 'Malam';
        }

        $user = $this->jwt->user()->id;

        $cluster = Occupant::select('nama_kk','no_rumah','product_id','no_hp')->where('id', $user)->first();

        $product = $cluster->product_id;

        $checkSecurity = Occupant::where('device_id','!=',null)
                                    ->whereHas('sekuriti.schedules',function($q) use ($date, $shift){
                                        $q->where('shift', '=', $shift)->where('date', '=', $date);
                                    })
                                    ->whereHas('sekuriti', function($a) use($product){
                                        $a->where('product_id', '=', $product);
                                    })
                                    ->pluck('device_id')->toArray();
        
        if($checkSecurity == null){
            return response()->json(['code' => 200, 'description' => 'Tidak ada security yang berjaga'], 200);
        }

        $this->saveLog($product,$shift,$date,$user);

        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setPriority(OptionsPriorities::high);
        $optionBuilder->setTimeToLive(60 * 20);

        $analytics_label = ["fcm_options" => ["analytics_label" => "Panic Button" ]];

        $notificationBuilder = new PayloadNotificationBuilder('Bantuan Panic Button');
        $notificationBuilder->setBody('Tolong Bantuan!!! '.$cluster->nama_kk.' '.$cluster->no_rumah)->setClickAction('OPEN_ACTIVITY_PANIC')->setSound('default');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['message' => 'KEPADA SECURITY YANG BERJAGA, PENGHUNI '.$cluster->nama_kk.' '.$cluster->no_rumah.' MEMBUTUHKAN BANTUAN. TELEPON YANG DAPAT DIHUBUNGI '.$cluster->no_hp.'.',
                                'no_hp' => $cluster->no_hp,
                                'flag' => 'panic',
                                'click_action'=>'OPEN_ACTIVITY_PANIC',                
                            ]);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        $tokens = $checkSecurity;
        
        $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data, $analytics_label);

        $res = [
            'numberSuccess'     => $downstreamResponse->numberSuccess(),
            'numberFailure'     => $downstreamResponse->numberFailure(),
            'numberModification' => $downstreamResponse->numberModification(),
            'tokensWithError'  => $downstreamResponse->tokensWithError(),
        ];

        Log::info($res);
        DB::transaction(function() use($user){
            $save = new AppsLogs;
            $save->occupant_id = $user;
            $save->logs_name = 'Menekan Panic Button';
            $save->save();
        });

        return $this->trueResponse('berhasil', 200, ['description' => 'KEPADA SECURITY YANG BERJAGA, PENGHUNI '.$cluster->nama_kk.' '.$cluster->no_rumah.' MEMBUTUHKAN BANTUAN. TELEPON YANG DAPAT DIHUBUNGI '.$cluster->no_hp.'.']);
    }

    public function pushNotifPopUp(Request $request){

        $validator = Validator::make($request->all(),[
            'datetime'     =>'required|date_format:"Y-m-d H:i:s'
        ]);

        if($validator->fails()){
            $data = array();
            foreach($validator->messages()->getMessages() as $field_name => $values){
                $data[$field_name]=$values[0];
            }
            return response()->json(['code'=>400, 'description'=>'Validasi gagal', 'results'=>$data],400);
        }

        $date = substr($request->datetime, 0, 10);
        $time = substr($request->datetime, 11);

        $pagi = '08:00:00';
        $malam = '20:00:00';

        if(($time >= $pagi) && ($time <= $malam)){
            $shift = "Pagi";
        }elseif(($time >= $malam) || ($time <= $pagi)){
            $shift = 'Malam';
        }

        $user = $this->jwt->user()->id;

        $cluster = Occupant::select('nama_kk','no_rumah','product_id','no_hp')->where('id', $user)->first();

        $product = $cluster->product_id;

        $checkSecurity = Occupant::where('device_id','!=',null)
                                    ->whereHas('sekuriti.schedules',function($q) use ($date, $shift){
                                        $q->where('shift', '=', $shift)->where('date', '=', $date);
                                    })
                                    ->whereHas('sekuriti', function($a) use($product){
                                        $a->where('product_id', '=', $product);
                                    })
                                    ->pluck('device_id')->toArray();
        
        if($checkSecurity == null){
            return response()->json(['code' => 200, 'description' => 'Tidak ada security yang berjaga'], 200);
        }

        $this->saveLog($product,$shift,$date,$user);

        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setPriority(OptionsPriorities::high);
        $optionBuilder->setTimeToLive(60 * 20);

        $analytics_label = ["fcm_options" => ["analytics_label" => "Panic Button" ]];

        // $notificationBuilder = new PayloadNotificationBuilder('Bantuan Panic Button');
        // $notificationBuilder->setBody('Tolong Bantuan!!! '.$cluster->nama_kk.' '.$cluster->no_rumah)->setClickAction('OPEN_ACTIVITY_PANIC')->setSound('default');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['message' => 'KEPADA SECURITY YANG BERJAGA, PENGHUNI '.$cluster->nama_kk.' '.$cluster->no_rumah.' MEMBUTUHKAN BANTUAN. TELEPON YANG DAPAT DIHUBUNGI '.$cluster->no_hp.'.',
                                'no_hp' => $cluster->no_hp,
                                'flag' => 'panic',
                                'click_action'=>'OPEN_ACTIVITY_PANIC',                
                            ]);

        $option = $optionBuilder->build();
        // $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        $tokens = $checkSecurity;
        
        $downstreamResponse = FCM::sendTo($tokens, $option, null, $data, $analytics_label);

        $res = [
            'numberSuccess'     => $downstreamResponse->numberSuccess(),
            'numberFailure'     => $downstreamResponse->numberFailure(),
            'numberModification' => $downstreamResponse->numberModification(),
            'tokensWithError'  => $downstreamResponse->tokensWithError(),
        ];

        Log::info($res);
        DB::transaction(function() use($user){
            $save = new AppsLogs;
            $save->occupant_id = $user;
            $save->logs_name = 'Menekan Panic Button';
            $save->save();
        });

        return $this->trueResponse('berhasil', 200, ['description' => 'Tunggu beberapa saat, security sedang dalam perjalan ke tempat Anda!']);
    }

    public function saveLog($product,$shift,$date,$user){

        $getSecurity = Occupant::where('device_id','!=',null)
                                ->whereHas('sekuriti.schedules',function($q) use ($date, $shift){
                                    $q->where('shift', '=', $shift)->where('date', '=', $date);
                                })
                                ->whereHas('sekuriti', function($a) use($product){
                                    $a->where('product_id', '=', $product);
                                })
                                ->pluck('nama_kk')->toArray();

        $saveSecurity = implode( ", ", $getSecurity);

        $save = new PanicLogs;
        $save->user_id = $user;
        $save->sent_to = $saveSecurity;
        $save->logs_name = 'Menekan Panic Button';
        $save->save();
        
        Log::info('Berhasil menyimpan '.$save);

    }
}