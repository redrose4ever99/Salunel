<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
	protected $table = 'services' ;
    use HasFactory;
	  protected $guarded = [];
	
	public function salonService(){
        return $this->hasMany('App\Models\SalonService');
    }
	
	  public function serviceTrans(){
        return $this->hasMany('App\Models\ServiceTrans');
    }
	
    public function salonService(){
        return $this->hasMany('App\Models\SalonService');
    }

    public function salons(){
        return $this->belongsToMany('App\Models\Salon')->withPivot('main_price' , 'main_duration' , 'rating');
    }
	
}
