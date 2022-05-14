<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use CRUDBooster;
use DB;
use App\Model\SalonService;
use App\Model\SalonWorkingHours;
use Carbon\Carbon as Time;


class SalonBooking extends Model
{

   
   protected $salon;
    use HasFactory;
    protected $guarded = [];


	public static function getWorkableBookingsForEmployee($employeeID, $ndays)
	{
		// Get all bookings from the next 30 days
		$bookings = SalonBooking::allLatest($ndays)->where('salon_id',$salon->id);

		// Get all working times for the employee for next 30 days
		$workingTimes = SalonWorkingHour::getWorkingTmesForEmployee($employeeID, $ndays)->get();

		// Final bookings
		$finalBookings = [];

		// Iterate through each booking
		foreach ($bookings as $booking) {
			// Iterate through each working time
			foreach ($workingTimes as $workingTime) {
				// If the employee is working during the entirety of this booking
				// And the booking is on the same day the employee is working
				if ($workingTime->start_time <= $booking->start_time &&
					$workingTime->end_time >= $booking->end_time &&
					$workingTime->date == $booking->date) {

					// Push booking to list of final bookings
					array_push($finalBookings, $booking);
				}
			}
		}

		// Return bookings
		return $finalBookings;
	}

	/**
	 * Get employee from bookings
	 *
	 * @return \App\Employee
	 */
	public function staff()
	{
		return $this->belongsTo(Staffs::class);
	}

	/**
	 * Get customer from bookings
	 *
	 * @return \App\Customer
	 */
	public function clients()
	{
		return $this->belongsTo(Client::class);
	}

	/**
	 * Get activity from bookings
	 *
	 * @return \App\Activity
	 */
	public function service()
	{
		return $this->belongsTo(SalonService::class);
	}
	
}
