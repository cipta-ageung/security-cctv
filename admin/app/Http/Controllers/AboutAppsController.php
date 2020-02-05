<?php

namespace App\Http\Controllers;

use App\Models\AboutApps;
use App\Models\AdminLogs;
use Illuminate\Http\Request;

use Validator;
use Auth;
use DB;
use Image;

class AboutAppsController extends Controller
{
    public function index(){

        $getData = AboutApps::get();

        return view('about.index', compact('getData'));
        
    }

    public function create(){

        return view('about.create');
    }

    public function store(Request $request){

        $message = [
            'nama.required' => 'Wajib di isi',
            'nama.unique' => 'Nama sudah pernah ada',
            'deskripsi.required' => 'Wajib di isi',
            'gambar.required' => 'Wajib di isi',
            'gambar.dimensions' => 'Ukuran yg di terima max 1000px x 1000px',
            'gambar.image' => 'Format Gambar Tidak Sesuai',
            'gambar.mimes' => 'Format Gambar yang diterima .jpg, .png, .jpeg',
            'gambar.max' => 'File Size Terlalu Besar',
        ];
        
        $validator = Validator::make($request->all(), [
            'nama' => 'required|unique:fra_about_apps',
            'deskripsi' => 'required',
            'gambar' => 'image|mimes:jpeg,png,jpg|max:1500|dimensions:max_width=3000,max_height=6000',
        ], $message);
    
    
        if($validator->fails()){
            return redirect()->route('about.create')->withErrors($validator)->withInput();
        }

        DB::transaction(function() use($request){
            $gambar = $request->file('gambar');
        
            $save = new AboutApps;
            $salt = str_random(4);
        
            if($gambar){
                $img_url_gambar = str_slug($request->nama,'-').'about-apps-'.$salt. '.' . $gambar->getClientOriginalExtension();
                Image::make($gambar)->save('produk/images/'. $img_url_gambar);
        
                $save->gambar = $img_url_gambar;
            }
        
            
            $save->nama = $request->nama;
            $save->deskripsi = $request->deskripsi;
                
            $save->created_by = Auth::user()->id;
            $save->save();
        
            $save1 = new AdminLogs;
            $save1->user_id = Auth::user()->id;
            $save1->logs_name = 'Menambahkan Tentang Aplikasi '.$request->nama;
            $save1->save();
        });
    
        return redirect()->route('about.index')->with('berhasil', 'Berhasil Menambahkan Tentang Aplikasi');
    }

    public function edit($id){

        $find = AboutApps::find($id);
    
        return view('about.update', compact('find'));
    
    }

    public function update(Request $request){

        $find = AboutApps::findOrFail($request->id);

        if(!$find){
            abort('404');
        }

        $message = [
            'nama.required' => 'Wajib di isi',
            'nama.unique' => 'Nama sudah pernah ada',
            'deskripsi.required' => 'Wajib di isi',
            'video.required' => 'Wajib di isi',
            'gambar.dimensions' => 'Ukuran yg di terima max 6000px x 6000px',
            'gambar.image' => 'Format Gambar Tidak Sesuai',
            'gambar.mimes' => 'Format Gambar yang diterima .jpg, .png, .jpeg',
            'gambar.max' => 'File Size Terlalu Besar',
        ];
    
        $validator = Validator::make($request->all(), [
            'nama' => 'required|unique:fra_about_apps,nama,'.$request->id,
            'deskripsi' => 'required',
            'gambar' => 'image|mimes:jpeg,png,jpg|max:1500|dimensions:max_width=3000,max_height=6000',
        ], $message);
    
    
        if($validator->fails())
        {
            return redirect()->route('about.edit', ['id'=>$request->id])->withErrors($validator)->withInput();
        }

        DB::transaction(function() use($request){
            $gambar = $request->file('gambar');
        
            $save = AboutApps::find($request->id);
            $salt = str_random(4);
        
            if($gambar){
                $img_url_gambar = str_slug($request->nama,'-').'about-apps-'.$salt. '.' . $gambar->getClientOriginalExtension();
                Image::make($gambar)->save('produk/images/'. $img_url_gambar);
        
                $save->gambar = $img_url_gambar;
            }
        
            $save->nama = $request->nama;
            $save->deskripsi = $request->deskripsi;
                
            $save->created_by = Auth::user()->id;
            $save->save();
        
            $save1 = new AdminLogs;
            $save1->user_id = Auth::user()->id;
            $save1->logs_name = 'Mengubah Tentang Aplikasi '.$request->nama;
            $save1->save();
        });
    
        return redirect()->route('about.index')->with('berhasil', 'Berhasil Merubah Tentang Aplikasi');

    }

    public function delete($id){

        $find = AboutApps::find($id);

        if(!$find){
            abort(404);
        }

        $nama = $find->nama;
        $find = $find->delete();

        $save1 = new AdminLogs();
        $save1->user_id = Auth::user()->id;
        $save1->logs_name = 'Menghapus data Tentang aplikasi '.$nama;
        $save1->save();

        return redirect()->route('about.index')->with('berhasil', 'Tentang Aplikasi di Hapus');
    }
}
