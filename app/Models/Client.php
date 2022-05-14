<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $table = 'salon_clients' ;
    protected $guarded = [];

    public function salonBooking() {
        return $this->hasMany('SalonBooking', 'client_id');
    }


}
