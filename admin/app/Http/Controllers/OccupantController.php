<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Occupant;
use App\Models\Product;
use App\Mail\ActivationEmailPenghuni;
use App\Mail\ChangeEmailPenghuni;
use App\Mail\ResetPasswordPenghuni;
use App\Models\AdminLogs;
use Validator;
use Auth;
use Image;
use DB;
use Mail;

class OccupantController extends Controller
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

        $adminId = Auth::user()->product_id;

        $getData = Occupant::select('fra_occupant.id','fra_occupant.product_id','fra_occupant.no_rumah','fra_occupant.nama_kk','fra_occupant.no_hp','fra_occupant.email','fra_occupant.avatar','fra_users.name')
					->join('fra_users','fra_users.id','fra_occupant.created_by')
					->where('fra_occupant.security_id', null)
					->where('fra_occupant.product_id', $adminId)->get();

        return view('occupant.index', compact('getData'));
    }

    public function create(){

        $adminId = Auth::user()->product_id;

        $getProduct = Product::where('id', $adminId)->get();

        return view('occupant.create', compact('getProduct'));
    }

    public function store(Request $request){

        $message = [
            'product_id.required' => 'Wajib di isi',
            'no_rumah.required' => 'Wajib di isi',
            'no_rumah.unique' => 'Rumah sudah dihuni',
            'nama_kk.required' => 'Wajib di isi',
            'no_hp.required' => 'Wajib di isi',
            'no_hp.numeric' => 'Harus angka',
            'no_hp.unique' => 'No HP sudah terdaftar',
            'email.required' => 'Wajib di isi',
            'email.email' => 'Harus format email',
            'email.unique' => 'Email sudah terdaftar',
            'avatar.dimensions' => 'Ukuran maksimal yg di terima 3000px x 3000px',
            'avatar.max' => 'Ukuran file maksimal yg di terima 2MB',
            'avatar.image' => 'Format gambar tidak sesuai',
            'avatar.mimes' => 'Format gambar yang diterima .jpg, .png, .jpeg',
        ];

        $validator = Validator::make($request->all(), [
            'no_rumah' => 'required',
            'product_id' => 'required',
            'nama_kk' => 'required',
            'no_hp' => 'required|numeric|unique:fra_occupant',
            'email' => 'required|email|unique:fra_occupant',
            'avatar' => 'image|mimes:jpeg,png,jpg|max:2000|dimensions:max_width=3000,max_height=3000',
        ], $message);


        if($validator->fails())
        {
            return redirect()->route('occupant.create')->withErrors($validator)->withInput();
        }

        DB::transaction(function() use($request){
            $gambar = $request->file('avatar');

            $occupant = new Occupant;

            if($gambar){
                $salt = str_random(4);
                
                $img_url = str_slug($request->nama_kk,'-').'-penghuni-'.$salt. '.' . $gambar->getClientOriginalExtension();
                Image::make($gambar)->fit(100,100)->save('penghuni/images/'. $img_url);

                $occupant->avatar = $img_url;
            }

            $occupant->product_id = $request->product_id;
            $occupant->nama_kk = $request->nama_kk;
            $occupant->no_rumah = $request->no_rumah;
            $occupant->no_hp = $request->no_hp;
            $occupant->email = $request->email;
            $occupant->password = bcrypt('WelcomeAquila');
                
            $occupant->created_by = Auth::user()->id;
            $occupant->save();

            $save1 = new AdminLogs;
            $save1->user_id = Auth::user()->id;
            $save1->logs_name = 'Menambahkan Penghuni '.$request->nama_kk;
            $save1->save();

            Mail::to($request->email)->send(new ActivationEmailPenghuni($occupant));
        });

        return redirect()->route('occupant.index')->with('berhasil', 'Berhasil Menambahkan Penghuni, harap cek Inbox atau Spam Email');
    }

    public function edit($id){

        $find = Occupant::findOrFail($id);

        $adminId = Auth::user()->product_id;
        
        $getProduct = Product::select('id', 'nama')->where('id', $adminId)->get();

        return view('occupant.update', compact('find', 'getProduct'));
    }

    public function update(Request $request){

        $message = [
            'product_id.required' => 'Wajib di isi',
            'no_rumah.required' => 'Wajib di isi',
            'no_rumah.unique' => 'Rumah sudah dihuni',
            'nama_kk.required' => 'Wajib di isi',
            'no_hp.required' => 'Wajib di isi',
            'no_hp.numeric' => 'Harus angka',
            'no_hp.unique' => 'No HP sudah terdaftar',
            'email.required' => 'Wajib di isi',
            'email.email' => 'Harus format email',
            'email.unique' => 'Email sudah terdaftar',
            'avatar.dimensions' => 'Ukuran maksimal yg di terima 3000px x 3000px',
            'avatar.max' => 'Ukuran file maksimal yg di terima 2MB',
            'avatar.image' => 'Format gambar tidak sesuai',
            'avatar.mimes' => 'Format gambar yang diterima .jpg, .png, .jpeg',
        ];

        $validator = Validator::make($request->all(), [
            'no_rumah' => 'required',
            'product_id' => 'required',
            'nama_kk' => 'required',
            'no_hp' => 'required|numeric|unique:fra_occupant,no_hp,'.$request->id,
            'email' => 'required|email|unique:fra_occupant,email,'.$request->id,
            'avatar' => 'image|mimes:jpeg,png,jpg|max:2000|dimensions:max_width=3000,max_height=3000',
        ], $message);


        if($validator->fails())
        {
            return redirect()->route('occupant.edit', ['id' => $request->id ])->withErrors($validator)->withInput();
        }

        DB::transaction(function() use($request){
            $gambar = $request->file('avatar');

            $occupant = Occupant::findOrFail($request->id);

            if($gambar){
                $salt = str_random(4);
                
                $img_url = str_slug($request->nama_kk,'-').'-penghuni-'.$salt. '.' . $gambar->getClientOriginalExtension();
                Image::make($gambar)->fit(100,100)->save('penghuni/images/'. $img_url);

                $occupant->avatar = $img_url;
            }

            $occupant->product_id = $request->product_id;
            $occupant->nama_kk = $request->nama_kk;
            $occupant->no_rumah = $request->no_rumah;
            $occupant->no_hp = $request->no_hp;
            $occupant->email = $request->email;
                
            $occupant->created_by = Auth::user()->id;
            $occupant->update();

            $save1 = new AdminLogs;
            $save1->user_id = Auth::user()->id;
            $save1->logs_name = 'Mengubah Data Penghuni '.$request->nama_kk;
            $save1->save();

            if($occupant->isDirty('email')){
                Mail::to($request->email)->send(new ChangeEmailPenghuni($occupant));
            }
        });

        return redirect()->route('occupant.index')->with('berhasil', 'Berhasil Mengubah Penghuni');
    }

    public function delete($id){

        $find = Occupant::find($id);
    
        if(!$find){
          abort(404);
        }

        $nama = $find->nama_kk;
        $find = $find->delete();

        $save1 = new AdminLogs;
        $save1->user_id = Auth::user()->id;
        $save1->logs_name = 'Menghapus data penghuni '.$nama;
        $save1->save();
    
        return response()->json([
            'success' => 'Berhasil',
            'message' => 'Penghuni Berhasil di Hapus',
        ]);
    }

    public function reset($id){

        $occupant = Occupant::find($id);

        if(!$occupant){
            abort(404);
        }

        $occupant->password = bcrypt('WelcomeAquila');
        $occupant->update();

        $save1 = new AdminLogs;
        $save1->user_id = Auth::user()->id;
        $save1->logs_name = 'Reset Password Penghuni '.$occupant->nama_kk;
        $save1->save();

        Mail::to($occupant->email)->send(new ResetPasswordPenghuni($occupant));

        return response()->json([
            'success' => 'Berhasil',
            'message' => 'Password Penghuni Berhasil di Reset',
        ]);      
    }
}
