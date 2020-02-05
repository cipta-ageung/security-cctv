<?php

namespace App\Http\Controllers;

use App\Models\AdminLogs;
use Illuminate\Http\Request;
use App\Models\Product;

use Validator;
use DB;
use Image;
use Auth;

class ProductController extends Controller
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
      
    $getData = Product::get();

    return view('product.index', compact('getData'));
  }

  public function create(){

    return view('product.create');
  }

  public function store(Request $request){

    $message = [
      'nama.required' => 'Wajib di isi',
      'nama.unique' => 'Nama Product sudah pernah ada',
      'lokasi.required' => 'Wajib di isi',
      'video.required' => 'Wajib di isi',
      'master_plan.required' => 'Wajib di isi',
      'master_plan.dimensions' => 'Ukuran yg di terima max 3000px x 6000px',
      'master_plan.image' => 'Format Gambar Tidak Sesuai',
      'master_plan.mimes' => 'Format Gambar yang diterima .jpg, .png, .jpeg',
      'master_plan.max' => 'File Size Terlalu Besar',
      'brosur_1.required' => 'Wajib di isi',
      'brosur_1.dimensions' => 'Ukuran yg di terima max 3000px x 6000px',
      'brosur_1.image' => 'Format Gambar Tidak Sesuai',
      'brosur_1.mimes' => 'Format Gambar yang diterima .jpg, .png, .jpeg',
      'brosur_1.max' => 'File Size Terlalu Besar',
      'brosur_2.dimensions' => 'Ukuran yg di terima max 3000px x 6000px',
      'brosur_2.image' => 'Format Gambar Tidak Sesuai',
      'brosur_2.mimes' => 'Format Gambar yang diterima .jpg, .png, .jpeg',
      'brosur_2.max' => 'File Size Terlalu Besar',
      'brosur_3.dimensions' => 'Ukuran yg di terima max 3000px x 6000px',
      'brosur_3.image' => 'Format Gambar Tidak Sesuai',
      'brosur_3.mimes' => 'Format Gambar yang diterima .jpg, .png, .jpeg',
      'brosur_3.max' => 'File Size Terlalu Besar',
    ];

    $validator = Validator::make($request->all(), [
      'nama' => 'required|unique:fra_product',
      'lokasi' => 'required',
      'video' => 'required',
      'brosur_1' => 'required|image|mimes:jpeg,png,jpg|max:1500|dimensions:max_width=3000,max_height=6000',
      'brosur_2' => 'image|mimes:jpeg,png,jpg|max:1500|dimensions:max_width=3000,max_height=6000',
      'brosur_3' => 'image|mimes:jpeg,png,jpg|max:1500|dimensions:max_width=3000,max_height=6000',
      'master_plan' => 'required|image|mimes:jpeg,png,jpg|max:1500|dimensions:max_width=3000,max_height=6000',
    ], $message);


    if($validator->fails())
    {
      return redirect()->route('product.create')->withErrors($validator)->withInput();
    }

    DB::transaction(function() use($request){
      $brosur_1 = $request->file('brosur_1');
      $brosur_2 = $request->file('brosur_2');
      $brosur_3 = $request->file('brosur_3');
      $master_plan = $request->file('master_plan');

      $save = new Product;
      $salt = str_random(4);

      if($brosur_1){
        $img_url_brosur_1 = str_slug($request->nama,'-').'-apps-'.$salt. '.' . $brosur_1->getClientOriginalExtension();
        Image::make($brosur_1)->save('produk/images/'. $img_url_brosur_1);

        $save->brosur_1 = $img_url_brosur_1;
      }

      if($brosur_2){
        $img_url_brosur_2 = str_slug($request->nama,'-').'-2-apps-'.$salt. '.' . $brosur_2->getClientOriginalExtension();
        Image::make($brosur_2)->save('produk/images/'. $img_url_brosur_2);

        $save->brosur_2 = $img_url_brosur_2;
      }

      if($brosur_3){
        $img_url_brosur_3 = str_slug($request->nama,'-').'-3-apps-'.$salt. '.' . $brosur_3->getClientOriginalExtension();
        Image::make($brosur_3)->save('produk/images/'. $img_url_brosur_2);

        $save->brosur_3 = $img_url_brosur_3;
      }

      if($master_plan){
        $img_url_master_plan = str_slug($request->nama,'-').'master-plan-apps-'.$salt. '.' . $master_plan->getClientOriginalExtension();
        Image::make($master_plan)->save('produk/images/'. $img_url_master_plan);

        $save->master_plan = $img_url_master_plan;
      }

      
      $save->nama = $request->nama;
      $save->video = $request->video;
      $save->lokasi = $request->lokasi;
          
      $save->created_by = Auth::user()->id;
      $save->save();

      $save1 = new AdminLogs;
      $save1->user_id = Auth::user()->id;
      $save1->logs_name = 'Menambahkan Product '.$request->nama;
      $save1->save();
    });

    return redirect()->route('product.index')->with('berhasil', 'Berhasil Menambahkan Product');
  }

  public function edit($id){

    $find = Product::find($id);

    return view('product.update', compact('find'));

  }

  public function update(Request $request){
    
    $find = Product::findOrFail($request->id);

    if(!$find){
      abort('404');
    }

    $message = [
      'nama.required' => 'Wajib di isi',
      'nama.unique' => 'Nama Product sudah pernah ada',
      'lokasi.required' => 'Wajib di isi',
      'video.required' => 'Wajib di isi',
      'master_plan.dimensions' => 'Ukuran yg di terima max 6000px x 6000px',
      'master_plan.image' => 'Format Gambar Tidak Sesuai',
      'master_plan.mimes' => 'Format Gambar yang diterima .jpg, .png, .jpeg',
      'master_plan.max' => 'File Size Terlalu Besar',
      'brosur_1.dimensions' => 'Ukuran yg di terima max 6000px x 6000px',
      'brosur_1.image' => 'Format Gambar Tidak Sesuai',
      'brosur_1.mimes' => 'Format Gambar yang diterima .jpg, .png, .jpeg',
      'brosur_1.max' => 'File Size Terlalu Besar',
      'brosur_2.dimensions' => 'Ukuran yg di terima max 6000px x 6000px',
      'brosur_2.image' => 'Format Gambar Tidak Sesuai',
      'brosur_2.mimes' => 'Format Gambar yang diterima .jpg, .png, .jpeg',
      'brosur_2.max' => 'File Size Terlalu Besar',
      'brosur_3.dimensions' => 'Ukuran yg di terima max 6000px x 6000px',
      'brosur_3.image' => 'Format Gambar Tidak Sesuai',
      'brosur_3.mimes' => 'Format Gambar yang diterima .jpg, .png, .jpeg',
      'brosur_3.max' => 'File Size Terlalu Besar',
    ];

    $validator = Validator::make($request->all(), [
      'nama' => 'required|unique:fra_product,nama,'.$request->id,
      'lokasi' => 'required',
      'video' => 'required',
      'brosur_1' => 'image|mimes:jpeg,png,jpg|max:1500|dimensions:max_width=6000,max_height=6000',
      'brosur_2' => 'image|mimes:jpeg,png,jpg|max:1500|dimensions:max_width=6000,max_height=6000',
      'brosur_3' => 'image|mimes:jpeg,png,jpg|max:1500|dimensions:max_width=6000,max_height=6000',
      'master_plan' => 'image|mimes:jpeg,png,jpg|max:1500|dimensions:max_width=6000,max_height=6000',
    ], $message);


    if($validator->fails())
    {
      return redirect()->route('product.edit', ['id'=>$request->id])->withErrors($validator)->withInput();
    }


    DB::transaction(function() use($request){
      $brosur_1 = $request->file('brosur_1');
      $brosur_2 = $request->file('brosur_2');
      $brosur_3 = $request->file('brosur_3');
      $master_plan = $request->file('master_plan');

      $save = Product::find($request->id);
      $salt = str_random(4);

      if($brosur_1){
        $img_url_brosur_1 = str_slug($request->nama,'-').'-apps-'.$salt. '.' . $brosur_1->getClientOriginalExtension();
        Image::make($brosur_1)->save('produk/images/'. $img_url_brosur_1);

        $save->brosur_1 = $img_url_brosur_1;
      }

      if($brosur_2){
        $img_url_brosur_2 = str_slug($request->nama,'-').'-2-apps-'.$salt. '.' . $brosur_2->getClientOriginalExtension();
        Image::make($brosur_2)->save('produk/images/'. $img_url_brosur_2);

        $save->brosur_2 = $img_url_brosur_2;
      }

      if($brosur_3){
        $img_url_brosur_3 = str_slug($request->nama,'-').'-3-apps-'.$salt. '.' . $brosur_3->getClientOriginalExtension();
        Image::make($brosur_3)->save('produk/images/'. $img_url_brosur_3);

        $save->brosur_3 = $img_url_brosur_3;
      }

      if($master_plan){
        $img_url_master_plan = str_slug($request->nama,'-').'master-plan-apps-'.$salt. '.' . $master_plan->getClientOriginalExtension();
        Image::make($master_plan)->save('produk/images/'. $img_url_master_plan);

        $save->master_plan = $img_url_master_plan;
      }

      $save->nama = $request->nama;
      $save->video = $request->video;
      $save->lokasi = $request->lokasi;
          
      $save->created_by = Auth::user()->id;
      $save->save();

      $save1 = new AdminLogs;
      $save1->user_id = Auth::user()->id;
      $save1->logs_name = 'Mengubah Produk '.$request->nama;
      $save1->save();
    });

    return redirect()->route('product.index')->with('berhasil', 'Berhasil Merubah Product');
  }

}
