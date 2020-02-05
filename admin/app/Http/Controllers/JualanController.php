<?php

namespace App\Http\Controllers;

use App\Models\AdminLogs;
use App\Models\Jualan;

use Auth;

class JualanController extends Controller
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

        $getJualan = Jualan::get();

        return view('jualan.index', compact('getJualan'));
    }

    public function delete($id){

        $find = Jualan::find($id);

        if(!$find){
            abort(404);
        }

        $judul = $find->judul;
        $find = $find->delete();

        $save1 = new AdminLogs();
        $save1->user_id = Auth::user()->id;
        $save1->logs_name = 'Menghapus data jualan '.$judul;
        $save1->save();

        return response()->json([
            'success' => 'Berhasil',
            'message' => 'Jualan Berhasil di Hapus',
        ]);
    }
    
}
