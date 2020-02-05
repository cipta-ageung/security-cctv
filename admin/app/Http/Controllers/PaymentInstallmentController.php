<?php

namespace App\Http\Controllers;

use App\Models\AdminLogs;
use App\Models\Occupant;
use Illuminate\Http\Request;
use App\Models\PaymentInstallment;

use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;

use Validator;
use Log;
use DB;
use Auth;
use FCM;


class PaymentInstallmentController extends Controller
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

        $getData = PaymentInstallment::orderBy('month','desc')->get();

        return view('paymentCicilan.index', compact('getData'));
    }

    public function create(){
        
        $adminId = Auth::user()->product_id;

        $getOccupant = Occupant::where('security_id', null)->where('product_id', $adminId)->get(); 

        return view('paymentCicilan.create', compact('getOccupant'));
    }

    public function store(Request $request){

        $message = [
            'occupant_id.required' => 'Wajib di isi',
            'occupant_id.unique' => 'Sudah ada pengingat',
            'bulan.required' => 'Wajib di isi',
            'bulan.unique' => 'Sudah pernah ada',
            'nominal.required' => 'Wajib di isi',
            'nominal.numeric' => 'Wajib di isi',
        ];

        $validator = Validator::make($request->all(), [
            'occupant_id' => 'required|unique:fra_payment_installment,occupant_id,NULL,id,month,'.$request->bulan,
            'bulan' => 'required|unique:fra_payment_installment,month,NULL,id,occupant_id,'.$request->occupant_id,
            'nominal' => 'required|numeric',
        ], $message);


        if($validator->fails())
        {
            return redirect()->route('reminder-installment.create')->withErrors($validator)->withInput();
        }
        

        DB::transaction(function() use($request){
            $save = new PaymentInstallment();
            $save->occupant_id = $request->occupant_id;
            $save->month = $request->bulan.'/01';
            $save->nominal = $request->nominal;
            $save->created_by = Auth::user()->id;
            $save->save();

            $data = PaymentInstallment::where('id', $save->id)->with('penghuni')->first();
           
            $save1 = new AdminLogs;
            $save1->user_id = Auth::user()->id;
            $save1->logs_name = 'Menambahkan Pengingat Cicilan Bulan '.$request->bulan;
            $save1->save();

            if($data['penghuni']['device_id'] != null){
                $tokenOccupant = $data['penghuni']['device_id'];
                $this->pushNotif($data, $tokenOccupant);
            }
            
        });

        return redirect()->route('reminder-installment.index')->with('berhasil', 'Berhasil Menambahkan Pengingat Cicilan');

    }

    public function pushNotif($data, $tokenOccupant){

        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);

        $notificationBuilder = new PayloadNotificationBuilder('Pengingat Pembayaran Cicilan');
        $notificationBuilder->setBody('Pembayaran Cicilan Sebesar Rp. '.number_format($data['nominal'],'0','.','.'))->setClickAction('OPEN_ACTIVITY_CICILAN')->setSound('default');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['judul' => 'Pengingat Cicilan '.$data['month'],
                                'message' => 'Pembayaran Cicilan Sebesar Rp. '.number_format($data['nominal'],'0','.','.'),
                                'flag' => 'inboxCicilan',
                                'click_action'=>'OPEN_ACTIVITY_CICILAN',
                            ]);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        $token = $tokenOccupant;

        if($tokenOccupant != null){
            $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);
    
            $res = [
                'numberSuccess'     => $downstreamResponse->numberSuccess(),
                'numberFailure'     => $downstreamResponse->numberFailure(),
                'numberModification' => $downstreamResponse->numberModification(),
                'tokensWithError'  => $downstreamResponse->tokensWithError(),
            ];
    
            Log::info($res);
        }else{
            Log::info('Tidak mengirim push notif Installment');

            return 'Tidak Dapat Mengirim Push Notif';
        }
        
    }
}
