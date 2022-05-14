<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon as Time;
use CRUDBooster;
use DB;
class Staffs extends Model
{
    use HasFactory;
    protected $table = 'staffs' ;
    protected $guarded = [];
     protected $salon;
	/**
	 * Get the available times of an employee at a given date
	 *
	 * @param  string $date
	 * @return Array
	 */
    public function availableNuwarTimes($date) {
        $day_num = date('N', strtotime($date));
        $res=array();
		// Get working time
		$workingTime = $this->workingTimes->where('date', $day_num)->first();
        $bookings = $this->bookings->where('date', $date)->sortBy('start');
    $flag=1;
    $start=$workingTime->start_time;
    $finish=$workingTime->finish_time;
    
    do {  
    foreach ($bookings as $b) {
if($b->start  == $start or $start < $b->start  ){
    $start=$b->end;
    $flag=0;
}
else $flag=1;
    }
    if($flag)
    array_push($res,$start);
   
    $start=$start+date('H:i:s', '00:30:00');
      } while ($start <= $finish);




    
    
    
    }

	public function availableTimes($date) {
        $day_num = date('N', strtotime($date));
  
		// Get working time
		$workingTime = $this->workingTimes->where('date', $day_num)->first();

        // Get employee bookings
        $bookings = $this->bookings->where('date', $date)->sortBy('start');

        if (!$workingTime || !$bookings) {
            return null;
        }

        // Index the available time array
        $i = 0;

        // Set available time to working time
        $avaTimes[$i]['start_time'] = $workingTime->start_time;
        $avaTimes[$i]['end_time'] = $workingTime->finish_time;

        foreach ($bookings as $booking) {
            if ($avaTimes[$i]['end_time'] != $avaTimes[$i]['start_time']) {
                // Switch times
                $avaTimes[$i]['end_time'] = $booking->start;
                // If avail and booking start time are the same, go back
                if ($avaTimes[$i]['start_time'] == $booking->start) {
                    array_pop($avaTimes);
                }
                // IF booking and working end time is the same, go back
                if ($booking->end_time != $workingTime->finish_time) {
                    $i++;
                    // Switch times
                    $avaTimes[$i]['start_time'] = $booking->end;
                    $avaTimes[$i]['end_time'] = $workingTime->finish_time;
                }
            }
        }

        return $avaTimes;
	}

	/**
	 * Get working times of an employee
	 *
	 * @return Relationship
	 */
	public function workingTimes()
	{
		return $this->hasMany(SalonWorkingHours::class);
	}

	/**
	 * Get bookings of an employee
	 *
	 * @return Relationship
	 */
	public function bookings()
	{
		return $this->hasMany(SalonBooking::class);
	}

}
