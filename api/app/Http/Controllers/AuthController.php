<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AppsLogs;
use App\Models\Devices;
use Tymon\JWTAuth\JWTAuth;
use App\Models\User;

use Validator;
use Log;
use DB;

class AuthController extends Controller
{
    // use AccountTrait;
    
    protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    public function login(Request $request)
    { 
        Log::info($request->email.' Login');
        $validator = Validator::make($request->all(),[
            'email'     =>'required|email',
            'password'  => 'required|min:8'
        ]);

        if($validator->fails()){
            $data = array();
            foreach($validator->messages()->getMessages() as $field_name => $values){
                $data[$field_name]=$values[0];
            }
            return response()->json(['code'=>400, 'description'=>'Validasi gagal', 'results'=>$data],400);
        }

        $cekUser = User::where(['email'=>$request->email])->first();
        
        if($cekUser){
            $credentials = $request->only('email', 'password');
            
            if($token = $this->jwt->attempt($credentials)){

                $profile = User::select('no_rumah','nama_kk as nama_penghuni','avatar','no_hp','email','security_id as secure')->where('id', $this->jwt->user()->id)->first();

                DB::transaction(function() use($request){
                    $save = new AppsLogs;
                    $save->occupant_id = $this->jwt->user()->id;
                    $save->logs_name = 'Login Aplikasi';
                    $save->save();

                    $device_id = User::find($this->jwt->user()->id);
                    $device_id->device_id = $request->device_id;
                    $device_id->update();

                    Devices::where('device_id', '=', $request->device_id)->delete();
                });

                if($profile->secure == null){
                    $penghuni = 'penghuni';
                }else{
                    $penghuni = 'security';
                }

                if($request->password == 'WelcomeAquila'){
                    Log::info($request->email.' Berhasil Login');

                    return response()->json(['code' => 200, 'description' => 'pengguna baru', 'access_token' => $token, 'penghuni' => $penghuni, 'results' => $profile], 200);
                }

                Log::info($request->email.' Login Aplikasi');
                
                return response()->json(['code' => 200, 'description' => 'login berhasil', 'access_token' => $token, 'penghuni' => $penghuni, 'results' => $profile], 200);
            }else{
                Log::info($request->email.' Email atau Password Salah');

                return $this->trueResponse('Email atau password salah', 400);
            }
            
        }else{
            return $this->trueResponse('Data Penghuni tidak valid',400);
        }

    }

    public function logout()
    {

        User::where('id', $this->jwt->user()->id)->update(['device_id' => null]);
        
        $save = new AppsLogs;
        $save->occupant_id = $this->jwt->user()->id;
        $save->logs_name = 'Logout Aplikasi';
        $save->save();

        Log::info($this->jwt->user()->nama_kk.' Logout Aplikasi');


        $this->jwt->invalidate();

        
        return $this->trueResponse('Logout berhasil', 200);
    }
}