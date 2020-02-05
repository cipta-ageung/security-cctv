<?php

namespace App\Http\Controllers;

use App\Models\AdminLogs;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Product;

use Validator;
use DB;
use Image;
use Auth;

class ContactController extends Controller
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

  public function index()
  {

    $adminId = Auth::user()->product_id;
    
    $getData = Contact::where('product_id', $adminId)->get();

    return view('contact.index', compact('getData'));
  }

  public function create()
  {

    $adminId = Auth::user()->product_id;

    $getProduct = Product::where('id', $adminId)->get();

    return view('contact.create', compact('getProduct'));
  }

  public function store(Request $request)
  {

    $message = [
      'nama.required' => 'Wajib di isi',
      'nama.unique' => 'Nama kontak sudah pernah ada',
      'alamat.required' => 'Wajib di isi',
      'product_id.required' => 'Wajib di isi',
      'no_telp.required' => 'Wajib di isi',
      'no_telp.numeric' => 'Harus angka',
      'gambar.dimensions' => 'Ukuran yg di terima 3000px x 3000px',
      'gambar.image' => 'Format Gambar Tidak Sesuai',
      'gambar.mimes' => 'Format Gambar yang diterima .jpg, .png, .jpeg',
      'gambar.max' => 'File Size Terlalu Besar',
    ];

    $validator = Validator::make($request->all(), [
      'nama' => 'required|unique:fra_contact',
      'alamat' => 'required',
      'product_id' => 'required',
      'no_telp' => 'required|numeric',
      'gambar' => 'image|mimes:jpeg,png,jpg|max:2000|dimensions:max_width=3000,max_height=3000',
    ], $message);


    if($validator->fails())
    {
      return redirect()->route('contact.create')->withErrors($validator)->withInput();
    }


    DB::transaction(function() use($request){
      $gambar = $request->file('gambar');

      $save = new Contact;

      if($gambar){
        $salt = str_random(4);
        
        $img_url = str_slug($request->nama,'-').'-apps-'.$salt. '.' . $gambar->getClientOriginalExtension();
        Image::make($gambar)->save('kontak/images/'. $img_url);

        $save->gambar = $img_url;
      }

      
      $save->nama = $request->nama;
      $save->no_telp = $request->no_telp;
      $save->product_id = $request->product_id;
      $save->alamat = $request->alamat;
          
      $save->created_by = Auth::user()->id;
      $save->save();

      $save1 = new AdminLogs;
      $save1->user_id = Auth::user()->id;
      $save1->logs_name = 'Menambahkan Kontak Layanan '.$request->nama;
      $save1->save();
    });

    return redirect()->route('contact.index')->with('berhasil', 'Berhasil Menambahkan Kontak Layanan');

  }

  public function edit($id){

    $find = Contact::findOrFail($id);

    $adminId = Auth::user()->product_id;
        
    $getProduct = Product::select('id', 'nama')->where('id', $adminId)->get();

    return view('contact.update', compact('find', 'getProduct'));

  }

  public function update(Request $request){

    $find = Contact::findOrFail($request->id);

    $message = [
      'nama.required' => 'Wajib di isi',
      'nama.unique' => 'Nama kontak sudah pernah ada',
      'alamat.required' => 'Wajib di isi',
      'product_id.required' => 'Wajib di isi',
      'no_telp.required' => 'Wajib di isi',
      'gambar.dimensions' => 'Ukuran yg di terima 3000px x 3000px',
      'gambar.image' => 'Format Gambar Tidak Sesuai',
      'gambar.mimes' => 'Format Gambar yang diterima .jpg, .png, .jpeg',
      'gambar.max' => 'File Size Terlalu Besar',
    ];

    $validator = Validator::make($request->all(), [
      'nama' => 'required|unique:fra_contact,nama,'.$request->id,
      'alamat' => 'required',
      'product_id' => 'required',
      'no_telp' => 'required',
      'gambar' => 'image|mimes:jpeg,png,jpg|max:2000|dimensions:max_width=3000,max_height=3000',
    ], $message);


    if($validator->fails())
    {
      return redirect()->route('contact.update', ['id'=>$request->id])->withErrors($validator)->withInput();
    }

    DB::transaction(function() use($request){
      $gambar = $request->file('gambar');

      $update = Contact::find($request->id);

        if($gambar){
          $salt = str_random(4);

          $img_url = str_slug($request->nama,'-').'-aquila-land-'.$salt. '.' . $gambar->getClientOriginalExtension();
          Image::make($gambar)->save('kontak/images/'. $img_url);

          $update->gambar = $img_url;
        }

      $update->nama = $request->nama;
      $update->alamat = $request->alamat;
      $update->product_id = $request->product_id;
      $update->no_telp = $request->no_telp;
      $update->created_by = Auth::user()->id;
      $update->update();

      $save1 = new AdminLogs;
      $save1->user_id = Auth::user()->id;
      $save1->logs_name = 'Mengubah Kontak Layanan '.$request->nama;
      $save1->save();
    });

    return redirect()->route('contact.index')->with('berhasil', 'Berhasil Mengubah Kontak Layanan.');  
  }

  public function delete($id){

    $find = Contact::find($id);

    if(!$find){
        abort(404);
    }

    $judul = $find->nama;
    $find = $find->delete();

    $save1 = new AdminLogs();
    $save1->user_id = Auth::user()->id;
    $save1->logs_name = 'Menghapus data kontak Layanan '.$judul;
    $save1->save();

    return response()->json([
      'success' => 'Berhasil',
      'message' => 'Kontak Layanan Berhasil di Hapus',
    ]);
  }
}
