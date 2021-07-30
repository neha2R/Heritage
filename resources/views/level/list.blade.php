@extends('layouts.app')
@section('content')
<!-- Header Section start here -->
<div class="app-main__outer">
<div class="app-main__inner">
<div class="app-page-title">
   <div class="page-title-wrapper">
      <div class="page-title-heading">
      Difficulty Level
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
               {{ __('Add Difficulty Level') }}
               <!-- <a href="{{ route('domain.create') }}"  class="float-right mb-2 mr-2 btn-transition btn btn-outline-primary">Primary
                  </a> -->

               <button type="button" class=" float-right btn mr-2 mb-2 btn-primary" data-toggle="modal" data-target=".add-model"> <i class="fas fa-plus-circle"></i> Create Difficulty Level</button>
            </div>
            @if(session()->has('success'))
            <div class="alert alert-dismissable alert-success">
               <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button> <strong>
               {!! session()->get('success') !!}
               </strong>
            </div>
            @endif @if(session()->has('error'))
            <div class="alert alert-dismissable alert-error">
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
                              <th>#</th>
                              <th>Name</th>
                              <th>Weightage (Per question) </th>
                              <th>Time (In Sec)</th>
                              <th>Status</th>
                              <th>Edit</th>
                              <th>Delete</th>
                           </tr>
                        </thead>
                        <tbody>
                           @foreach($difficultyLevels as $key=>$difficultyLevel)
                           <tr>
                              <th scope="row">{{$key+1}}</th>
                              <th scope="row">{{$difficultyLevel->name}}</th>
                             <td>{{$difficultyLevel->weitage_per_question}}</td>
                              <td>{{$difficultyLevel->time_per_question}} </td>
                              <td><label class="switch">
                                 @if($difficultyLevel->status=='1')
                                 @php $status='checked'; @endphp
                                 @else
                                 @php $status=''; @endphp
                                 @endif
                                 <input {{$status}}  type="checkbox" class="status" levelid="{{$difficultyLevel->id}}">
                                 <span class="slider round"></span>
                                 </label>

                              </td>
                              <td><button type="button" class=" btn mr-2 mb-2 btn-primary" data-toggle="modal" data-target="#edit-model{{$key}}"><i class="fas fa-pencil-alt"></i></button>
                              </td>
                              <td>
                                 <form class="delete" action="{{route('difflevel.destroy',$difficultyLevel->id)}}" method="POST">
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
<div class="modal fade bd-example-modal-lg show add-model" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Add Difficulty  Level</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
         </div>
         <div class="modal-body">
            <form id="signupForm" class="col-md-10 mx-auto" method="post" action="{{ route('difflevel.store') }}" >
            <!-- novalidate="novalidate" -->
               @csrf
               <div class="form-group">
                  <label for="name">Difficulty  Level</label>
                  <input type="text" class="@error('name') is-invalid @enderror form-control" maxlength="50" name="name" placeholder="Difficulty  Level name" required>
               </div>
               <div class="form-group">
                  <label for="name">Weightage Per Question</label>
                  <input type="number" class="@error('from') is-invalid @enderror form-control" min="1" max="99" name="weitage_per_question" placeholder="10"  required>
               </div>
               <div class="form-group">
                  <label for="name">Time Per Question (In sec)</label>
                  <input type="number" class="@error('name') is-invalid @enderror form-control" min="10"  name="time_per_question" placeholder="Enter time in sec"  required>
               </div>
         </div>
         <div class="modal-footer">
         <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
         <button type="submit" class="btn btn-primary">Save changes</button>
         </form>
         </div>
      </div>
   </div>
</div>
<!-- Add Model Ends here -->


@foreach($difficultyLevels as $key=>$difficultyLevel)

<!-- Edit Model Start Here -->
<div class="modal fade bd-example-modal-lg show" id="edit-model{{$key}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-lg">
     <div class="modal-content">
       <div class="modal-header">
         <h5 class="modal-title" id="exampleModalLongTitle">Edit Difficulty Level </h5>
         <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
      </div>
      <div class="modal-body">

	  <form  class="col-md-10 mx-auto" method="post" action="{{ route('difflevel.update',$difficultyLevel->id) }}" >
	  @method('PUT')

               @csrf
               <div class="form-group">
                  <label for="name">Difficulty  Level</label>
                  <input type="text" class="@error('name') is-invalid @enderror form-control" value="{{$difficultyLevel->name}}" maxlength="50" name="name" placeholder="Difficulty  Level name" required>
               </div>
               <div class="form-group">
                  <label for="name">Weightage Per Question</label>
                  <input type="number" class="@error('from') is-invalid @enderror form-control" min="1" max="99" name="weitage_per_question" placeholder="10" value="{{$difficultyLevel->weitage_per_question}}"  required>
               </div>
               <div class="form-group">
                  <label for="name">Time Per Question (In Sec)</label>
                  <input type="number" class="@error('name') is-invalid @enderror form-control" min="1"  name="time_per_question" placeholder="Enter time in sec" value="{{$difficultyLevel->time_per_question}}"  required>
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

   	$('#table').DataTable();

$(document).on('change','.status', function() {
    if(confirm("Are you sure want to change the status ?")) {
        var levelid = $(this).attr('levelid');
        window.location.href = "/admin/difflevel/"+levelid;
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
