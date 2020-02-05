<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PanicLogs extends Model
{
    protected $table = 'fra_panic_logs';

    protected $fillable = ['user_id','sent_to','logs_name'];

    public function occupant(){
        return $this->belongsTo('App\Models\Occupant', 'user_id');
    }
}
