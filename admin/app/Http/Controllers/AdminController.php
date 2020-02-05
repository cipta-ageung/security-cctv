<?php

namespace App\Http\Controllers;

use App\Mail\ActivationEmailAdmin;
use App\Models\AdminLogs;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\User;

use Validator;
use DB;
use Image;
use Mail;
use Auth;

class AdminController extends Controller
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

        $getData = User::where('email', '!=', 'fikrirezaa@gmail.com')->get();

        return view('admin.index', compact('getData'));
    }

    public function create(){

        $getProduct = Product::select('id','nama')->get();

        return view('admin.create', compact('getProduct'));
    }

    public function store(Request $request){
        $message = [
            'name.required' => 'Wajib di isi',
            'name.unique' => 'Nama sudah pernah ada',
            'email.required' => 'Wajib di isi',
            'email.email' => 'Format email salah',
            'email.unique' => 'Email sudah digunakan',
            'no_hp.required' => 'Wajib di isi',
            'no_hp.numeric' => 'Harap isikan nomor',
            'avatar.dimensions' => 'Ukuran yg di terima 2000px x 2000px',
            'avatar.image' => 'Format Foto Tidak Sesuai',
            'avatar.mimes' => 'Format Foto yang diterima .jpg, .png, .jpeg',
            'avatar.max' => 'File Size Terlalu Besar',
        ];
    
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:fra_users',
            'email' => 'required|email|unique:fra_users',
            'no_hp' => 'required|numeric',
            'product_id' => 'required',
            'avatar' => 'image|mimes:jpeg,png,jpg|max:2000|dimensions:max_width=2000,max_height=2000',
        ], $message);
    
    
        if($validator->fails())
        {
            return redirect()->route('admin.create')->withErrors($validator)->withInput();
        }
    
        DB::transaction(function() use($request){
            $image = $request->file('avatar');
    
            $save = new User;
    
            if($image){
                $salt = str_random(4);
                
                $img_url = str_slug($request->name,'-').'-admin-'.$salt. '.' . $image->getClientOriginalExtension();
                Image::make($image)->fit(300,300)->save('admin/images/'. $img_url);
        
                $save->avatar = $img_url;
            }
    
          
            $save->name = $request->name;
            $save->product_id = $request->product_id;
            $save->no_hp = $request->no_hp;
            $save->avatar = 'user.png';
            $save->email = $request->email;
            $save->password = bcrypt('WelcomeAquila');
            $save->save();

            $save1 = new AdminLogs;
            $save1->user_id = Auth::user()->id;
            $save1->logs_name = 'Menambahkan Akun '.$request->name.' dengan email '.$request->email;
            $save1->save();

            Mail::to($request->email)->send(new ActivationEmailAdmin($save));

        });
    
        return redirect()->route('admin.index')->with('berhasil', 'Berhasil Menambahkan Admin. Harap cek Inbox atau Spam');
    }

    public function edit($id){

        $find = User::find($id);
    
        $getProduct = Product::select('id','nama')->get();

        return view('admin.update', compact('find','getProduct'));
    
      }

    public function update(Request $request){

        $message = [
            'name.required' => 'Wajib di isi',
            'name.unique' => 'Nama sudah pernah ada',
            'email.required' => 'Wajib di isi',
            'email.email' => 'Format email salah',
            'email.unique' => 'Email sudah digunakan',
            'no_hp.required' => 'Wajib di isi',
            'no_hp.numeric' => 'Harap isikan nomor',
            'avatar.dimensions' => 'Ukuran yg di terima 2000px x 2000px',
            'avatar.image' => 'Format Foto Tidak Sesuai',
            'avatar.mimes' => 'Format Foto yang diterima .jpg, .png, .jpeg',
            'avatar.max' => 'File Size Terlalu Besar',
        ];
    
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:fra_users,name,'.$request->id,
            'email' => 'required|email|unique:fra_users,email,'.$request->id,
            'no_hp' => 'required|numeric|unique:fra_users,email,'.$request->id,
            'product_id' => 'required',
            'avatar' => 'image|mimes:jpeg,png,jpg|max:2000|dimensions:max_width=2000,max_height=2000',
        ], $message);
    
    
        if($validator->fails())
        {
            return redirect()->route('admin.edit',['id' => $request->id])->withErrors($validator)->withInput();
        }

        DB::transaction(function() use($request){
            $image = $request->file('avatar');
    
            $update = User::find($request->id);
    
            if($image){
                $salt = str_random(4);
                
                $img_url = str_slug($request->name,'-').'-admin-'.$salt. '.' . $image->getClientOriginalExtension();
                Image::make($image)->fit(300,300)->save('admin/images/'. $img_url);
        
                $update->avatar = $img_url;
            }
    
          
            $update->name = $request->name;
            $update->product_id = $request->product_id;
            $update->no_hp = $request->no_hp;
            $update->email = $request->email;
            $update->update();

            if($update->isDirty('email')){
                Mail::to($request->email)->send(new ActivationEmailAdmin($update));

                $save1 = new AdminLogs;
                $save1->user_id = Auth::user()->id;
                $save1->logs_name = 'Mengubah data Akun '.$request->name;
                $save1->save();
            }else{
                $save1 = new AdminLogs;
                $save1->user_id = Auth::user()->id;
                $save1->logs_name = 'Mengubah data Email Akun '.$request->name;
                $save1->save();
            }

        });
    
        return redirect()->route('admin.index')->with('berhasil', 'Berhasil Menambahkan Admin. Harap cek Inbox atau Spam Jika Mengubah Email');

    }

    public function delete($id){

        $find = User::find($id);
    
        if(!$find){
          abort(404);
        }
    
        $name = $find->name;
        $find = $find->delete();
    
        $save1 = new AdminLogs;
        $save1->user_id = Auth::user()->id;
        $save1->logs_name = 'Menghapus data akun '.$name;
        $save1->save();
    
        return response()->json([
          'success' => 'Berhasil',
          'message' => 'Akun Berhasil di Hapus',
        ]);
      }
}
