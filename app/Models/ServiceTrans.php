<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceTrans extends Model
{
     use HasFactory;
    public $timestamps = false;
    protected $table = 'service_trans' ;
    protected $guarded = [];

    public function service(){
        return $this->belongsTo('App\Models\Category');
    }
}
