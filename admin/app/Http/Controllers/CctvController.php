<?php

namespace App\Http\Controllers;

use App\Models\AdminLogs;
use Illuminate\Http\Request;
use App\Models\Cctv;
use App\Models\Product;

use Validator;
use DB;
use Auth;

class CctvController extends Controller
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

        $getData = Cctv::get()->groupBy('product_id');

        return view('cctv.index', compact('getData'));
    }

    public function create(){

        $getProduct = Product::select('id','nama')->get();
        
        return view('cctv.create', compact('getProduct'));
    }

    public function store(Request $request){

        $message = [
            'nama_cctv.required' => 'Wajib di isi',
            'nama_cctv.unique' => 'Nama CCTV sudah pernah ada',
            'link_stream.required' => 'Wajib di isi',
            'product_id.required' => 'Wajib di isi',
        ];

        $validator = Validator::make($request->all(), [
            'nama_cctv' => 'required|unique:fra_cctv',
            'link_stream' => 'required',
            'product_id' => 'required',
        ], $message);


        if($validator->fails())
        {
            return redirect()->route('cctv.create')->withErrors($validator)->withInput();
        }
        

        DB::transaction(function() use($request){
            $save = new Cctv;       
            $save->nama_cctv = $request->nama_cctv;
            $save->link_stream = $request->link_stream;
            $save->product_id = $request->product_id;
            $save->created_by = Auth::user()->id;
            $save->save();

            $save1 = new AdminLogs;
            $save1->user_id = Auth::user()->id;
            $save1->logs_name = 'Menambahkan cctv '.$request->nama_cctv;
            $save1->save();
        });

        return redirect()->route('cctv.index')->with('berhasil', 'Berhasil Menambahkan CCTV ');
    }

    public function edit($id){

        $find = Cctv::find($id);
        $getProduct = Product::get();

        return view('cctv.update', compact('find','getProduct'));

    }

    public function update(Request $request){

        $find = Cctv::findOrFail($request->id);
  
        $message = [
            'nama_cctv.required' => 'Wajib di isi',
            'nama_cctv.unique' => 'Nama CCTV sudah pernah ada',
            'link_stream.required' => 'Wajib di isi',
            'product_id.required' => 'Wajib di isi',
        ];
  
        $validator = Validator::make($request->all(), [
          'nama_cctv' => 'required|unique:fra_cctv,nama_cctv,'.$request->id,
          'link_stream' => 'required',
          'product_id' => 'required',
        ], $message);
  
  
        if($validator->fails())
        {
          return redirect()->route('cctv.update', ['id'=>$request->id])->withErrors($validator)->withInput();
        }
  
        DB::transaction(function() use($request){  
          $update = Cctv::find($request->id);
   
          $update->nama_cctv = $request->nama_cctv;
          $update->link_stream = $request->link_stream;
          $update->product_id = $request->product_id;
          $update->created_by = Auth::user()->id;
          $update->update();

          $save1 = new AdminLogs;
          $save1->user_id = Auth::user()->id;
          $save1->logs_name = 'Mengubah cctv '.$request->nama_cctv;
          $save1->save();
        });
  
        return redirect()->route('cctv.index')->with('berhasil', 'Berhasil Mengubah CCTV.');
  
    }

    public function delete($id){

        $find = Cctv::find($id);
    
        if(!$find){
            abort(404);
        }
    
        $judul = $find->nama;
        $find = $find->delete();
    
        $save1 = new AdminLogs();
        $save1->user_id = Auth::user()->id;
        $save1->logs_name = 'Menghapus data cctv '.$judul;
        $save1->save();
    
        return response()->json([
            'success' => 'Berhasil',
            'message' => 'CCTV Berhasil di Hapus',
          ]);
      }
  
}
