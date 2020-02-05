<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use SoftDeletes;

    protected $table = 'fra_contact';

    protected $fillable = ['product_id','nama','gambar','no_telp', 'alamat', 'created_by'];

    public function user(){
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    public function product(){
        return $this->belongsTo('App\Models\Product', 'product_id');
    }
}
