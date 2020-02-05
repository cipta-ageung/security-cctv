<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\News;

class BeritaController extends Controller
{

    public function index(Request $request){

        $getNews = News::orderBy('created_at', 'desc')->get();

        return $this->showAll('berhasil',$getNews,$request);
    }

    public function detail($id){

        $getDetail = News::where('id', $id)->get();

        return $this->trueResponse('berhasil', 200, $getDetail);
    }
}