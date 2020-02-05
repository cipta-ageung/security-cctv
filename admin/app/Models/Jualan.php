<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jualan extends Model
{
    use SoftDeletes;

    protected $table = 'fra_jualan_blade';

    protected $fillable = ['occupant_id','judul','nama','harga','no_hp','gambar_1','gambar_2'];

    public function penjual(){
        return $this->belongsTo('App\Models\Occupant', 'occupant_id');
    }
}
