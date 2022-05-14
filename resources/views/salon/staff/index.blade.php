<!-- First you need to extend the CB layout -->
@extends('crudbooster::admin_template')
@section('content')
<!-- Your custom  HTML goes here -->

<div class="card" style="width: 18rem">
  <img class="card-img-top" src='{{asset("img/card1.png")}}' >
  <div class="card-body">
    <h5 class="card-title">{{count ($result)}}</h5>
   </div>
 
  <div class="card-body">
  <h1> {{count ($result)}} </h1>
  </div>
</div>

<table class='table table-striped table-bordered'>
  <thead>
      <tr>
        <th>Name</th>
        <th>title</th>
        <th>email</th>
        <th>image</th>
        <th>phone</th>
       </tr>
  </thead>
  <tbody>
    @foreach($result as $row)
      <tr>
        <td>{{$row->name}}</td>
        <td>{{$row->title}}</td>
        <td>{{$row->email}}</td>
        <td>{{$row->image}}</td>
        <td>{{$row->phone}}</td>
        <td>
          <!-- To make sure we have read access, wee need to validate the privilege -->
          @if(CRUDBooster::isUpdate() && $button_edit)
          <a class='btn btn-success btn-sm' href='{{CRUDBooster::mainpath("edit/$row->id")}}'>Edit</a>
          @endif
          
          @if(CRUDBooster::isDelete() && $button_edit)
          <a class='btn btn-success btn-sm' href='{{CRUDBooster::mainpath("delete/$row->id")}}'>Delete</a>
          @endif
        </td>
       </tr>
    @endforeach
  </tbody>
</table>

<!-- ADD A PAGINATION -->
<p>{!! urldecode(str_replace("/?","?",$result->appends(Request::all())->render())) !!}</p>
@endsection