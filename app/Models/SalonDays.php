<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon as Time;
use CRUDBooster;
use App\Model\SalonBooking;
use App\Model\SalonWorkingHours;//WorkingTime

class SalonDays extends Model// BusinessTime
{
    use HasFactory;
    protected $table = 'salon_days' ;
    protected $guarded = [];
    public $timestamps = false;

	 
   
    public function deleteAllFutureWorkingTimes()
    {
        // Count the amount of working times removed
        $user_id=CRUDBooster::myId();
        $salon=DB::table('salons')->where('user_id', $user_id);
        $wTimeCount = 0;

        // Delete remaining working times after today on a day of week
        foreach (SalonWorkingHours::Where('salon_id',$salon->id)->where('date', '>=', getDateNow())->get() as $wTime) {
            if (strtoupper(Time::parse($wTime->date)->format('l')) == $this->day) {
                $wTime->delete();
                $wTimeCount++;
            }
        }

        Log::notice("Deleted " . $wTimeCount . " previous working time(s)");
    }

    /**
     * Removes all future bookings.
     *
     * @return void
     */
    public function deleteAllFutureBookings()
    {
        // Count the amount of bookings removed
        $bookingCount = 0;

        // Delete remaining booking after today on a day of week
        foreach (SalonBooking::where('date', '>=', getDateNow())->get() as $booking) {
            if (strtoupper(Time::parse($booking->date)->format('l')) == $this->day) {
                $booking->delete();
                $bookingCount++;
            }
        }

        Log::notice("Deleted " . $bookingCount . " previous booking(s)");
    }
}





   

