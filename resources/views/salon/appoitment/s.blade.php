<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Full Calendar js</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body>  
<style>
  #calendar {
    max-width: 700px;
    margin: 0 auto;
  }
</style>
  <!-- Modal -->
  <div class="modal fade"  id="bookingModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"  >

    
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Booking title</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        <form>
         <div class="row ">
              <div class="col-7 ">
                  <div class="form-group" >
                        <label for="client" class="col-form-label"> Client Nmae  </label>
                        <select class="form-control" id="client"  required="" name="clients">
                            <option value=""> Please select client</option>
                            @foreach($clients as $c)
                                <option value="{{ $c->id }}" >{{ $c->first_name." ".$c->last_name}}</option>
                            @endforeach
                       </select>
                  </div>
             </div>
                <div class="col ">
                  <label for="date" class="col-form-label"> Date  </label>        
                    <input type="text"  id="date" class="form-control" placeholder="Date">
                  </div>
                    
         </div>
         <div class="row ">
              <div class="col-7 ">
                  <div class="form-group" >
                        <label for="client" class="col-form-label"> Service </label>
                        <select class="form-control" id="service"  required="" name="clients">
                            <option value=""> Please select service</option>
                            @foreach($services as $s)
                                <option value="{{ $s->service_id }}" >{{ $s->name}}</option>
                            @endforeach
                       </select>
                  </div>
             </div>
             <div class="col-7 ">
                  <div class="form-group" >
                        <label for="client" class="col-form-label"> staff name  </label>
                        <select class="form-control" id="staff"  required="" name="staff">
                        <option value="0">- Select -</option>
                           
                       </select>
                  </div>
             </div>
             <div class="col-7 ">
                  <div class="form-group" >
                        <label for="itme-slote" class="col-form-label"> Select Time  </label>
                        <select class="form-control" id="time-slote"  required="" name="time-slote">
                        <option value="0">- Select -</option>
                           
                       </select>
                      
                  </div>
             </div>
               
                    
         </div>
                
       </form>

        </div>
      
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" id="saveBtn" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>

 
 
  <div id='calendar'></div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
   
<script>
 
      $(document).ready(function() {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $('#time-slote').on('change', function(e){ 

                        var db = $(this).val().slice(5);
                        
                    });

        $('#service').on('change', function(e){ 
            var service_id = $(this).val();
            console.log(service_id);
            $.ajax({
                  url:"{{ route('getStaffbyservice') }}",
                  type:"POST",
                  dataType:'json',
                  data:{ service_id},
                 
                  success:function(response)
                  {
                    var len = response.length;

                    $("#staff").empty();
                    for( var i = 0; i<len; i++){
                    var mm = response[i]['id'];
                    var name = response[i]['title'];
                        
                    $("#staff").append("<option value='"+mm+"'>"+name+"</option>");


                        }
                        $("#staff").on("change", changeCallback);
                          }
                  });
            
        });
        
       
        $('#staff').on('change', function(e){ 
         
            var staff = $(this).val();
            var date =$('#date').val();
            var service=$('#service').val();
            console.log(staff);
          
            $.ajax({
                  url:"{{ route('getStaffTimeSlote') }}",
                  type:"POST",
                  dataType:'json',
                  data:{staff,date,service},
                 
                  success:function(response)
                  {
                    console.log(response);
                        
                    var len = response.length;

                        $("#time-slote").empty();
                        for( var i = 0; i<len; i++){
                    var start = response[i]['start'];
                    
                $("#time-slote").append("<option value='"+start+"'>"+start+"     </option>");

                                              }
                           calendar.render();


                          }
                  });
        });
       
   var booking = @json($events);
   $('#calendar').fullCalendar({
                header: {
                    left: 'prev, next today',
                    center: 'title',
                    right: 'month, agendaWeek, agendaDay',
                },
                editable: true,
                events: booking,
                selectable: true,
                selectHelper: true,
                
                height: 'auto',
                    navLinks: true, // can click day/week names to navigate views
                   
                    selectable: true,
                    selectMirror: true,
                    nowIndicator: true,

                select: function(start, end, allDays) {
                    $('#date').val(moment(start).format('YYYY-MM-DD'));
                    $('#bookingModal').modal('toggle');
                    $('#saveBtn').click(function() {
                        var date =$('#date').val();
                        var service=$('#service').val();
                        var client = $('#client').val();
                        var staff = $('#staff').val();
                        var start = $('#time-slote').val();
                      
                        $.ajax({
                            url:"{{ route('calendar.store') }}",
                            type:"POST",
                            dataType:'json',
                            data:{ date,service,client,staff,start},
                            success:function(response)
                            {
                                console.log(response);
                            
                                $('#bookingModal').modal('hide')
                                $('#calendar').fullCalendar('renderEvent', {
                                    'title': response.title,
                                    'start' : response.start,
                                    'end'  : response.end,
                                    'color' : response.color
                                });
                                calendar.render();
                            },
                            error:function(error)
                            {
                                if(error.responseJSON.errors) {
                                    $('#titleError').html(error.responseJSON.errors.title);
                                }
                            },
                        });
                    });
                },
                
                eventDrop: function(event) {
                    var id = event.id;
                    var start = moment(event.start).format('YYYY-MM-DD');
                    var end = moment(event.end).format('YYYY-MM-DD');
                    $.ajax({
                            url:"{{ route('calendar.update','') }}" +'/'+ id,
                            type:"PATCH",
                            dataType:'json',
                            data:{ start, end },
                            success:function(response)
                            {
                                swal("Good job!", "Event Updated!", "success");
                            },
                            error:function(error)
                            {
                                console.log(error)
                            },
                        });
                },
                eventClick: function(event){
                    var id = event.id;
                    if(confirm('Are you sure want to remove it')){
                        $.ajax({
                            url:"{{ route('calendar.destroy', '') }}" +'/'+ id,
                            type:"DELETE",
                            dataType:'json',
                            success:function(response)
                            {
                                $('#calendar').fullCalendar('removeEvents', response);
                                // swal("Good job!", "Event Deleted!", "success");
                            },
                            error:function(error)
                            {
                                console.log(error)
                            },
                        });
                    }
                },
                
            });
            $("#bookingModal").on("hidden.bs.modal", function () {
                $('#saveBtn').unbind();
            });
            $('.fc-event').css('font-size', '13px');
            $('.fc-event').css('width', '20px');
            $('.fc-event').css('border-radius', '50%');
        });
    </script>
</body>
</html>