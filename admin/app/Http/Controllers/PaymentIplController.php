<?php

namespace App\Http\Controllers;

use App\Models\AdminLogs;
use Illuminate\Http\Request;
use App\Models\PaymentIpl;
use App\Models\Occupant;

use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;

use Validator;
use Log;
use DB;
use Auth;
use FCM;

class PaymentIplController extends Controller
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

    public function index(){

        $getData = PaymentIpl::select('fra_payment_ipl.month','fra_payment_ipl.nominal','fra_users.name')
					->join('fra_users','fra_users.id','fra_payment_ipl.created_by')
					->groupBy('month')->orderBy('month','desc')->get();

        return view('paymentIpl.index', compact('getData'));
    }

    public function create(){

        return view('paymentIpl.create');
    }

    public function store(Request $request){

        $message = [
            'bulan.required' => 'Wajib di isi',
            'bulan.unique' => 'Sudah pernah ada',
            'nominal.required' => 'Wajib di isi',
            'nominal.numeric' => 'Wajib di isi',
        ];

        $validator = Validator::make($request->all(), [
            'bulan' => 'required|unique:fra_payment_ipl,month',
            'nominal' => 'required|numeric',
        ], $message);


        if($validator->fails())
        {
            return redirect()->route('reminder-ipl.create')->withErrors($validator)->withInput();
        }

        $occupant = Occupant::select('id')->where('security_id', null)->get()->toArray();        

        DB::transaction(function() use($request, $occupant){
            foreach($occupant as $data){
                $save = new PaymentIpl();
                $save->occupant_id = $data['id'];
                $save->month = $request->bulan.'/01';
                $save->nominal = $request->nominal;
                $save->created_by = Auth::user()->id;
                $save->save();
                
                $data = $save;
                
            }

            $save1 = new AdminLogs;
            $save1->user_id = Auth::user()->id;
            $save1->logs_name = 'Menambahkan Pengingat iuran '.$request->bulan;
            $save1->save();

            $this->pushNotif($data);
        });

        return redirect()->route('reminder-ipl.index')->with('berhasil', 'Berhasil Menambahkan Pengingat IPL');

    }

    public function pushNotif($data){

        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60 * 20);

        $notificationBuilder = new PayloadNotificationBuilder('Pengingat Pembayaran Iuran');
        $notificationBuilder->setBody('Iuran Pemeliharaan Lingkungan Rp. '.number_format($data['nominal'],'0','.','.'))->setClickAction('OPEN_ACTIVITY_CICILAN')->setSound('default');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['judul' => 'Pengingat Iuran '.$data['month'],
                                'message' => 'Iuran Pemeliharaan Lingkungan Rp. '.number_format($data['nominal'],'0','.','.'),
                                'flag' => 'inboxIpl',
                                'click_action'=>'OPEN_ACTIVITY_CICILAN',              
                            ]);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        $tokens = Occupant::where('device_id', '!=', null)->where('security_id', null)->pluck('device_id')->toArray();

        if($tokens != null){
            $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);
    
            $res = [
                'numberSuccess'     => $downstreamResponse->numberSuccess(),
                'numberFailure'     => $downstreamResponse->numberFailure(),
                'numberModification' => $downstreamResponse->numberModification(),
                'tokensWithError'  => $downstreamResponse->tokensWithError(),
            ];
    
            Log::info($res);
        }else{
            Log::info('Tidak mengirim push notif IPL');

            return 'Tidak Dapat Mengirim Push Notif';
        }
        
    }

}
