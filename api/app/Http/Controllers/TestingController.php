<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\Devices;
use App\Models\Occupant;
use App\Models\PaymentInstallment;
use App\Models\PaymentIpl;
use Tymon\JWTAuth\JWTAuth;

use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\Topics;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;

use Log;
use FCM;

class TestingController extends Controller
{
    protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    public function berita(Request $request){

        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60 * 20);

        $notificationBuilder = new PayloadNotificationBuilder($request->judul);
        $notificationBuilder->setBody($request->kategori)->setClickAction('OPEN_ACTIVITY_NEWS')->setSound('default')->setIcon('https://admin.aquilaland.id/admin/images/user.png');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['judul' => $request->judul,
                                'kategori' => $request->kategori,
                                'flag'  => 'berita',
                                'id'    => $request->id,
                            ]);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        $tokens = Occupant::where('device_id', '!=', null)->where('security_id', null)->pluck('device_id')->toArray();
        $tokens1 = Devices::pluck('device_id')->toArray();

        $kirim = array_merge($tokens,$tokens1);

        if($kirim != null){
            $downstreamResponse = FCM::sendTo($kirim, $option, $notification, $data);
    
            $res = [
                'numberSuccess'     => $downstreamResponse->numberSuccess(),
                'numberFailure'     => $downstreamResponse->numberFailure(),
                'numberModification' => $downstreamResponse->numberModification(),
                'tokensWithError'  => $downstreamResponse->tokensWithError(),
            ];
            Log::info($res);

        }else{
            Log::info('Tidak mengirim push notif '.$request->judul);

        }

        return 'user login '. count($tokens). ' umum '. count($tokens1);
    }

    public function beritaTopics(Request $request){

        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60 * 20);

        $notificationBuilder = new PayloadNotificationBuilder($request->judul);
        $notificationBuilder->setBody($request->kategori)->setClickAction('OPEN_ACTIVITY_NEWS')->setSound('default')->setIcon('https://admin.aquilaland.id/admin/images/user.png');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['judul' => $request->judul,
                                'kategori' => $request->kategori,
                                'flag'  => 'berita',
                                'id'    => $request->id,
                            ]);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        $topic = new Topics();
        $topic->topic('berita');

        
        if($request != null){
            $topicResponse = FCM::sendToTopic($topic, $option, $notification, $data);
    
            $res = [
                'numberSuccess'     => $topicResponse->isSuccess(),
                'numberFailure'     => $topicResponse->shouldRetry(),
                'tokensWithError'  => $topicResponse->error(),
            ];
            Log::info($res);

        }else{
            Log::info('Tidak mengirim push notif '.$request->judul);
        }

        return 'Push Notif Terkirim';
    }

    public function resetPwd(Request $request){
        
        $getUser = Occupant::where('email', $request->email)->first();
        $getUser->password = Hash::make('WelcomeAquila');
        $getUser->update();
        
        return 'Berhasil ganti password WelcomeAquila';

    }

    public function cekInbox(){

        PaymentInstallment::where('read_flag', 1)->update(['read_flag' => 0]);
        PaymentIpl::where('read_flag', 1)->update(['read_flag' => 0]);

        $countIpl = PaymentIpl::where('read_flag', 0)->count();
        $countInstallment = PaymentInstallment::where('read_flag', 0)->count();

        return 'Inbox IPL '.$countIpl.', Inbox Cicilan '.$countInstallment;
    }
}