<?php

namespace App\Http\Controllers;
use  App\Models\SalonBooking;
use  App\Models\SalonService;
use  App\Models\Staffs;
use Illuminate\Http\Request;
use DB;
use DateTime;
use Carbon\Carbon ;
use Calendar;
use CRUDBooster;

class CalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index11(Request $request)
    {
        $salon=DB::table('salons')->where('user_id',CRUDBooster::myId())->first();
     
    	if($request->ajax())
    	{
    		$data = SalonBooking::where('salon_id','=',$salon->id)
                       ->whereDate('start', '>=', $request->start)
                       ->whereDate('end',   '<=', $request->end)
                       ->get(['id', 'service_id','client_id' ,'start', 'end','staff_id']);
            return response()->json($data);
    	}
    	return view('salon.appoitment.index');
    }
   
    public function action(Request $request)
    {
    	if($request->ajax())
    	{
    		if($request->type == 'add')
    		{
                $booking = SalonBooking::create(
                    ['staff_id' => 3,
                    'salon_id' => $salon->id,
                    'client_id' =>2,
                    'service_id' => 3,
                    'start' => $request->start,
                    'end' => $request->end
                ]);
    			

    			return response()->json($booking);
    		}

    		if($request->type == 'update')
    		{
    			$booking = SalonBooking::find($request->id)->update([
                    'staff_id' => 3,
                    'client_id' => 2,
                    'service_id' => 3,
                    'start' => $request->start,
                    'end' => $request->end
    			]);

    			return response()->json($booking);
    		}

    		if($request->type == 'delete')
    		{
    			$booking = SalonBooking::find($request->id)->delete();

    			return response()->json($booking);
    		}
    	}
    }

    public function index()
    {

     $salon=DB::table('salons')->where('user_id',CRUDBooster::myId())->first();
     
     $options=DB::table('salon_options')->where('salon_id','=', $salon->id)->where('name','=','abbr')->first();  
      $staffs=DB::table('staffs')->where('salon_id',$salon->id)->orderby('id','desc')->paginate(10);
      $clients=DB::table('salon_clients')->where('salon_id',$salon->id)->orderby('id','desc')->paginate(10);
      $booking=DB::select(DB::raw("SELECT id, start, color, end ,date  FROM salon_bookings where  salon_id=".$salon->id));
     $events=array();


foreach($booking as $b){
 $client=DB::table('salon_clients')->where('id','=', $b->client_id)->first();
$event['title']=$client->first_name." ".$client->last_name;    
$event['id']=$b->id;
$event['start']=$b->date." ".$b->start;
$event['end']=$b->date." ".$b->end;
$event['color']=$b->color;
array_push($events,$event);
}
 $services=DB::select(DB::raw("select ss.id,ss.service_id,ss.main_price,ss.duration,st.name as name from salon_service ss , service_trans st,services s where ss.active=1 and st.service_id=s.id and s.id=ss.service_id and ss.salon_id =".$salon->id." and st.languagies_id=".$options->value));
  return view('salon.appoitment.s',
         ['events' =>$events,
        'services'=>$services,
        'clients'=>$clients,
        'staffs'=>$staffs]
             );
    }
     
    public function test( )
    {     
      $f['start_time'] ='11:15:00';
      $f['end_time']='19:45:00';
    
     $start=$f['start_time'] ;
     $end=$f['end_time'];
     $service=DB::table('salon_service')->where('service_id','=',7)->first();
     $d =$service->duration;
     $k=array();
        while(strtotime($start) < strtotime($end)){
            array_push($k,$start);
            $start=date('H:i:s',strtotime($start)+strtotime($d));

        }
    return $k;
    $m=substr($service->duration,3,2);
    $minits= intval($h )*60+intval($m );
   // let minutes = d.getMinutes();

return $minits;
    return strtoTime(substr($service->duration,0,5))->getMinutes(); 

    }
    public function getStaffTimeSlote( Request $request)
    {     
        $request->validate([
            'service' => 'required',
            'date' => 'required',
            'staff' => 'required',
        ]);
        $date = $request->date;

       $day_num = date('N', strtotime($date));
       $staff_id=$request->staff;
       $service=$request->service;
       $workingTime = DB::table('salon_working_hours')->where('date','=',$day_num)->where('staff_id','=',$staff_id)->orderby('id','desc')->paginate(1000);
       $bookings = DB::table('salon_bookings')->where('date','=',$date)->where('staff_id','=',$staff_id)->orderby('start','asc')->paginate(10);
      // return $bookings;
       $service=DB::table('salon_service')->where('service_id','=',$request->service)->first();
       $d=$service->duration;
       // return sizeof($bookings);
     // $n=sizeof($avaTimes)
      
        if (!$workingTime  ) {
            return null;
        }
        if(sizeof($bookings) == 0){
           
          //  $avaTimes=$workingTime;
           $start=$workingTime[0]->start_time;
            $end= $workingTime[0]->finish_time;
            $l=0;
          $k=array();
                    while(strtotime($start) < strtotime($end)){
                        //array_push($k,$start);
                        $k[$l]['start']=$start;
                        $l++;
                        $start=date('H:i:s',strtotime($start)+strtotime($d));
                    }
                        return $k;
            
            }
        else{

        $i = 0;
           // $start_time=$workingTime[0]->start_time;
          //  $finish_time= $workingTime[0]->finish_time;
        // Set available time to working time
        $avaTimes[$i]['start_time'] = $workingTime[0]->start_time;
        $avaTimes[$i]['end_time'] = $workingTime[0]->finish_time;

        foreach ($bookings as $booking) {
            if (strtotime($avaTimes[$i]['end_time']) != strtotime($avaTimes[$i]['start_time'])) {
                // Switch times
                $avaTimes[$i]['end_time'] = $booking->start;
                // If avail and booking start time are the same, go back
                if (strtotime($avaTimes[$i]['start_time']) == strtotime($booking->start)) {
                    unset($avaTimes[$i]);
                    //array_pop($avaTimes);
                }
               /* if (strtotime($avaTimes[$i]['start_time']) > strtotime($avaTimes[$i]['end_time'])) {
                    unset($avaTimes[$i]);
                    //array_pop($avaTimes);
                }*/
                // IF booking and working end time is the same, go back
                if ( strtotime($booking->end_time) != strtotime($workingTime[0]->finish_time)) {
                    $i++;
                    // Switch times
                    $avaTimes[$i]['start_time'] = $booking->end;
                    $avaTimes[$i]['end_time'] = $workingTime[0]->finish_time;
                }
            }
        }

     
        //return $avaTimes;
        }
    $n=sizeof($avaTimes)+1;
  
    $l=0;
    $j=1;
   $k=array();
    while( $j<$n){
                    
                    $start=$avaTimes[$j]['start_time'];
                    $end=$avaTimes[$j]['end_time'];
                
                    while(strtotime($start) < strtotime($end)){
                        //array_push($k,$start);
                        $k[$l]['start']=$start;
                        $l++;
                        $start=date('H:i:s',strtotime($start)+strtotime($d));
                            }
                            $j++;
                        }
            
   
                        return $k;
    




    }
    public function getStaffFreeTimes( $workingTime,$bookings,$duration)
    { 
    


       } 

    public function index1( )
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
      $data['booking']=$booking;
     
 return $staffs;
    return view('salon.appoitment.ind',  ['events' => $booking,

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
    {//date,service,client,staff,start

      
       $request->validate([
            'date' => 'required',
            'service' => 'required',
            'client' => 'required',
            'staff' => 'required',
            
            'start' => 'required'

        ]);
    
        $start=$request->start;
      
        $salon=DB::table('salons')->where('user_id',CRUDBooster::myId())->first();
       $service=DB::table('salon_service')->where('service_id','=',$request->service)->first();
      $end=date('H:i:s',strtotime($start)+strtotime($service->duration));
       $booking = SalonBooking::create([
            'staff_id' => $request->staff,
            'salon_id' => $salon->id,
            'client_id' => $request->client,
            'service_id' => $request->service,
            'date' => $request->date,
            'color' => '#fd7e00',
            'start' => $start,
            'end' =>$end
        ]);
        $client=DB::table('salon_clients')->where('id','=', $booking->client_id)->first();
       
        return response()->json([
            'id' => $booking->id,
            'start' => $booking->date." ".$booking->start,
            'end' => $booking->date." ".$booking->end,
            'title' =>$client->first_name." ".$client->last_name,
            'color' => $booking->color

        ]);

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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $booking = SalonBooking::findOrFail($id);
        if(! $booking) {
            return response()->json([
                'error' => 'Unable to locate the event'
            ], 404);
        }
       
        $booking->update([
          //  'staff_id' => $request->staff,
    
           // 'client_id' => $request->client,
          //  'service_id' => $request->service,
            'start' => $request->start,
           // 'date' => $request->date,
            'end' => $request->end

          
        ]);
        return response()->json('Event updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $booking = SalonBooking::find($id);
        if(! $booking) {
            return response()->json([
                'error' => 'Unable to locate the event'
            ], 404);
        }
        $booking->delete();
        return $id;
    }
    public function getStaffbyservice(Request $request)
    { $salon=DB::table('salons')->where('user_id',CRUDBooster::myId())->first();
       
        $request->validate([
            'service_id' => 'required',
        ]);
       $id=$request->service_id;
       //
       $result=DB::select(DB::raw("SELECT sst.staff_id as staff_id , s.title  FROM `salon_service_stuff` sst ,staffs s  WHERE  sst.salon_id=".$salon->id." and s.id=sst.staff_id and sst.service_id=".$id));
      $response=array();
    
      foreach($result as $r){
          $res['id']=$r->staff_id;
          $res['title']=$r->title;
          array_push($response,$res);

      }
        
        return $response;

}
}