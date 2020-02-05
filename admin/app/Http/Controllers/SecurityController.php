<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Security;
use App\Models\SecuritySchedule;
use App\Models\Product;
use App\Models\Occupant;
use Illuminate\Support\Carbon;
use App\Mail\ActivationEmailPenghuni;
use App\Mail\ChangeEmailPenghuni;
use App\Mail\ResetPasswordPenghuni;
use App\Models\AdminLogs;
use Validator;
use DB;
use Image;
use Auth;
use Log;
use Mail;

class SecurityController extends Controller
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

        $getData = Security::with('account')->where('product_id',$adminId)->get();

        return view('security.index', compact('getData'));
    }

    public function create(){

        $adminId = Auth::user()->product_id;

        $getProduct = Product::select('id','nama')->where('id',$adminId)->get();

        return view('security.create', compact('getProduct'));
    }

    public function store(Request $request){

        $message = [
            'name.required' => 'Wajib di isi',
            'name.unique' => 'Nama sudah pernah ada',
            'product_id.required' => 'Wajib di isi',
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
        ];
    
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:fra_security',
            'product_id' => 'required',
            'email' => 'required|email|unique:fra_occupant',
            'no_hp' => 'required|numeric|unique:fra_occupant',
            'avatar' => 'image|mimes:jpeg,png,jpg|max:2000|dimensions:max_width=2000,max_height=2000',
        ], $message);
    
    
        if($validator->fails())
        {
            return redirect()->route('security.create')->withErrors($validator)->withInput();
        }
    
        try{
            DB::transaction(function() use($request){
                $image = $request->file('avatar');
        
                $save = new Security;
        
                if($image){
                    $salt = str_random(4);
                    
                    $img_url = str_slug($request->name,'-').'-apps-'.$salt. '.' . $image->getClientOriginalExtension();
                    Image::make($image)->fit(300,300)->save('penjaga/images/'. $img_url);
            
                    $save->avatar = $img_url;
                }
        
              
                $save->name = $request->name;
                $save->product_id = $request->product_id;
                    
                $save->created_by = Auth::user()->id;
                $save->save();
    
                //Save Security Account to Occupant table
                $occupant = new Occupant;
    
                if($image){
                    $salt = str_random(4);
                    
                    $img_url = str_slug($request->name,'-').'-penghuni-'.$salt. '.' . $image->getClientOriginalExtension();
                    Image::make($image)->fit(100,100)->save('penghuni/images/'. $img_url);
    
                    $occupant->avatar = $img_url;
                }
    
                $occupant->product_id = $request->product_id;
                $occupant->security_id = $save->id;
                $occupant->nama_kk = $request->name;
                $occupant->no_rumah = 'Pos';
                $occupant->no_hp = $request->no_hp;
                $occupant->email = $request->email;
                $occupant->password = bcrypt('WelcomeAquila');
                
                $occupant->created_by = Auth::user()->id;
                $occupant->save();
    
                $save1 = new AdminLogs;
                $save1->user_id = Auth::user()->id;
                $save1->logs_name = 'Menambahkan Akun Security'.$request->name.' dengan email '.$request->email;
                $save1->save();
    
                Mail::to($request->email)->send(new ActivationEmailPenghuni($occupant));
    
            });
        }catch(\Exception $e){
            Log::info($e);

            return redirect()->route('security.create')->withErrors($validator)->withInput();
        }
    
        return redirect()->route('security.index')->with('berhasil', 'Berhasil Menambahkan Security');
    }

    public function edit($id){

        $find = Security::find($id);
        $occupant = Occupant::where('security_id', $id)->first();

        $getProduct = Product::select('id','nama')->get();

        return view('security.update', compact('find','occupant','getProduct'));
    }

    public function update(Request $request){

        $message = [
            'name.required' => 'Wajib di isi',
            'name.unique' => 'Nama sudah pernah ada',
            'product_id.required' => 'Wajib di isi',
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
            'name' => 'required|unique:fra_security,name,'.$request->id,
            'product_id' => 'required',
            'email' => 'required|email|unique:fra_occupant,email,'.$request->occupant_id,
            'no_hp' => 'required|numeric|unique:fra_occupant,no_hp,'.$request->no_hp,
            'avatar' => 'image|mimes:jpeg,png,jpg|max:2000|dimensions:max_width=2000,max_height=2000',
        ], $message);
    
    
        if($validator->fails())
        {
            return redirect()->route('security.update', ['id' => $request->id])->withErrors($validator)->withInput();
        }
    
        DB::transaction(function() use($request){
            $image = $request->file('avatar');
    
            $update = Security::find($request->id);
    
            if($image){
                $salt = str_random(4);
                
                $img_url = str_slug($request->name,'-').'-apps-'.$salt. '.' . $image->getClientOriginalExtension();
                Image::make($image)->fit(300,300)->save('penjaga/images/'. $img_url);
        
                $update->avatar = $img_url;
            }
    
          
            $update->name = $request->name;
            $update->product_id = $request->product_id;
                
            $update->created_by = Auth::user()->id;
            $update->update();


            //Update Security Account to Occupant table
            $occupant = Occupant::findOrFail($request->occupant_id);

            if($image){
                $salt = str_random(4);
                
                $img_url = str_slug($request->name,'-').'-penghuni-'.$salt. '.' . $image->getClientOriginalExtension();
                Image::make($image)->fit(100,100)->save('penghuni/images/'. $img_url);

                $occupant->avatar = $img_url;
            }

            $occupant->product_id = $request->product_id;
            $occupant->security_id = $update->id;
            $occupant->nama_kk = $request->name;
            $occupant->no_rumah = 'Pos';
            $occupant->no_hp = $request->no_hp;
            $occupant->email = $request->email;
            
            $occupant->created_by = Auth::user()->id;
            $occupant->update();

            if($occupant->isDirty('email')){
                Mail::to($request->email)->send(new ChangeEmailPenghuni($occupant));
            }

            $save1 = new AdminLogs;
            $save1->user_id = Auth::user()->id;
            $save1->logs_name = 'Mengubah Akun Security'.$request->name;
            $save1->save();

        });
    
        return redirect()->route('security.index')->with('berhasil', 'Berhasil Mengubah Security');
    }

    public function delete($id){

        $find = Security::find($id);
        $occupant = Occupant::where('security_id', $id)->first();
        $schedule = SecuritySchedule::where('security_id',$id)->delete();

        if(!$find){
            abort(404);
        }

        $judul = $find->name;
        $find = $find->delete();
        $occupant = $occupant->delete();

        $save1 = new AdminLogs();
        $save1->user_id = Auth::user()->id;
        $save1->logs_name = 'Menghapus data security '.$judul;
        $save1->save();

        return response()->json([
            'success' => 'Berhasil',
            'message' => 'Security Berhasil di Hapus',
        ]);
    }

    public function reset($id){
        $occupant = Occupant::where('security_id',$id)->first();

        if(!$occupant){
            abort(404);
        }

        $occupant->password = bcrypt('WelcomeAquila');
        $occupant->update();

        $save1 = new AdminLogs;
        $save1->user_id = Auth::user()->id;
        $save1->logs_name = 'Reset Password Security '.$occupant->nama_kk;
        $save1->save();

        Mail::to($occupant->email)->send(new ResetPasswordPenghuni($occupant));

        return response()->json([
            'success' => 'Berhasil',
            'message' => 'Password Security Berhasil di Reset',
        ]);      
    }
    

    public function scheduleIndex($id){

        $getSecurity = Security::findorFail($id);
        $getSchedule = SecuritySchedule::orderBy('date', 'desc')->where('security_id', $id)->get();

        return view('security.scheduleIndex', compact('getSchedule','getSecurity'));
    }

    public function scheduleCreate($id){

        $find = Security::findOrFail($id);

        return view('security.scheduleCreate', compact('find'));
    }

    public function scheduleStore(Request $request){
        
        $message = [
            'shift.required' => 'Wajib di isi',
            'shift.in' => 'Pagi/Malam',
            'shift.unique' => 'Shift sudah ada',
            'date_start.required' => 'Wajib di isi',
            'date_start.date' => 'Format tanggal salah',
            'date_start.unique' => 'Sudah bertugas pada awal tanggal',
            'date_end.required' => 'Wajib di isi',
            'date_end.date' => 'Format tanggal salah',
            'date_end.after_or_equal' => 'Tanggal Akhir tidak boleh lebih kecil dari Tanggal Mulai',
            'date_end.unique' => 'Sudah bertugas pada akhir tanggal',
        ];
    
        $validator = Validator::make($request->all(), [
            // 'security_id' => 'unique:fra_security_schedule,security_id,'.$request->id.',id',
            // 'shift' => 'required|in:Pagi,Malam|unique:fra_security_schedule,shift,'.$request->id.',id',
            // 'date_start' => 'required|date|unique:fra_security_schedule,date,'.$request->id.',id',
            // 'date_end' => 'required|date|after_or_equal:date_start|unique:fra_security_schedule,date,'.$request->id.',id',
            'shift' => 'required|in:Pagi,Malam',
            'date_start' => 'required|date',
            'date_end' => 'required|date|after_or_equal:date_start',
        ], $message);
    
    
        if($validator->fails())
        {
            return redirect()->route('securitySchedule.create', ['id' => $request->id])->withErrors($validator)->withInput();
        }

        try{

            DB::transaction(function() use($request){   
                
                $Date1 = $request->date_start; 
                $Date2 = $request->date_end; 
                
                $array = array(); 
                
                $Variable1 = strtotime($Date1); 
                $Variable2 = strtotime($Date2); 
                
                for ($currentDate = $Variable1; $currentDate <= $Variable2; $currentDate += (86400)) {
                    $Store = date('Y-m-d', $currentDate); 
                    $array[] = ['security_id' => $request->id,
                                'shift' => $request->shift,
                                'date' => $Store, 
                                'created_by' => Auth::user()->id, 
                                'created_at' => Carbon::now()]; 
                }
                
                SecuritySchedule::insert($array);
    
                $save1 = new AdminLogs;
                $save1->user_id = Auth::user()->id;
                $save1->logs_name = 'Menambahkan Jadwal Security';
                $save1->save();
            });
        }catch(\Exception $e){
            Log::info($e->getMessage());

            return redirect()->route('securitySchedule.create', ['id' => $request->id])->withInput()->with('gagal', 'Harap periksa kembali jadwal Security');
        }

        return redirect()->route('securitySchedule.index', ['id' => $request->id])->with('Berhasil Menambah Jadwal');
    }

    public function scheduleEdit($id){

        $find = SecuritySchedule::find($id);
        $occupant = Occupant::select('nama_kk', 'product_id','security_id')->where('security_id', $find->security_id)->first();

        $getProduct = Product::select('id','nama')->where('id', $occupant->product_id)->first();

        return view('security.scheduleUpdate', compact('find','occupant','getProduct'));

    }

    public function scheduleUpdate(Request $request){

        $message = [
            'shift.required' => 'Wajib di isi',
            'shift.in' => 'Pagi/Malam',
            'shift.unique' => 'Shift sudah ada',
            'date_start.required' => 'Wajib di isi',
            'date_start.date' => 'Format tanggal salah',
            'date_start.unique' => 'Sudah bertugas pada awal tanggal',
        ];
        
        $validator = Validator::make($request->all(), [
            'shift' => 'required|in:Pagi,Malam',
            'date_start' => 'required|date',
        ], $message);
        
        
        if($validator->fails())
        {
            return redirect()->route('securitySchedule.edit', ['id' => $request->id])->withErrors($validator)->withInput();
        }

        try{
            DB::transaction(function() use($request){
        
                $update = SecuritySchedule::find($request->id);          
                $update->security_id = $request->security_id;
                $update->shift = $request->shift;
                $update->date = $request->date_start;
                $update->created_by = Auth::user()->id;
                $update->update();


                $save1 = new AdminLogs;
                $save1->user_id = Auth::user()->id;
                $save1->logs_name = 'Mengubah Jadwal Security'.$request->security_name;
                $save1->save();

            });
        }catch(\Exception $e){
            Log::info($e->getMessage());

            return redirect()->route('securitySchedule.edit', ['id' => $request->id])->withInput()->with('gagal', 'Harap periksa kembali jadwal Security');
        }
        
        return redirect()->route('securitySchedule.index', ['id' => $request->security_id])->with('berhasil', 'Berhasil Mengubah Jadwal Security');
    }

    public function scheduleDelete($id){
        
        $find = SecuritySchedule::find($id);

        if(!$find){
            abort(404);
        }

        $jadwal = $find->date.' - '.$find->shift;
        $find = $find->delete();

        $save1 = new AdminLogs();
        $save1->user_id = Auth::user()->id;
        $save1->logs_name = 'Menghapus data jadwal security '.$jadwal;
        $save1->save();

        return response()->json([
            'success' => 'Berhasil',
            'message' => 'Jadwal Security Berhasil di Hapus',
        ]);
    }

}
