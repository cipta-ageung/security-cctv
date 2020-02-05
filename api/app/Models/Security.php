<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Security extends Model
{
    use SoftDeletes;
    
    protected $table = 'fra_security';

    protected $fillable = ['name','avatar','product_id','created_by'];

    public function user(){
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    public function cluster(){
        return $this->belongsTo('App\Models\Product', 'product_id');
    }

    public function schedules(){
        return $this->hasMany('App\Models\SecuritySchedule', 'security_id', 'id');
    }

    public function account(){
        return $this->hasOne('App\Models\Occupant');
    }
}
