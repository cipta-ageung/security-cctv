<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppsLogs extends Model
{
    protected $table = 'fra_apps_logs';

    protected $fillable = ['occupant_id','logs_name'];

    public function user(){
        return $this->belongsTo('App\Models\Occupant', 'occupant_id');
    }
}
