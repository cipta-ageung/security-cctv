<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AppsLogs;
use App\Models\Jualan;
use Tymon\JWTAuth\JWTAuth;

use DB;
use Validator;
use Image;

class JualanController extends Controller
{
    protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    public function index(Request $request){

        $getJualan = Jualan::with('penjual.product')->orderBy('created_at', 'desc')->get();

        DB::transaction(function() use($request){
            $save = new AppsLogs;
            $save->occupant_id = $this->jwt->user()->id;
            $save->logs_name = 'Melihat List Jualan';
            $save->save();
        });

        return $this->showAllWithToken('berhasil',$getJualan, $request);
    }

    public function produkSaya(Request $request){

        $getProdukSaya = Jualan::where('occupant_id',  $this->jwt->user()->id)->orderBy('created_at', 'desc')->get();

        DB::transaction(function() use($request){
            $save = new AppsLogs;
            $save->occupant_id = $this->jwt->user()->id;
            $save->logs_name = 'Melihat Produk Jualan Saya';
            $save->save();
        });

        return $this->showAllWithToken('berhasil',$getProdukSaya, $request);
    }

    public function produkPosting(Request $request){

        $validator = Validator::make($request->all(),[
            'judul'     =>'required|min:3',
            'nama'  => 'required|min:3',
            'harga'  => 'required|numeric',
            'no_hp'  => 'required|numeric|min:10',
            'gambar_1'  => 'required|image|mimes:jpeg,png,jpg|max:2000|dimensions:max_width=5000,max_height=5000'
        ]);

        if($validator->fails()){
            $data = array();
            foreach($validator->messages()->getMessages() as $field_name => $values){
                $data[$field_name]=$values[0];
            }
            return response()->json(['code'=>400, 'description'=>'Validasi gagal', 'results'=>$data],400);
        }

        DB::transaction(function() use($request){
            $gambar = $request->file('gambar_1');
    
            $save = new Jualan;
    
            if($gambar){
              $salt = str_random(4);
              
              $img_url = str_slug($request->judul,'-').'-jualan-'.$salt. '.' . $gambar->getClientOriginalExtension();
              Image::make($gambar)->fit(500,300)->save('jualan/images/'. $img_url);
    
              $save->gambar_1 = $img_url;
            }

            $save->occupant_id = $this->jwt->user()->id;
            $save->judul = $request->judul;
            $save->nama = $request->nama;                
            $save->harga = $request->harga;                
            $save->no_hp = $request->no_hp;
            $save->save();

            $save1 = new AppsLogs;
            $save1->occupant_id = $this->jwt->user()->id;
            $save1->logs_name = 'Membuat Jualan '.$request->judul.' dengan nama '.$request->nama;
            $save1->save();
            
          });
      
        return $this->responseWithToken('Produk/Jasa Anda Berhasil di Posting',201);
    }

    public function update(Request $request){

        $update = Jualan::findOrFail($request->id);

        if(!$update){
            return $this->trueResponse('Jualan tidak ditemukan', 400);
        }

        $validator = Validator::make($request->all(), [
            'occupant_id' => 'required',
            'judul'       => 'required|min:3',
            'nama'        => 'required|min:3',
            'harga'       => 'required|numeric',
            'no_hp'       => 'required|numeric|min:10',
            'gambar_1'    => 'image|mimes:jpeg,png,jpg|max:2000|dimensions:max_width=5000,max_height=5000',
        ]);

        if ($validator->fails()) {
            $data = array();
            foreach ($validator->messages()->getMessages() as $field_name => $values) {
                $data[$field_name] = $values[0];
            }
            return $this->trueResponse('validasi gagal', 400, $data);
        }

        DB::transaction(function() use($request,$update){

            $gambar = $request->file('gambar_1');

            if($gambar){
                $salt = str_random(4);
                
                $img_url = str_slug($request->judul,'-').'-jualan-'.$salt. '.' . $gambar->getClientOriginalExtension();
                Image::make($gambar)->fit(500,300)->save('jualan/images/'. $img_url);
        
                $update->gambar_1 = $img_url;
            }

            $update->occupant_id = $this->jwt->user()->id;
            $update->judul = $request->judul;
            $update->nama = $request->nama;                
            $update->harga = $request->harga;                
            $update->no_hp = $request->no_hp;
            $update->update();

            $save1 = new AppsLogs;
            $save1->occupant_id = $this->jwt->user()->id;
            $save1->logs_name = 'Mengubah Jualan '.$request->judul.' dengan nama '.$request->nama;
            $save1->save();
        });


        return $this->responseWithToken('Produk/Jasa Anda Berhasil di Ubah',201);
    }

    public function detail($id){

        $getDetail = Jualan::select('id','judul','nama','harga','no_hp','gambar_1','created_at')->where('id',$id)->get();

        if(!$getDetail){
            return $this->trueResponse('Data tidak ditemukan', 400);
        }

        return $this->trueResponse('berhasil', 200, $getDetail);
    }

    public function delete(Request $request){

        $find = Jualan::findOrFail($request->id);

        if(!$find){
            return $this->trueResponse('Jualan tidak ditemukan', 400);
        }

        $judul = $find->judul.' '.$find->nama;
        $find->delete();

        $save1 = new AppsLogs();
        $save1->occupant_id = $this->jwt->user()->id;
        $save1->logs_name = 'Menghapus data jualan '.$judul;
        $save1->save();

        return $this->responseWithToken('Produk/Jasa Anda Berhasil di Hapus',201);
    }
}