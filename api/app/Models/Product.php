<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Product extends Model
{
    use SoftDeletes;
    
    protected $table = 'fra_product';

    protected $fillable = ['nama','lokasi','brosur_1','brosur_2','brosur_3','master_plan','video','created_by'];

    public function user(){
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    public function facilities(){
            return $this->hasMany('App\Models\Facilities');
    }

    public function houseType(){
        return $this->hasMany('App\Models\HouseType');
    }
}
