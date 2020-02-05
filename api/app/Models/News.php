<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class News extends Model
{
    use SoftDeletes;
    
    protected $table = 'fra_news';

    protected $fillable = ['judul','gambar','kategori','isi_berita','created_by'];

    public function user(){
        return $this->belongsTo('App\Models\User', 'created_by');
    }
}
