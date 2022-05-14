<?php

namespace App\Http\Controllers;
use Session;
	use Request;
	use DB;
	use CRUDBooster;
    use Carbon\Carbon ;
    use Calendar;

class SalonHomeController extends Controller
{
    function saveSetting(Request $request)
    {
        
       // Excel::import(new UsersImport, request()->file('import_file'));
        
      //  return redirect('import_excel')->with('success', 'تم تحميل الملف بنجاح');
    }//
    function salonSettingShow()
    {
        return view('salon.setting');
    }
    public function calendarShow( $staffID=null)
    {
      	//$data['result'] = DB::table('products')->orderby('id','desc')->paginate(10);
		//Create a view. Please use `view` method instead of view method from laravel.
        $salon=DB::table('salons')->where('user_id',CRUDBooster::myId())->first();
        $staffs=DB::table('staffs')->where('salon_id',$salon->id)->orderby('id','desc')->paginate(10);
        $clients=DB::table('salon_clients')->where('salon_id',$salon->id)->orderby('id','desc')->paginate(10);
        
        $services=DB::table('salon_service')->where('salon_id',$salon->id)->orderby('id','desc')->paginate(10);
        $booking=DB::table('salon_bookings')
        ->where('salon_id',$salon->id)
        ->where('staff_id',5)
        ->get()
        ->sortBy('date');
        $data['staffs']=$staffs;
        $data['services']=$services;
        $data['clients']=$clients;
        
        $events=array();
        foreach ($booking as $booking){
       $events[]= Calendar::event(
        "fff",
               true,
           $booking->start_date,
           $booking->end_date
       );
    }
   
    
     $calendar = Calendar::addEvents($events); 
   
      return view('salon.appoitment.index', compact('calendar'));
    }

//"start_time":"21:15:45","end_time":"21:15:45","date":"2022-04-28",
    public function calendadrShow( $employeeID = null)
    {
       
        $bookings = Booking::where('date', '<=', $date->endOfMonth()->toDateString())
            ->where('date', '>=', $date->startOfMonth()->toDateString())
            ->get()
            ->sortBy('date');

        // Find employee
        $employee = Employee::find($employeeID);

        if ($employeeID) {
            // Find working time by employee ID
            $workingTimes = WorkingTime::where('employee_id', $employeeID);
        }
        else {
            // Else get all working times
            $workingTimes = WorkingTime::all();
        }

        $workingTimes = $workingTimes->where('date', '<=', $date->endOfMonth()->toDateString())
            ->where('date', '>=', $date->startOfMonth()->toDateString())
            ->get();

        return view('admin.bookings', [
            'bookings'      => $bookings,
            'business'      => BusinessOwner::first(),
            'employeeID'    => $employeeID,
            'employee'      => $employee,
            'roster'        => $workingTimes,
            'date'          => $date,
            'dateString'    => $date->format('m-Y'),
            'months'        => $monthList
        ]);
    }


}



