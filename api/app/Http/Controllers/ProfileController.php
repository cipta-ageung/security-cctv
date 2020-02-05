<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AppsLogs;
use App\Models\Devices;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Support\Facades\Hash;

use App\Models\Occupant;

use Log;
use Validator;
use DB;
use Image;

class ProfileController extends Controller
{
    protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    public function profile(){

        $getProfile = Occupant::select('id','product_id','no_rumah','nama_kk','avatar','no_hp','email','device_id')
                                ->where('id',$this->jwt->user()->id)
                                ->first();

        return $this->responseWithToken('berhasil',200,$getProfile);
    }

    public function fcm(Request $request){

        $message = [
            'device_id.required' => 'Wajib di isi',
            'device_id.unique' => 'DeviceID sudah digunakan',
        ];

        $validator = Validator::make($request->all(), [
            'device_id' => 'required|unique:fra_devices'
        ], $message);


        if($validator->fails())
        {
            return $this->trueResponse('validasi gagal',400, $validator->errors());
        }

        try{
            Devices::insert([
                'device_id' => $request->device_id,
                'created_at' => date("Y-m-d H:i:sa"),
            ]);
            
            return $this->trueResponse('sukses simpan fcm',201);

        }catch(\Exception $e){
            Log::info($e->getMessage());

            return $this->trueResponse('terjadi kesalahan',409);
        }
    }

    public function update(Request $request){

        $update = Occupant::findOrFail($request->id);

        if(!$update){
            return $this->trueResponse('Profil tidak ditemukan', 400);
        }

        $validator = Validator::make($request->all(), [
            'nama_kk' => 'required',
            'no_hp' => 'required|numeric|unique:fra_occupant,no_hp,'.$request->id,
            'avatar' => 'image|mimes:jpeg,png,jpg|max:2000|dimensions:max_width=3000,max_height=3000',
        ]);

        if ($validator->fails()) {
            $data = array();
            foreach ($validator->messages()->getMessages() as $field_name => $values) {
                $data[$field_name] = $values[0];
            }
            return $this->trueResponse('validasi gagal', 400, $data);
        }

        DB::transaction(function() use($request,$update){

            $gambar = $request->file('avatar');

            $occupant = Occupant::findOrFail($request->id);

            if($gambar){
                $salt = str_random(4);
                
                $img_url = str_slug($request->nama_kk,'-').'-penghuni-'.$salt. '.' . $gambar->getClientOriginalExtension();
                Image::make($gambar)->fit(100,100)->save('penghuni/images/'. $img_url);

                $occupant->avatar = $img_url;
            }

            $occupant->nama_kk = $request->nama_kk;
            $occupant->no_hp = $request->no_hp;
                
            $occupant->created_by = $this->jwt->user()->id;
            $occupant->update();

            $save1 = new AppsLogs;
            $save1->occupant_id = $this->jwt->user()->id;
            $save1->logs_name = 'Mengubah Profil '.$request->nama_kk;
            $save1->save();
        });

        return $this->responseWithToken('Profil Anda Berhasil di Ubah',201);
    }

    public function gantiPassword(Request $request){

        $messages = [
            'oldPassword.required' => "Isi password lama anda.",
            'password.min' => "Terlalu Singkat.",
            'confirmPassword.required' => "Isi konfirmasi password baru anda.",
            'confirmPassword.same' => "Password Tidak Cocok.",
        ];
        
        $validator = Validator::make($request->all(), [
            'oldPassword' => 'required',
            'password' => 'required|min:8',
            'confirmPassword' => 'required|same:password'
        ], $messages);
        
        if ($validator->fails()) {
            return $this->trueResponse('validasi gagal',400, $validator->errors());
        }

        $getUser = Occupant::findOrFail($this->jwt->user()->id);
        if(Hash::check($request->oldPassword, $getUser->password)){
            $getUser->password = Hash::make($request->password);
            $getUser->update();
        }else{
            return $this->trueResponse('password lama anda salah',409);
        }

        DB::transaction(function() use($request){
            $save = new AppsLogs;
            $save->occupant_id = $this->jwt->user()->id;
            $save->logs_name = 'Mengganti Password';
            $save->save();
        });

        return $this->responseWithToken('Password anda telah berhasil dirubah!', 201);
    }
}