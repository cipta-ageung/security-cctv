<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentInstallment extends Model
{
    protected $table = 'fra_payment_installment';

    protected $fillable = ['occupant_id','month','nominal','read_flag','created_by'];

    public function user(){
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    public function penghuni(){
        return $this->belongsTo('App\Models\Occupant', 'occupant_id');
    }

    public function getNominalAttribute($nominal){
        return 'Rp. '.number_format($nominal,'0','.','.');
    }

    public function getMonthAttribute($month){
        return substr($month, 0, 7);
    }
}
