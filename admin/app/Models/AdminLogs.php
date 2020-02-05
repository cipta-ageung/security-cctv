<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminLogs extends Model
{
    protected $table = 'fra_admin_logs';

    protected $fillable = ['user_id','logs_name'];

    public function user(){
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
