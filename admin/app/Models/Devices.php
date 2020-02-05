<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Devices extends Model
{
    use SoftDeletes;

    protected $table = 'fra_devices';

    protected $fillable = ['device_id'];

}
