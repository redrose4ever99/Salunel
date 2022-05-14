@extends('crudbooster::admin_template')

@section('content')

  <br />
  <style type="text/css"> 

.table td {
text-align: center;
font-size: 16;
} 
.table th {
text-align: center;
} 



  </style>
  
  <div class="container">
   <h3 align="center">اعدادات عامة</h3>
    <br />
   @if(count($errors) > 0)
    <div class="alert alert-danger">
     خطأ <br><br>
     <ul>
      @foreach($errors->all() as $error)
      <li>{{ $error }}</li>
      @endforeach
     </ul>
    </div>
   @endif

   @if($message = Session::get('success'))
   <div class="alert alert-success alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>
           <strong>{{ $message }}</strong>
   </div>
   @endif
   <div class="card-body">   
        </div>
    {{ csrf_field() }}
    <br /> 
   <br />
   <div class="panel panel-default">
    <div class="panel-heading">
    <h3  style="align-items: center;"    class="panel-title">عمليات</h3>
   </div>
    <div class="panel-body">
     <div class="table-responsive">
     </div>
    </div>
   </div>
  </div>
  @endsection

