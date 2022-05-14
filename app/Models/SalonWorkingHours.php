<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon as Time;
use CRUDBooster;

class SalonWorkingHours extends Model
{
    use HasFactory;
    protected $table = 'salon_working_hours' ;
    protected $guarded = [];

    public static function getRoster()
    {
    	// Start of week in the month
        $timezone = date_default_timezone_get();
        $user_id=CRUDBooster::myId();
        $salon=DB::table('salons')->where('user_id', $user_id);

    	$startDate = Time::now($timezone)
    		->addMonth()
    		->startOfMonth()
    		->startOfWeek()
    		// Subtract a day to capture the first day of week
    		->subDay();
    	// End of week in the month
    	$endDate = Time::now($timezone)
    		->addMonth()
    		->endOfMonth()
    		->endOfWeek()
    		// Add a day to capture last day of week
    		->addDay();

    	return SalonWorkingTime::Where('salon_id',$salon->id)->whereBetween('date', [$startDate, $endDate])
    		// Get eloquent model
    		->get()
            ->sortBy('end_time')
    		->sortBy('start_time');
    }

    /**
     * Get the working times of an employee for a given amount of days
     *
     * @return WorkingTime
     */
    public static function getWorkingTmesForEmployee($employeeID, $days)
    {
        $user_id=CRUDBooster::myId();
        $salon=DB::table('salons')->where('user_id', $user_id);

        //Get all working times for a particular employee
        $workingTimes = SalonWorkingTime::where('salon_id',$salon->id)->where('staff_id', $employeeID);

        //Get working times from today onwards
       
        $workingTimes = $workingTimes->where('date', '>=', Time::now(date_default_timezone_get())->toDateString());

        //Final day of working times
        $max = Time::now(date_default_timezone_get())->addDays($days);

        //Restrict working times to amount of days
        $WorkingTimes = $workingTimes->where('date', '<', $max);

        //Return the working times for the employee
        return $workingTimes;
    }

    /**
	 * Get employee from working time
     *
	 * @return Employee
	 */
	public function staffs()
	{
		return $this->belongsTo(Staffs::class);
	}

    /**
     * Removes all future bookings.
     *
     * @return void
     */
    public function deleteBookings()
    {
        // Count the amount of bookings removed
        $bookingCount = 0;

        // Delete remaining booking after today on a day of week
        foreach (Booking::where('date', $this->date)->where('staff_id', $this->employee_id)->get() as $booking) {
            $booking->delete();
            $bookingCount++;
        }

        Log::notice("Deleted " . $bookingCount . " previous booking(s)");
    }


}
