@extends('layouts.app')
@section('content')
<!-- Header Section start here -->
<div class="app-main__outer">
<div class="app-main__inner">
<div class="app-page-title">
   <div class="page-title-wrapper">
      <div class="page-title-heading">
          Questions
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
               {{ __('Add Question') }}
               <a href="{{ route('question.create') }}"  class="float-right mb-2 mr-2 btn-transition btn btn-outline-primary">Add
                  </a>

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
                              <th>Sr. No.</th>
                              <th>Type</th>
                              <th>Speed </th>
                              <th>View</th>
                              <th>Status</th>
                              <th>Edit</th>
                              <th>Delete</th>
                           </tr>
                        </thead>
                        <tbody>
                           @foreach($questions as $key=>$question)
                           <tr>
                              <th scope="row">{{$key+1}}</th>
                              <th scope="row">{{$question->setting}}</th>
                             <td>{{$quizSpeed->no_of_question}}</td>
                              <td>{{$quizSpeed->duration}} </td>
                              <td><label class="switch">
                                 @if($quizSpeed->status=='1')
                                 @php $status='checked'; @endphp
                                 @else
                                 @php $status=''; @endphp
                                 @endif
                                 <input {{$status}}  type="checkbox" class="status" quizid="{{$quizSpeed->id}}">
                                 <span class="slider round"></span>
                                 </label>

                              </td>
                              <td><button type="button" class=" btn mr-2 mb-2 btn-primary" data-toggle="modal" data-target="#edit-model{{$key}}"><i class="fas fa-pencil-alt"></i></button>
                              </td>
                              <td>
                                 <form class="delete" action="{{route('quizspeed.destroy',$quizSpeed->id)}}" method="POST">
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
            <h5 class="modal-title" id="exampleModalLongTitle">Add Quiz Speed  Level</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
         </div>
         <div class="modal-body">
            <form id="signupForm" class="col-md-10 mx-auto" method="post" action="{{ route('quizspeed.store') }}" >
            <!-- novalidate="novalidate" -->
               @csrf
               <div class="form-group">
                  <label for="name">Quiz Speed</label>
                  <input type="text" class="@error('name') is-invalid @enderror form-control" maxlength="50" name="name" placeholder="Quiz Speed name" required>
               </div>
               <div class="form-group">
                  <label for="name">No Of Question</label>
                  <input type="number" class="@error('from') is-invalid @enderror form-control" min="1" max="99" name="no_of_question" placeholder="10"  required>
               </div>
               <div class="form-group">
                  <label for="name">Duration (In Min)</label>
                  <input type="number" class="@error('name') is-invalid @enderror form-control" min="10"  name="duration" placeholder="Enter time in min"  required>
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


@foreach($quizSpeeds as $key=>$quizSpeed)

<!-- Edit Model Start Here -->
<div class="modal fade bd-example-modal-lg show" id="edit-model{{$key}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-lg">
     <div class="modal-content">
       <div class="modal-header">
         <h5 class="modal-title" id="exampleModalLongTitle">Edit Quiz Speed </h5>
         <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
      </div>
      <div class="modal-body">

	  <form  class="col-md-10 mx-auto" method="post" action="{{ route('quizspeed.update',$quizSpeed->id) }}" >
	  @method('PUT')

               @csrf
               <div class="form-group">
                  <label for="name">Quiz Speed</label>
                  <input type="text" class="@error('name') is-invalid @enderror form-control" value="{{$quizSpeed->name}}" maxlength="50" name="name" placeholder="Quiz Speed name" required>
               </div>
               <div class="form-group">
                  <label for="name">No Of Question</label>
                  <input type="number" class="@error('from') is-invalid @enderror form-control" min="1" max="99" name="no_of_question" placeholder="10" value="{{$quizSpeed->no_of_question}}"  required>
               </div>
               <div class="form-group">
                  <label for="name">Duration (In Min)</label>
                  <input type="number" class="@error('name') is-invalid @enderror form-control" min="1"  name="duration" placeholder="Enter time in min" value="{{$quizSpeed->duration}}"  required>
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
        var quizid = $(this).attr('quizid');
        window.location.href = "/admin/quizspeed/"+quizid;
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
