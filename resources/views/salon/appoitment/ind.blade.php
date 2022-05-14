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

  <!-- Modal 1-->
  <div class="modal fade" id="bookingModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">New message</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
        <div class="form-group" >
        <label for="service" class="col-form-label"> Service Type  </label>
        <select class="form-control" id="service"  required="" name="service">
            <option value=""> Please select services</option>
            @foreach($services as $s)
                <option value="{{ $s->service_id }}" >{{ $s->name}}</option>
            @endforeach
         </select>
        </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Recipient:</label>
            <input type="text" class="form-control" id="recipient-name">
          </div>
          <div class="form-group">
            <label for="message-text" class="col-form-label">Message:</label>
            <textarea class="form-control" id="message-text"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Send message</button>
      </div>
    </div>
  </div>
</div>





<div class="container-fluid" style="min-height: 1239px;">
<form class="request" method="POST" action="/admin/calendar">

<div class="form-group header-group-0 " id="form-group-active" style="">
    <label class="control-label col-sm-2">Employee
                    <span class="text-danger" title="This field is required">*</span>
            </label>

    <div class="col-sm-5">
        <select class="form-control" id="staff" data-value="1" required="" name="active">
            <option value="">** Please select staff</option>
            @foreach($staffs as $e)
                <option value="{{ $e->id }}" >{{ $e->title . ' - ' . $e->name }}</option>
            @endforeach
         </select>
        <div class="text-danger"></div>
        <p class="help-block"></p>
      </div>
      </div>

     </form>

  
        <div class="row">
            <div class="col-12">
                <h3 class="text-center mt-5">FullCalendar Development Lab</h3>
                <div class="col-md-11 offset-1 mt-5 mb-5">

                    <div id="calendar">

                    </div>

                </div>
            </div>
        </div>


    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>



<script>
jQuery(document).ready(function() {



var booking = @json($events);

$('#calendar').fullCalendar({
    header: {
        left: 'prev, next today',
        center: 'title',
        right: 'month, agendaWeek, agendaDay',
    },
    events: booking,
    selectable: true,
    selectHelper: true,
    select: function(start, end, allDays) {
        $('#bookingModal').modal('toggle');

        $('#saveBtn').click(function() {
            var title = $('#client').val();
            var start_date = moment(start).format('YYYY-MM-DD');
            var end_date = moment(end).format('YYYY-MM-DD');

            $.ajax({
                url:"{{ route('calendar.store') }}",
                type:"POST",
                dataType:'json',
                data:{ staff, start, end  },
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
    editable: true,
    eventDrop: function(event) {
        var id = event.id;
        var start_date = moment(event.start).format('YYYY-MM-DD');
        var end_date = moment(event.end).format('YYYY-MM-DD');

        $.ajax({
                url:"{{ route('calendar.update', '') }}" +'/'+ id,
                type:"PATCH",
                dataType:'json',
                data:{ start_date, end_date  },
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
    selectAllow: function(event)
    {
        return moment(event.start).utcOffset(false).isSame(moment(event.end).subtract(1, 'second').utcOffset(false), 'day');
    },



});



</script>
<style>
#calendar {
  max-width: 800px;
  margin: 0 auto;
}
</style>

</body>
</html>