<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Facilities extends Model
{
    use SoftDeletes;
    
    protected $table = 'fra_facilities';

    protected $fillable = ['product_id','image','created_by'];

    public function user(){
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    public function cluster(){
        return $this->belongsTo('App\Models\Product','product_id');
    }
}
