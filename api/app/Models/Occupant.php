<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Occupant extends Model
{
    use SoftDeletes;
    
    protected $table = 'fra_occupant';

    protected $fillable = ['product_id','security_id','no_rumah','nama_kk','poto','no_hp','email','password','created_by'];

    protected $hidden = [
        'password','device_id','security_id','poto','created_by','email_verified_at','deleted_at'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function product(){
        return $this->belongsTo('App\Models\Product', 'product_id');
    }

    public function user(){
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    public function sekuriti(){
        return $this->belongsTo('App\Models\Security', 'security_id');
    }
}
