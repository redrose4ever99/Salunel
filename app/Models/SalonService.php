<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\SalonWorkingHours;
class SalonService extends Model
{
    use HasFactory;
    protected $table = 'salon_service' ;
    protected $guarded = [];
    public $hour, $minute;

   /* public function __construct(array $attributes = array()) {
		// Run parent Eloquent model construct method before setup
		parent::__construct($attributes);

		// Set public accessors
		$this->hour = Time::parse($this->duration)->hour;
		$this->minute = Time::parse($this->duration)->minute;
	}*/

    /**
	 * Get bookings from activity
	 *
	 * @return \App\Booking
	 */
	public function bookings()
	{
		return $this->hasMany(SalonBooking::class);
	}
	   public function salon(){
        return $this->belongsTo('App\Models\Salon');
    }

    public function service(){
        return $this->belongsTo('App\Models\Service');
    }
	
	
	
}
