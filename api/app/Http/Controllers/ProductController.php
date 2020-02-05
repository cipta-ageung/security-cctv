<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AboutApps;
use App\Models\Product;


class ProductController extends Controller
{

    public function list(){

        $getProduct = Product::select('id','nama')->get();

        return $this->trueResponse('berhasil',200,$getProduct);
    }

    public function detail($id){

        $getDetail = Product::where('id', $id)->with('facilities')->with('houseType')->get();

            return $this->trueResponse('berhasil', 200, $getDetail);
    }

    public function aboutApps(){

        $getAbout = AboutApps::select('nama','gambar','deskripsi')->get();

        return $this->trueResponse('berhasil',200,$getAbout);
    }
}