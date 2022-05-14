<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class city extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'cities' ;
    protected $guarded = [];

    public function cityTrans(){
        return $this->hasMany('App\Models\CityTrans');
    }

    public function scopeLang($query ,$lang_abbr){
        return $this->cityTrans()->where('abbr' , $lang_abbr )->first();
    }

    public function salons(){
        return $this->hasMany('App\Models\Salon');
    }
}
