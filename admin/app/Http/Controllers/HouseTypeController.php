<?php

namespace App\Http\Controllers;

use App\Models\AdminLogs;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\HouseType;

use Validator;
use DB;
use Image;
use Auth;

class HouseTypeController extends Controller
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

    public function index($id){
        
        $getProduct = Product::findOrFail($id);
        $getHouseType = HouseType::where('product_id', $id)->get();

        return view('houseType.index', compact('getProduct','getHouseType'));
    }

    public function create($id){

        $find = Product::findOrFail($id);

        return view('houseType.create', compact('find'));
    }

    public function store(Request $request){

      $find = Product::findOrFail($request->id);

      if(!$find){
        abort(404);
      }

      $message = [
        'name.required' => 'Wajib di isi',
        'name.unique' => 'Nama tipe rumah sudah pernah ada',
        'image.dimensions' => 'Ukuran yg di terima 3000px x 3000px',
        'image.image' => 'Format Gambar Tidak Sesuai',
        'image.mimes' => 'Format Gambar yang diterima .jpg, .png, .jpeg',
        'image.max' => 'File Size Terlalu Besar',
      ];

      $validator = Validator::make($request->all(), [
        'name' => 'required|unique:fra_house_type,name,'.$request->id,
        'image' => 'image|mimes:jpeg,png,jpg|max:1500|dimensions:max_width=3000,max_height=3000',
      ], $message);


      if($validator->fails())
      {
        return redirect()->route('houseType.create', ['id'=>$request->id])->withErrors($validator)->withInput();
      }

      DB::transaction(function() use($request){
          $gambar = $request->file('image');
  
          $save = new HouseType;
  
          if($gambar){
            $salt = str_random(4);
            
            $img_url = str_slug($request->name,'-').'-apps-'.$salt. '.' . $gambar->getClientOriginalExtension();
            Image::make($gambar)->fit(500,300)->save('produk/images/'. $img_url);
  
            $save->image = $img_url;
          }

          $save->product_id = $request->id;
          $save->name = $request->name;                
          $save->created_by = Auth::user()->id;
          $save->save();

          $save1 = new AdminLogs();
          $save1->user_id = Auth::user()->id;
          $save1->logs_name = 'Membuat data tipe rumah '.$request->name;
          $save1->save();
        });
    
      return redirect()->route('houseType.index', ['id' => $request->id])->with('berhasil', 'Berhasil Menambahkan Tipe Rumah');
    }

    public function delete($id){

        $find = HouseType::find($id);

        if(!$find){
            abort(404);
        }

        $judul = $find->name;
        $product_id = $find->product_id;
        $find = $find->delete();

        $save1 = new AdminLogs();
        $save1->user_id = Auth::user()->id;
        $save1->logs_name = 'Menghapus data Tipe Rumah '.$judul;
        $save1->save();

        return response()->json([
          'success' => 'Berhasil',
          'message' => 'Tipe Rumah Berhasil di Hapus',
        ]);
    }
}
