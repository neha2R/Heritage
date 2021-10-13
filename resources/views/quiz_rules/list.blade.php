@extends('layouts.app')
@section('content')
<!-- Header Section start here -->
<div class="app-main__outer">
<div class="app-main__inner">
<div class="app-page-title">
   <div class="page-title-wrapper">
      <div class="page-title-heading">
         Quiz Rules
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
               {{ __('Add Quiz Rule') }}
               <!-- <a href="{{ route('domain.create') }}"  class="float-right mb-2 mr-2 btn-transition btn btn-outline-primary">Primary
                  </a> -->

               <button type="button" class=" float-right btn mr-2 mb-2 btn-primary" data-toggle="modal" data-target=".add-model"> <i class="fas fa-plus-circle"></i> Create Quiz Rule</button>
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
                              <th>Quiz Type</th>
                              <th>Quiz Speed </th>
                              <th>View</th>
                              <th>Status</th>
                              <th>Edit</th>
                              <th>Delete</th>
                           </tr>
                        </thead>
                        <tbody>
                        @foreach($quizRules as $key=>$rule)
                           <tr>
                              <th scope="row">{{$key+1}}</th>
                              <th scope="row">@if(isset($rule->types)){{ucwords(strtolower($rule->types->name))}} @else {{'n/a'}}@endif</th>
                              <th>
                              {{ucwords(strtolower($rule->speeds->name))}}
                              </th>
                              <td><button type="button" class=" btn mr-2 mb-2 btn-primary" data-toggle="modal" data-target="#view-model{{$key}}"><i class="fas fa-eye"></i></button>
                              <td><label class="switch">
                                 @if($rule->status=='1')
                                 @php $status='checked'; @endphp
                                 @else
                                 @php $status=''; @endphp
                                 @endif
                                 <input {{$status}}  type="checkbox" class="status" ruleid="{{$rule->id}}">
                                 <span class="slider round"></span>
                                 </label>

                              </td>
                              </td>
                              <td><button type="button" class="edit-btn-bg btn mr-2 mb-2 btn-primary" data-toggle="modal" data-target="#edit-model{{$key}}"><i class="fas fa-pencil-alt"></i></button>
                              </td>
                              <td>
                                 <form class="delete" action="{{route('quizrules.destroy',$rule->id)}}" method="POST">
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
            <h5 class="modal-title" id="exampleModalLongTitle">Add Quiz Rule</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
         </div>
         <div class="modal-body">
            <form id="signupForm" class="col-md-10 mx-auto" method="post" action="{{ route('quizrules.store') }}" >
            <!-- novalidate="novalidate" -->
               @csrf
               <lable for="type"class="m-2">Quiz Type</lable>
               <div class="form-group mt-2">
                  <select class="@error('quiz_type_id') is-invalid @enderror form-control" name="quiz_type_id" id="type"  onchange="addType(this.value)" required>
                  <option>Select Any</option>
                  @foreach($quizType as $type)
                  <option value="{{$type->id}}">{{ucwords(strtolower($type->name))}}</option>
                  @endforeach
                   </select>
               </div>
               <lable class="m-2">Quiz Speed</lable>
               <div class="form-group mt-2">
                  <select class="@error('quiz_speed_id') is-invalid @enderror form-control" name="quiz_speed_id"  id="speed" required onchange="addSpeed(this.value)">
                  <option>Select Any</option>
                  @foreach($quizSpeed as $speed)
                  <option value="{{$speed->id}}">{{ucwords(strtolower($speed->name))}}</option>
                  @endforeach
                   </select>
               </div>
               <lable class="m-2">Scoring</lable>
               <div class="form-group mt-2">
                  <input type="text" maxlength="250" class="@error('scoring') is-invalid @enderror form-control" name="scoring" placeholder="Scoring" required>
               </div>
               <lable class="m-2">Negative Marking</lable>
               <div class="form-group mt-2">
                  <input type="text" maxlength="100" class="@error('negative_marking') is-invalid @enderror form-control" name="negative_marking"  placeholder="Negative Marking" required>
               </div>
               <lable class="m-2">Time Limit</lable>
               <div class="form-group row mt-2">
              
                  <div class="col-md-8">
                      <input type="text" maxlength="100" class="@error('time_limit') is-invalid @enderror form-control" name="time_limit" placeholder="Time Limit"  required>
                  </div>
                  <div class="col-md-4 ">
                      <input type="text" maxlength="100" class=" form-control time_limit"  disabled placeholder="0" required>
                  </div>
               </div>
               <lable class="m-2">No Of Players</lable>
               <div class="form-group row mt-2">
                  <div class="col-md-8">
                    <input type="text"  maxlength="100" class="@error('no_of_players') is-invalid @enderror form-control" name="no_of_players" placeholder="No. Of Players" required>
                    </div>
                  <div class="col-md-4">
                      <input type="text" maxlength="100" class=" form-control no_of_player" placeholder="0"  disabled required>
                  </div>
               </div>
               <lable class="m-2">Hint Guide</lable>
               <div class="form-group row mt-2">
                  <input type="text" maxlength="100" class="@error('hint_guide') is-invalid @enderror form-control" name="hint_guide"  placeholder="Hint Guide" required>
               </div>
               <lable class="m-2">Question Navigation</lable>
               <div class="form-group row mt-2">
                  <input type="text" maxlength="100" class="@error('que_navigation') is-invalid @enderror form-control" name="que_navigation" placeholder="Question Navigation" required>
               </div>
               <div class="form-group more" >
               </div>

               <div class="form-group row">
                  <a href="#" class="form-group btn btn-success ml-auto" onclick="addMore()">Add more..</a>
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


@foreach($quizRules as $key=>$rule)
<!-- edit Model Start Here -->
<div class="modal fade bd-example-modal-lg show " tabindex="-1" id="edit-model{{$key}}" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Edit Quiz Rule </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
         </div>
         <div class="modal-body">
            <form id="signupForm" class="col-md-10 mx-auto" method="post" action="{{ route('quizrules.update',$rule->id) }}" >
            <!-- novalidate="novalidate" -->
            @method('PUT')
               @csrf
               <lable class="m-2">Quiz Type</lable>
               <div class="form-group mt-2">
                  <select class="@error('quiz_type_id') is-invalid @enderror form-control" name="quiz_type_id" id="type" required onchange="addType(this.value)">
                  <option>Select Any</option>
                  @foreach($quizType as $type)
                  @php
                     $selected=$rule->quiz_type_id==$type->id?'selected':'';
                  @endphp
                  <option value="{{$type->id}}"  {{$selected}}>{{ucwords(strtolower($type->name))}}</option>
                  @endforeach
                   </select>
               </div>
               <lable class="m-2">Quiz Speed</lable>
               <div class="form-group mt-2">
                  <select class="@error('quiz_speed_id') is-invalid @enderror form-control" name="quiz_speed_id"  id="speed" required onchange="addSpeed(this.value)">
                  <option>Select Any</option>
                  @foreach($quizSpeed as $speed)
                  @php
                     $selected=$rule->quiz_speed_id==$speed->id?'selected':'';
                  @endphp
                  <option value="{{$speed->id}}" {{$selected}}>{{ucwords(strtolower($speed->name))}}</option>
                  @endforeach
                   </select>
               </div>
               <lable class="m-2">Scoring</lable>
               <div class="form-group mt-2">
                  <input type="text" maxlength="250" class="@error('scoring') is-invalid @enderror form-control" name="scoring" placeholder="Scoring" required value="{{$rule->scoring}}">
               </div>
               <lable class="m-2">Negative Marking</lable>
               <div class="form-group mt-2">
                  <input type="text" maxlength="100" class="@error('negative_marking') is-invalid @enderror form-control" name="negative_marking"  placeholder="Negative Marking" required value="{{$rule->negative_marking}}">
               </div>
               <lable class="m-2">Time Limit</lable>
               <div class="form-group row mt-2">
                  <div class="col-md-8">
                      <input type="text" maxlength="100" class="@error('time_limit') is-invalid @enderror form-control" name="time_limit" placeholder="Time Limit"  required value="{{$rule->time_limit}}">
                  </div>
                  <div class="col-md-4">
                      <input type="text" maxlength="100" class=" form-control time_limit" disabled placeholder="0" required value="{{$rule->speeds->duration}}">
                  </div>
               </div>
               <lable class="m-2">No Of Players</lable>
               <div class="form-group row mt-2">
                  <div class="col-md-8">
                    <input type="text" maxlength="100" class="@error('no_of_players') is-invalid @enderror form-control" name="no_of_players" placeholder="No. Of Players" required value="{{$rule->no_of_players}}" >
                    </div>
                  <div class="col-md-4">
                      <input type="text" maxlength="100" class=" form-control no_of_player" placeholder="0"  disabled required value="@if(isset($rule->types)){{$rule->types->no_of_player}}@endif">
                  </div>
               </div>
               <lable class="m-2">Hint Guide</lable>
               <div class="form-group row mt-2">
                  <input type="text" maxlength="100" class="@error('hint_guide') is-invalid @enderror form-control" name="hint_guide"  placeholder="Hint Guide" required value="{{$rule->hint_guide}}">
               </div>
               <lable class="m-2">Question Navigation</lable>
               <div class="form-group row mt-2">
                  <input type="text" maxlength="100" class="@error('que_navigation') is-invalid @enderror form-control" name="que_navigation" placeholder="Question Navigation" required value="{{$rule->que_navigation}}">
               </div>
               <div class="form-group moreone">
                 
                  
                  @php $myrule = json_decode($rule->more);@endphp
                  @if(isset($myrule)) 
                  @foreach($myrule as $Rule)
                  <div class="row box">
                     <div class="form-group col-md-10">
                        <input type="text" maxlength="100" class=" form-control box" name="more[]" value="{{$Rule}}" placeholder="More Value" required>
                     </div>
                     <div class="form-group col-md-2"><a href="#" class="btn btn-danger button-remove" >Remove</a>
                  </div>
               </div>

                  @endforeach
                  @endif
               </div>

               <div class="form-group row">
                  <a href="#" class="form-group btn btn-success ml-auto" onclick="addMoreOne()">Add more..</a>
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
<!-- Edit Model Ends here -->
@endforeach



@foreach($quizRules as $key=>$rule)
<!-- view Model Start Here -->
<div class="modal fade bd-example-modal-lg show " tabindex="-1" id="view-model{{$key}}" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">View Quiz Rule </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
         </div>
         <div class="modal-body">

         <lable class="m-2">Scoring</lable>
               <div class="form-group mt-2">
                  <input type="text"   disabled class="@error('scoring') is-invalid @enderror form-control" name="scoring" placeholder="Scoring" required value="{{$rule->scoring}}">
               </div>
               <lable class="m-2">Negative Marking</lable>
               <div class="form-group mt-2">
                  <input type="text"   disabled class="@error('negative_marking') is-invalid @enderror form-control" name="negative_marking"  placeholder="Negative Marking" required value="{{$rule->negative_marking}}">
               </div>
               <lable class="m-2">Time Limit</lable>
               <div class="form-group row mt-2">
                  <div class="col-md-8">
                      <input type="text" disabled class="@error('time_limit') is-invalid @enderror form-control" name="time_limit" placeholder="Time Limit"  required value="{{$rule->time_limit}}">
                  </div>
                  <div class="col-md-4">
                      <input type="text" disabled class=" form-control time_limit" disabled placeholder="0" required value="{{$rule->speeds->duration}}">
                  </div>
               </div>
               <lable class="m-2">No Of Players</lable>
               <div class="form-group row mt-2">
                  <div class="col-md-8">
                    <input type="number" disabled min="0" max="50" class="@error('no_of_players') is-invalid @enderror form-control" name="no_of_players" placeholder="No. Of Players" required value="{{$rule->no_of_players}}" >
                    </div>
                  <div class="col-md-4">
                      <input type="text" disabled class=" form-control no_of_player" placeholder="0"  disabled required value="@if(isset($rule->types)){{$rule->types->no_of_player}}@endif">
                  </div>
               </div>
               <lable class="m-2">Hint Guide</lable>
               <div class="form-group row mt-2">
                  <input type="text" disabled class="@error('hint_guide') is-invalid @enderror form-control" name="hint_guide"  placeholder="Hint Guide" required value="{{$rule->hint_guide}}">
               </div>
               <lable class="m-2">Question Navigation</lable>
               <div class="form-group row mt-2">
                  <input type="text" disabled class="@error('que_navigation') is-invalid @enderror form-control" name="que_navigation" placeholder="Question Navigation" required value="{{$rule->que_navigation}}">
               </div>
               <div class="form-group more">
                  @foreach(json_decode($rule->more) as $Rule)
                  <div class="form-group">

                        <input type="text" disabled class=" form-control box" name="more[]" value="{{$Rule}}" placeholder="More Value" required>

                  </div>


                  @endforeach
               </div>


         </div>
         <div class="modal-footer">
         <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>


         </div>
      </div>
   </div>
</div>
<!-- view Model Ends here -->
@endforeach



@endsection
@section('js')
<script>
   $(document).ready(function() {

   	$('#table').DataTable();

   });

   $(document).on('change','.status', function() {
    if(confirm("Are you sure want to change the status ?")) {
        var ruleid = $(this).attr('ruleid');
        window.location.href = "/admin/quizrules/"+ruleid;
       }
       else{
         if($(this).prop('checked') == true){
            $(this).prop('checked', false); // Unchecks it
         } else{
            $(this).prop('checked', true);

         }
       }
      });




    $(document).on('submit','.delete', function() {
var c = confirm("Are you sure want to delete ?");
return c; //you can just return c because it will be true or false
});

function addType(val){

   $.ajax({
      type:"get",
      url:'/admin/get_rule_type/'+val,
      success:function(data) {
            data=JSON.parse(data);
            $('.no_of_player').val(data.no_of_player);
      }
   });
}

function addSpeed(val){
   $.ajax({
      type:"get",
      url:'/admin/get_rule_speed/'+val,
      success:function(data) {
         data=JSON.parse(data);
            $('.time_limit').val(data.duration);
      }
   });
}

function addMoreOne(){
   $('.moreone').append('<div class="row box"><div class="form-group col-md-10 "><input type="text" maxlength="50" class=" form-control box" name="more[]" placeholder="More Value" required></div><div class="form-group col-md-2"><a href="#" class="btn btn-danger button-remove" >Remove</a></div></div>')
}

function addMore(){
   $('.more').append('<div class="row box "><div class="form-group col-md-10 "><input type="text" maxlength="50" class=" form-control box" name="more[]" placeholder="More Value" required></div><div class="form-group col-md-2"><a href="#" class="btn btn-danger button-remove" >Remove</a></div></div>')
}


$(document).on("click", ".button-remove", function() {
    $(this).closest(".box").remove();
});
    </script>
      @endsection
