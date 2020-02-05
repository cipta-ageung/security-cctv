<?php

namespace App\Http\Controllers;

use App\Mail\ChangeEmailAdmin;
use App\Models\AdminLogs;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use DB;
use Auth;
use Mail;

class ProfileController extends Controller
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

        $getData = User::findOrFail(Auth::user()->id);

        return view('profil.index', compact('getData'));
    }

    public function update(Request $request){

        $messages = [
            'name.required' => 'Wajib di isi',
            'email.required' => 'Wajib di isi',
            'email.email' => 'Format email salah',
            'email.unique' => 'Email sudah digunakan',
            'no_hp.required' => 'Wajib di isi',
            'no_hp.numeric' => 'Harap isikan nomor',
            'no_hp.unique' => 'Nomor sudah digunakan',
            'avatar.dimensions' => 'Ukuran yg di terima 2000px x 2000px',
            'avatar.image' => 'Format Foto Tidak Sesuai',
            'avatar.mimes' => 'Format Foto yang diterima .jpg, .png, .jpeg',
            'avatar.max' => 'File Size Terlalu Besar',

            'password.min' => "Terlalu Singkat.",
            'confirmPassword.required' => "Isi konfirmasi password baru anda.",
            'confirmPassword.same' => "Password Tidak Cocok.",
        ];
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:fra_users,name,'.$request->id,
            'product_id' => 'required',
            'email' => 'required|email|unique:fra_users,email,'.$request->id,
            'no_hp' => 'required|numeric|unique:fra_users,no_hp,'.$request->id,
            'avatar' => 'image|mimes:jpeg,png,jpg|max:2000|dimensions:max_width=2000,max_height=2000',
            'password' => 'nullable|min:8',
            'confirmPassword' => 'same:password'
        ], $messages);
        
        if ($validator->fails()) {
            return redirect()->route('profil.index')->withErrors($validator)->withInput();
        }

        DB::transaction(function() use($request){
            $save = User::find($request->id);

            if($request->password){
                $save->password = Hash::make($request->password);;
                $save->email_verified_at = date(now());

                $save1 = new AdminLogs;
                $save1->user_id = Auth::user()->id;
                $save1->logs_name = 'Mengubah Password Admin '.$request->name;
                $save1->save();
            }

            $image = $request->file('avatar');

            if($image){
                $salt = str_random(4);
                
                $img_url = str_slug($request->name,'-').'-admin-'.$salt. '.' . $image->getClientOriginalExtension();
                Image::make($image)->fit(300,300)->save('admin/images/'. $img_url);
        
                $save->avatar = $img_url;
            }

            $save->name = $request->name;
            $save->no_hp = $request->no_hp;
            $save->email = $request->email;

            if($save->isDirty('email')){
                Mail::to($request->email)->send(new ChangeEmailAdmin($save));
                
                $save1 = new AdminLogs;
                $save1->user_id = Auth::user()->id;
                $save1->logs_name = 'Mengubah Data Email Admin '.$request->email;
                $save1->save();
            }

            
            $save->update();
        });


        return redirect()->route('profil.index')->with('berhasil', 'Data Anda Berhasil Dirubah. Silahkan check email jika mengganti email');
    }

    public function changePassword(Request $request){

        $messages = [
            'password.min' => "Terlalu Singkat.",
            'confirmPassword.required' => "Isi konfirmasi password baru anda.",
            'confirmPassword.same' => "Password Tidak Cocok.",
        ];
        
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:8',
            'confirmPassword' => 'required|same:password'
        ], $messages);
        
        if ($validator->fails()) {
            return redirect()->route('home')->withErrors($validator)->withInput();
        }

        DB::transaction(function() use($request){
            $save = User::find(Auth::user()->id);

            $save->password = Hash::make($request->password);;
            $save->email_verified_at = date(now());
            $save->update();
        });

        return redirect()->route('home');
    }
}
