@extends('layouts.app')
@section('content')
<!-- Header Section start here -->
<div class="app-main__outer">
<div class="app-main__inner">
<div class="app-page-title">
   <div class="page-title-wrapper">
      <div class="page-title-heading">
         Age Group
         <div class="page-title-subheading"> </div>
      </div>
   </div>
</div>
<!-- Content Section start here -->
<div class="row">
<div class="container">
   <div class="row justify-content-center">
      <div class="col-md-12">
         <div class="card">
            <div class="card-header display-inline mt-3">
               {{ __('Add Group') }}
               <!-- <a href="{{ route('domain.create') }}"  class="float-right mb-2 mr-2 btn-transition btn btn-outline-primary">Primary
                  </a> -->

               <button type="button" class=" float-right btn mr-2 mb-2 btn-primary" data-toggle="modal" data-target=".add-model"> <i class="fas fa-plus-circle"></i> Create Age Group</button>
            </div>
            @if(session()->has('success'))
            <div class="alert alert-dismissable alert-success">
               <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button> <strong>
               {!! session()->get('success') !!}
               </strong>
            </div>
            @endif @if(session()->has('error'))
            <div class="alert alert-dismissable alert-danger">
               <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button> <strong>
               {!! session()->get('error') !!}
               </strong>
            </div>
            @endif
            @foreach ($errors->all() as $message)
            <div class="alert alert-dismissable alert-danger">
               <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button> <strong>
               {{ $message }}</strong>
            </div>
            @endforeach
                    <div class="card-body">
                    <div class="table-responsive">
                     <table id="table" class="mb-0 table table-striped">
                        <thead>
                           <tr>
                              <th>Sr. No</th>
                              <th>Name</th>
                              <th>Age</th>
                              <th>Status</th>
                              <th>Edit</th>
                              <th>Delete</th>
                           </tr>
                        </thead>
                        <tbody>
                           @foreach($ages as $key=>$age)
                           <tr>
                              <th scope="row">{{$key+1}}</th>
                              <td>{{$age->name}}</td>
                              <td>{{$age->from}} - {{$age->to}}</td>
                              <td><label class="switch">
                                 @if($age->status=='1')
                                 @php $status='checked'; @endphp
                                 @else
                                 @php $status=''; @endphp
                                 @endif
                                 <input {{$status}}  type="checkbox" class="agestatus" ageid="{{$age->id}}">
                                 <span class="slider round"></span>
                                 </label>

                              </td>
                              <td><button type="button" class="edit-btn-bg btn mr-2 mb-2 btn-primary" data-toggle="modal" data-target="#edit-model{{$key}}"><i class="fas fa-pencil-alt"></i></button>
                              </td>
                              <td>
                                 <form class="delete" action="{{route('agegroup.destroy',$age->id)}}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit" class=" btn mr-2 mb-2 btn-primary " ><i class="far fa-trash-alt"></i></button>
                                 </form>
                              </td>
                           </tr>
                           @endforeach
                        </tbody>
                     </table>
                  </div>
                    </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection

 @section('model')

 <!-- Add Model Start Here -->
<div class="modal fade bd-example-modal-sm show nextstep" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true">
   <div class="modal-dialog modal-md">
      <div class="modal-content">

         <div class="modal-body text-center">
<h3 class="text-success"><b>Congrulations ! </b></h3>
<p>You have added a age group, now you can continue</p>
<div>
<a href="#"  data-toggle="modal" data-target=".add-model">Add more age groups</a></div>
<div>Or</div>
<a href="/admin/domain?success=1">Add new domain</a>

         </div>

      </div>
   </div>
</div>
<!-- Add Model Ends here -->


<!-- Add Model Start Here -->
<div class="modal fade bd-example-modal-lg show add-model" tabindex="-1"  role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Add Age Group</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
         </div>
         <div class="modal-body">
            <form id="signupForm" class="col-md-10 mx-auto" method="post" action="{{ route('agegroup.store') }}" >
            <!-- novalidate="novalidate" -->
               @csrf
               <div class="form-group">
                  <label for="name">Age Group</label>
                  <input type="text" class="@error('name') is-invalid @enderror form-control" maxlength="50" name="name" placeholder="Age Group name" required>
               </div>
               <div class="form-group">
                  <label for="name">From</label>
                  <input type="number" class="@error('from') is-invalid @enderror form-control" min="1" max="99" name="from" placeholder="10" onKeyUp="if(this.value>99){this.value='99';}else if(this.value<0){this.value='0';}" required>
               </div>
               <div class="form-group">
                  <label for="name">To</label>
                  <input type="number" class="@error('name') is-invalid @enderror form-control" min="1" max="99" name="to" placeholder="20" onKeyUp="if(this.value>99){this.value='99';}else if(this.value<0){this.value='0';}" required>
               </div>
         </div>
         <div class="modal-footer">
         <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
         <button type="submit" class="btn btn-primary">Continue</button>
         </form>
         </div>
      </div>
   </div>
</div>
<!-- Add Model Ends here -->


@foreach($ages as $key=>$age)

<!-- Edit Model Start Here -->
<div class="modal fade bd-example-modal-lg show" id="edit-model{{$key}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-lg">
     <div class="modal-content">
       <div class="modal-header">
         <h5 class="modal-title" id="exampleModalLongTitle">Edit Age Group </h5>
         <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
      </div>
      <div class="modal-body">

	  <form  class="col-md-10 mx-auto" method="post" action="{{ route('agegroup.update',$age->id) }}" >
	  @method('PUT')

               @csrf
               <div class="form-group">
                  <label for="name">Age Group</label>
                  <input type="text" class="@error('name') is-invalid @enderror form-control" value="{{$age->name}}" maxlength="50" name="name" placeholder="Age Group name" required>
               </div>
               <div class="form-group">
                  <label for="name">From</label>
                  <input type="number" class="@error('from') is-invalid @enderror form-control" min="1" max="99" name="from" placeholder="10" value="{{$age->from}}"  required>
               </div>
               <div class="form-group">
                  <label for="name">To</label>
                  <input type="number" class="@error('name') is-invalid @enderror form-control" min="1" max="99" name="to" placeholder="20" value="{{$age->to}}"  required>
               </div>

         </div>
         <div class="modal-footer">
         <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
         <button type="submit" class="btn btn-primary">Update changes</button>
         </form>
         </div>
      </div>
   </div>
</div>
<!-- Edit Model Ends here -->
@endforeach

@endsection
@section('js')
<script>
   $(document).ready(function() {
     var success="{{Request::get('success')}}";
     if(success=='1')
     { 
          $('.add-model').modal('show');
     }
   	$('#table').DataTable();

$(document).on('change','.agestatus', function() {
    if(confirm("Are you sure want to change the status ?")) {
        var ageid = $(this).attr('ageid');
        window.location.href = "/admin/agegroup/"+ageid;
       }
       else{
         if($(this).prop('checked') == true){
            $(this).prop('checked', false); // Unchecks it
         } else{
            $(this).prop('checked', true);

         }
       }
      });
    });

    $(document).on('submit','.delete', function() {
var c = confirm("Are you sure want to delete ?");
return c; //you can just return c because it will be true or false
});
    </script>
      @endsection
