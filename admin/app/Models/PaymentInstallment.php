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
}
