<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutApps extends Model
{
    protected $table = 'fra_about_apps';

    protected $fillable = ['nama','gambar','deskripsi','created_by'];

    public function user(){
        return $this->belongsTo('App\Models\User', 'created_by');
    }
}
