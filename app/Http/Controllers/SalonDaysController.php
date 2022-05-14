<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use DB; 
use DateTime;
use App\Models\SalonBooking;
use App\Models\Salon;
use App\Models\SalonDays;
use App\Models\SalonCustomer;
use App\Models\Staffs;
use App\Models\SalonWorkingTime;
use Carbon\Carbon as Time;
use CRUDBooster;
class SalonDaysController extends Controller
{

public $salon;
public $user_id;
    public function __construct() {
        // Business Owner auth
        $user_id=CRUDBooster::myId();
        $salon=DB::table('salons')->where('user_id', $user_id);

        // Validation error messages
        $this->messages = [
            'day.is_day_of_week' => 'The :attribute field must be a valid day (e.g. Monday, Tuesday).',
            'start_time.date_format' => 'The :attribute field must be in the correct 24-hour time format.',
            'end_time.date_format' => 'The :attribute field must be in the correct 24-hour time format.',
            'start_time.before' => 'The :attribute must be before the end time.',
            'end_time.after' => 'The :attribute must be after the start time.'
        ];

        // Validation rules
        $this->rules = [
            'day' => 'required|unique:business_times,day|is_day_of_week',
            'start_time' => 'required|date_format:H:i|before:end_time',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ];

        // Attributes replace the field name with a more readable name
        $this->attributes = [
            'start_time' => 'start time',
            'end_time' => 'end time',
        ];
    }





    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id=CRUDBooster::myId();
        $salon=DB::table('salons')->where('user_id', $user_id);
      
       
$days=Time::getDays();
        return view('admin.times', [
            'business' => $salon,
            'days'=>$days,
            'bTimes' => SalonDays::where('salon_id',$salon->id)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::info("An attempt to create a business time from the Business Owner Dashboard", $request->all());
        Log::debug("Validating Business Owner input");

        // Validate form
        $this->validate($request, $this->rules, $this->messages, $this->attributes);

        // Convert start time to proper time format
        $request->merge([
            'start_time' => toTime($request->start_time),
            'end_time' => toTime($request->end_time)
        ]);

        // Create business time
        $bTime = SalonDays::create([
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'day' => $request->day,
        ]);

        Log::notice("Business time was created by Business Owner ID " . $user_id, $bTime->toArray());

        // Session flash
        session()->flash('message', 'Business time has successfully been created.');

        return redirect('admin/times');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(SalonDays $time)
    {
        $bTime = $time;
        $business = $salon;

        return view('admin.edit.business_time', compact(['bTime', 'salon']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SalonDays $time)
    {
        unset($this->rules['day']);

        Log::debug("Validating Activity input");

        // Validate form
        $this->validate($request, $this->rules, $this->messages, $this->attributes);

        $bTime = SalonDays::find($time->id);

        // Set variables once validated
        $bTime->start_time = $request->start_time;
        $bTime->end_time = $request->end_time;

        // Save activity
        $bTime->save();

        Log::notice("Business time ID " . $bTime->id . " was updated", $bTime->toArray());

        // Delete future working times and bookings
        $time->deleteAllFutureWorkingTimes();
        $time->deleteAllFutureBookings();

        // Session flash
        session()->flash('message', 'Business time successfully edited.');

        // Redirect to activity page
        return redirect('/admin/times');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(SalonDays $time)
    {
        // Remove selected business time
        $time->delete();

        // Delete future working times and bookings
        $time->deleteAllFutureWorkingTimes();
        $time->deleteAllFutureBookings();

        // Session flash
        session()->flash('message', 'Business time successfully removed.');

        // Redirect to activity page
        return redirect('/admin/times');
    }
}