 
 $(document).ready(function() {
     $.ajaxSetup({
           headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           }
       });
   
   
            $('#service').change(function() {
       
       var id = $(this).val();
       console.log(id);
       $.ajax({
               url: "/get-staffs/", 
               method: "POST",  
               data:{id:id},  
               success:function(data){
                 console.log(response);
              }  
   
          });  
   
   });
   
      // var booking = @json($events);
       var calendar = $('#calendar').fullCalendar({
      editable:true,
      header:{
       left:'prev,next today',
       center:'title',
       right:'month,agendaWeek,agendaDay'
      },
      events: booking,
      selectable:true,
      selectHelper:true,
      select: function(start, end, allDays) {
                       $('#bookingModal').modal('toggle');
                       $('#saveBtn').click(function() {
                         //  var title = $('#title').val();
                           var staff=11;
                           var client =3;
                           var service =3;
                           var start_date = moment(start).format('YYYY-MM-DD');
                           var end_date = moment(end).format('YYYY-MM-DD');
                           $.ajax({
                               url:"{{ route('calendar.stores') }}",
                               type:"POST",
                               dataType:'json',
                               data:{ client,staff, start_date, end_date,service, _token: '{{csrf_token()}}'  },
   
                               success:function(response)
                               {
                                   $('#bookingModal').modal('hide')
                                   $('#calendar').fullCalendar('renderEvent', {
                                       'title': response.title,
                                       'start' : response.start,
                                       'end'  : response.end,
                                       'color' : response.color
                                   });
   
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
      editable:true,
      eventResize:function(event)
      {
       var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
       var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
       var title = event.title;
       var id = event.id;
       $.ajax({
        url:"update.php",
        type:"POST",
        data:{title:title, start:start, end:end, id:id},
        success:function(){
         calendar.fullCalendar('refetchEvents');
         alert('Event Update');
        }
       })
      },
   
      eventDrop:function(event)
      {
       var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
       var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
       var title = event.title;
       var id = event.id;
       $.ajax({
        url:"update.php",
        type:"POST",
        data:{title:title, start:start, end:end, id:id},
        success:function()
        {
         calendar.fullCalendar('refetchEvents');
         alert("Event Updated");
        }
       });
      },
   
      eventClick:function(event)
      {
       if(confirm("Are you sure you want to remove it?"))
       {
        var id = event.id;
        $.ajax({
         url:"delete.php",
         type:"POST",
         data:{id:id},
         success:function()
         {
          calendar.fullCalendar('refetchEvents');
          alert("Event Removed");
         }
        })
       }
      },
   
     });
    });
     
   