<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salon extends Model
{
    use HasFactory;
    protected $table = 'salons' ;
    protected $guarded = [];

    public function city(){
        return $this->belongsTo('App\Models\City');
    }

    public function attachments(){
        return $this->hasMany('App\Models\SalonAttachment');
    }

    public function staffs(){
        return $this->hasMany('App\Models\Staff');
    }

    public function salonService(){
        return $this->hasMany('App\Models\SalonService');
    }

    public function services(){
        return $this->belongsToMany('App\Models\Service')->withPivot('main_price' , 'main_duration' , 'rating');
    }

}