<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class SecuritySchedule extends Model
{
    use SoftDeletes;
    
    protected $table = 'fra_security_schedule';

    protected $fillable = ['security_id','shift','date','created_by'];

    public function user(){
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    public function security(){
        return $this->belongsTo('App\Models\Security', 'security_id');
    }
}
