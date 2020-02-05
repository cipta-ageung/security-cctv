<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cctv extends Model
{
    use SoftDeletes;

    protected $table = 'fra_cctv';

    protected $fillable = ['product_id','created_by','nama_cctv','link_stream'];

    public function user(){
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    public function product(){
        return $this->belongsTo('App\Models\Product', 'product_id');
    }
}
