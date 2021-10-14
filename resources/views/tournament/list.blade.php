@extends('layouts.app')
@section('css')
<style>
   input[type="file"] {
  display: block;
    }
.imageThumb {
  max-height: 75px;
  border: 2px solid;
  padding: 1px;
  cursor: pointer;
}
.pip {
  display: inline-block;
  margin: 10px 10px 0 0;
}
.remove {
  display: block;
  background: #444;
  border: 1px solid black;
  color: white;
  text-align: center;
  cursor: pointer;
  width: 20px;
    margin-top: 1px;
    position: absolute;
    float: right;
    background-color: red;
    z-index :  9999;
}
.remove:hover {
  background: white;
  color: black;
}
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css" integrity="sha512-f0tzWhCwVFS3WeYaofoLWkTP62ObhewQ1EZn65oSYDZUg1+CyywGKkWzm8BxaJj5HGKI72PnMH9jYyIFz+GH7g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection
@section('content')
<!-- Header Section start here -->
<div class="app-main__outer">
<div class="app-main__inner">
<div class="app-page-title">
   <div class="page-title-wrapper">
      <div class="page-title-heading">
          Tournament
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
               

               <button type="button" class="float-right btn mr-2 mb-2 btn-primary" data-toggle="modal" data-target=".add-model"> <i class="fas fa-plus-circle"></i> Add Tournament Quiz</button>
               <button type="button" class="float-right btn mr-2 mb-2 btn-primary" data-toggle="modal" data-target=".add-rule-model"> <i class="fas fa-plus-circle"></i> Add Tournament Rule</button>
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
               {{ $message }}
            </strong>
            </div>
            @endforeach
            <div class="card-body">
                <div class="table-responsive">
                    <table id="table" class="mb-0 table table-striped">
                        <thead>
                           <tr>
                              <th>#</th>
                              <th>Tournament Name</th>
                              <th>Frequency</th>
                              <th>Status </th>
                              <th>Edit</th>
                              <th>Delete</th>
                           </tr>
                        </thead>
                        <tbody>    
                        @foreach($tournaments as $key=>$tournament) 
                            <tr>
                            <th scope="row">{{$tournament->id}}</th>
                              <th scope="row">{{$tournament->title}}</th>
                             <td>{{$tournament->frequency->title}}</td>
                             <td><label class="switch">
                                 @if($tournament->status=='1')
                                 @php $status='checked'; @endphp
                                 @else
                                 @php $status=''; @endphp
                                 @endif
                                 <input {{$status}}  type="checkbox" class="status" tournament_id="{{$tournament->id}}">
                                 <span class="slider round"></span>
                                 </label>

                              </td>
                             <td><button type="button" class="edit-btn-bg btn mr-2 mb-2 btn-primary" data-toggle="modal" data-target="#edit-model{{$tournament->id}}"><i class="fas fa-pencil-alt"></i></td>
                             <td>
                                 <form class="delete" action="{{route('tournament.destroy',$tournament->id)}}" method="POST">
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

<!-- Tournament Add Rule Model Start here -->
<div class="modal fade bd-example-modal-lg show add-rule-model" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Add Tournament Rule</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
         </div>
         <div class="modal-body">
            <form id="signupForm" class="col-md-10 mx-auto" method="post" action="{{ route('tourrule.store') }}" >
            <!-- novalidate="novalidate" -->
               @csrf
               <lable for="type"class="m-2">Tournament </lable>
               <div class="form-group mt-2">
                  <select class="@error('quiz_type_id') is-invalid @enderror form-control" name="tournament_id" id="tournament_id" required>
                  <option>Select Tournament</option>
                  @foreach($tournaments as $type)
                  <option value="{{$type->id}}">{{ucwords(strtolower($type->title))}}</option>
                  @endforeach
                   </select>
               </div>
               
               <div class="row box"><div class="form-group col-md-10 "><input type="text" maxlength="100" class=" form-control box" name="details[]" placeholder="Details here" required></div><div class="form-group col-md-2"></div>
               </div>

               <div class="row box"><div class="form-group col-md-10 "><input type="text" maxlength="100" class=" form-control box" name="details[]" placeholder="Details here" required></div><div class="form-group col-md-2"></div>
               </div>

               <div class="row box"><div class="form-group col-md-10 "><input type="text" maxlength="100" class=" form-control box" name="details[]" placeholder="Details here" required></div><div class="form-group col-md-2"></div>
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
<!-- Tournament Add Rule Model Ends here -->

<!-- strat model quize type button  -->
    <div class="modal fade bd-example-modal-lg add-model" id="add-model" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;     top: 200px;" aria-hidden="true">
            <div class="modal-dialog">
                    <div class="modal-content">
                            <div class="modal-body">
                                    <div class= row>
                                            <div class = "col-6" style="text-align:center">
                                                    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target=".normal_quize_model" >Normal Quiz</button>
                                            </div>
                                                <div class = "col-6" style="text-align:center">
                                                    <button type="button" class="btn btn-secondary" data-toggle="modal"  data-target=".special_quize_model" >Special Quiz</button>
                                                </div>                        
                                    </div>          
                            </div>
                    </div>
            </div>
    </div>
<!-- strat model quize type button -->

<!-- Add normal quize Start Here  -->
<div class="modal fade bd-example-modal-lg normal_quize_model" tabindex="-1" role="dialog" aria-labelledby="normal_quize_model" style="display: none;" aria-hidden="true" >
    <div class="modal-dialog modal-lg">
            <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Add normal Quize</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                    </div>
                    <div class="modal-body">
                        <form class="col-md-10 mx-auto" method="post" action="{{ route('tournament.store') }}" enctype="multipart/form-data">
                            <div class="row">
                                @csrf
                                    <div class="col">
                                        <input type="text" class="form-control" name="title" placeholder="Title">
                                    </div>
                                <input type="hidden" name="quize_type" value='0'>
                                  <div class="col">
                                        <div class="form-group">
                                            <select name="age_group_id" class="@error('age_group_id') is-invalid @enderror form-control" required >
                                                <option disabled selected value> -- Select Age Group --</option>
                                                @foreach($age_groups as $age_group)
                                                    <option value="{{$age_group->id}}">{{$age_group->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                  </div>

                            </div>
                            <div class = "row"> 
                                <div class="col">
                                    <div class="form-group">
                                        <select name="difficulty_level_id" class="@error('difficulty_level_id') is-invalid @enderror form-control" required >
                                            <option  disabled selected value > -- Select Difficulty Level --</option>
                                            @foreach($difficulty_levels as $difficulty_level)
                                                <option value="{{$difficulty_level->id}}">{{$difficulty_level->name}}</option>
                                            @endforeach
                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <select name="theme_id" class="@error('theme_id') is-invalid @enderror form-control" required >
                                            <option disabled selected value > --Select Theme--</option>
                                            @foreach($themes as $theme)
                                                <option value="{{$theme->id}}">{{$theme->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class = "row"> 
                                <div class="col">
                                    <div class="form-group">
                                        <select name="domain_id" class="@error('domain_id') is-invalid @enderror form-control" required >
                                            <option  disabled selected value>--Select Domain--</option>
                                            @foreach($domains as $domain)
                                                <option value="{{$domain->id}}">{{$domain->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <select name="sub_domain_id" class="@error('sub_domain_id') is-invalid @enderror form-control" required >
                                            <option  disabled selected value>-- Select Sub Domain--</option>
                                            @foreach($subDomains as $subDomain)
                                                <option value="{{$subDomain->id}}">{{$subDomain->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class = "row"> 
                                <div class="col">
                                    <div class="form-group">
                                        <select name="frequency_id" class="@error('frequency_id') is-invalid @enderror form-control frequency" required >
                                            <option value="">Select Frequency</option>
                                            @foreach($frequencies as $freq)
                                                <option value="{{$freq->id}}">{{$freq->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col sessions">
                                    <input type="number" class="form-control" placeholder="Session Per Day " name="session_per_day">
                                </div>
                            </div>
                            <div class = "row is_attempt"> 
                                <div class="col">
                                    <div class="form-group">
                                        <select name="is_attempt" class="@error('is_attempt') is-invalid @enderror form-control" required >
                                            <option>User Frequency</option>
                                            <option value="0">Daily</option>   
                                            <option value="1">Once</option>  
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class = "row"> 
                                <div class="col">
                                    <div class="form-group">
                                        <select name="no_of_players" class="@error('domain_id') is-invalid @enderror form-control" required >
                                            <option  disabled selected value >--Select No. of Players--</option>
                                            <option value="10">10 Players</option>
                                            <option value="20">20 Players</option>
                                            <option value="30">30 Players</option>
                                            <option value="40">40 Players</option>
                                            <option value="50">50 Players</option>
                                            <option value="60">60 Players</option>
                                            <option value="70">70 Players</option>
                                            <option value="80">80 Players</option>
                                            <option value="90">90 Players</option>
                                            <option value="100">100 Players</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <input type="number" name="duration" class="form-control" placeholder="Duration in (Minutes)">
                                </div>
                            </div>                  
                            <div class = "row"> 
                                <div class="col">
                                    <input id="datetimepicker" class="form-control"  type="text" autocomplete="off" name="start_time" placeholder="start time" >
                                </div>
                                <div class="col">
                                    <input id="datetimepicker2" class="form-control"  type="text" autocomplete="off" name="end_time" placeholder="End Time" >
                                </div>
                            </div>

                            <div class = "row"> 

                                <div class="col">
                                <br>
                                    <input id="no_of_question" class="form-control"  type="number" autocomplete="off" name="no_of_question" placeholder="No of questions i.e 10" >
                                </div>

                                <div class="col">
                                <br>
                                    <input type="number" class="form-control" autocomplete="off" name="interval_session" id="interval_session" placeholder="Interval b/w session in (Minutes)">
                                </div>

                            </div>

                            <div class="row">
                                <div class="col">
                                    <br>
                                    <div class="form-group">
                                            <div class="field" align="left">
                                                <label>Banner Image</label> 
                                                <input type="file" id="files" name="media_name" accept="image/*" multiple   />
                                            </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <br>
                                    <div class="form-group">
                                        <div class="field" align="left">
                                            <label>Sponsor Images</label> 
                                            <input type="file" id="files" name="sponsor_media_name" accept="image/*" multiple   />
                                        </div>
                                    </div>
                                </div>
                               
                            </div>                        
                    </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" data-toggle="modal" class="btn btn-primary" name="preference_questions" value="1" >choose your preference questions</button>
                            <button type="submit" data-toggle="modal" class="btn btn-primary" name="preference_questions" value="0">Submit</button>
                        </div>
                    </form>
            </div>
    </div>
</div>
<!-- Add normal quize  Ends here -->

<!-- special  quize Start Here  -->
<div class="modal fade bd-example-modal-lg special_quize_model" tabindex="-1" role="dialog" aria-labelledby="special_quize_model" style="display: none;"   aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add Special Quize</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                </div>
                <div class="modal-body">
                        <form class="col-md-10" method="post" action="{{ route('tournament.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                    <div class="col">
                                        <input type="text" class="form-control" name="title" placeholder="Title">          
                                    </div>
                                <input type="hidden" name="quize_type" value='1' />
                                <div class="col">
                                    <div class="form-group">
                                        <select name="age_group_id" class="@error('age_group_id') is-invalid @enderror form-control" required >
                                            <option  disabled selected value >--Select Age Group--</option>
                                            @foreach($age_groups as $age_group)
                                            <option value="{{$age_group->id}}">{{$age_group->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                            </div>   
</div>                    
                            <div class = "row "> 
                                <div class="col">
                                    <div class="form-group">
                                        <select name="frequency_id" class="@error('difficulty_level_id') is-invalid @enderror form-control" required >
                                            <option>Frequency</option>    
                                        </select>
                                    </div>
                                </div>
                                <div class="col ">
                                    <input class="form-control" type="text" name="session_per_day" placeholder=" Session Per " >
                                </div>
                            </div>

                           
                            <div class = "row"> 
                                <div class="col">
                                    <div class="form-group">
                                        <select name="no_of_players" class="@error('domain_id') is-invalid @enderror form-control" required >
                                            <option  disabled selected value> --Select No. of Players--</option>
                                            <option value="10">10 Players</option>
                                            <option value="20">20 Players</option>
                                            <option value="30">30 Players</option>
                                            <option value="40">40 Players</option>
                                            <option value="50">50 Players</option>
                                            <option value="60">60 Players</option>
                                            <option value="70">70 Players</option>
                                            <option value="80">80 Players</option>
                                            <option value="90">90 Players</option>
                                            <option value="100">100 Players</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control" name="duration" placeholder="Duration">
                                </div>
                            </div>
                            <div class = "row"> 
                            <div class="col">
                                    <input id="datetimepicker"  type="text" name="start_time" autocomplete="off" class="form-control" placeholder="start time" />
                                </div>

                                <div class="col">
                                    <div class="form-group">
                                        <input type="text" class="form-control" autocomplete="off" name="interval_bw_session" placeholder="Interval b/w session">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <input type="text" class="form-control" name="no_of_question" placeholder="No. of question">
                                </div>                            
                                <div class="col">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="mark_per_question" placeholder="Mark Per Question"/>
                                    </div>
                                </div>
                            </div>                
                        <!-- <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <select name="negative_marking" class="@error('domain_id') is-invalid @enderror form-control" required >
                                        <option>Negative Marking</option>
                                    </select>
                                </div>
                            </div>                            
                            <div class="col">
                                <input type="text" class="form-control" name="negative_mark_per_question" placeholder="Negative Mark Per Question">
                            </div>
                        </div> -->
                        <div class="row">
                            <div class="col">
                                <br>
                                <div class="form-group">
                                    <div class="field" align="left">
                                        <h3>Upload your images</h3> 
                                        <input type="file" class="form-control" id="files" name="media_name" accept="image/*" multiple   />
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <br>
                                <div class="form-group">
                                    <div class="field" align="left">
                                        <h3>Excel File </h3> 
                                        <input type="file" class="form-control" id="files" name="sponsor_media_name" accept="image/*" multiple   />
                                    </div>
                                </div>
                                <a type="button" class="btn btn-warning" href={{ route("tournament-excel-download") }}>Excel Sample </a>
                            </div>
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

<!-- special normal quize  Ends here -->


@foreach($tournaments as $key=>$tournament)
<!-- edit  quize Start Here   -->

 @if($tournament->type == 0) 

<div class="modal fade bd-example-modal-lg update_normal_quize_model" id="edit-model{{$tournament->id}}" tabindex="-1" role="dialog" aria-labelledby="update_normal_quize_model" style="display: none;" aria-hidden="true" >
    <div class="modal-dialog modal-lg">
            <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Edit Normal Quize</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                    </div>
                    
                    <div class="modal-body">
                        <form class="col-md-10 mx-auto" method="post" action="{{ route('tournament.update',$tournament) }}" enctype="multipart/form-data">
                            <div class="row">
                                @method('PUT')
                                @csrf
                                    <div class="col">
                                        <input type="text" class="form-control" name="title" value="{{$tournament->title}}" placeholder="Title">
                                    </div>
                                <input type="hidden" name="quize_type" value='0'>
                                  <div class="col">
                                        <div class="form-group">
                                            <select name="age_group_id" class="@error('age_group_id') is-invalid @enderror form-control" required >
                                                <option value="{{$tournament->age_group->id}}" selected>{{$tournament->age_group->name}}</option>
                                                <option disabled  value> -- Select Age Group --</option>
                                                @foreach($age_groups as $age_group)
                                                    <option value="{{$age_group->id}}">{{$age_group->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                  </div>

                            </div>
                            <div class = "row"> 
                                <div class="col">
                                    <div class="form-group">
                                        <select name="difficulty_level_id" class="@error('difficulty_level_id') is-invalid @enderror form-control" required >
                                            <option value="{{$tournament->difficulty_level->id}}" selected>{{$tournament->difficulty_level->name}}</option>
                                            <option  disabled  value > -- Select Difficulty Level --</option>
                                            @foreach($difficulty_levels as $difficulty_level)
                                                <option value="{{$difficulty_level->id}}">{{$difficulty_level->name}}</option>
                                            @endforeach
                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <select name="theme_id" class="@error('theme_id') is-invalid @enderror form-control" required >
                                            <option value="{{$tournament->theme->id}}" selected>{{$tournament->theme->title}}</option>
                                            <option disabled  value > --Select Theme--</option>
                                            @foreach($themes as $theme)
                                                <option value="{{$theme->id}}">{{$theme->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class = "row"> 
                                <div class="col">
                                    <div class="form-group">
                                        <select name="domain_id" class="@error('domain_id') is-invalid @enderror form-control" required >
                                        <option value="{{$tournament->domain->id}}" disabled selected>{{$tournament->domain->name}}</option>    
                                        <!-- <option  disabled  value>--Select Domain--</option>
                                            @foreach($domains as $domain)
                                                <option value="{{$domain->id}}">{{$domain->name}}</option>
                                            @endforeach -->
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <select name="sub_domain_id" class="@error('sub_domain_id') is-invalid @enderror form-control" required >
                                            <option value="{{$tournament->sub_domain->id}}" disabled selected>{{$tournament->sub_domain->name}}</option>
                                            <!-- <option  disabled value>-- Select Sub Domain--</option>
                                            @foreach($subDomains as $subDomain)
                                                <option value="{{$subDomain->id}}">{{$subDomain->name}}</option>
                                            @endforeach -->
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class = "row"> 
                                <div class="col">
                                    <div class="form-group">
                                        <select name="frequency_id" class="@error('frequency_id') is-invalid @enderror form-control frequency" required >
                                            <option value="{{$tournament->frequency->id}}" selected>{{$tournament->frequency->title}}</option>
                                            <option value="">Select Frequency</option>
                                            @foreach($frequencies as $freq)
                                                <option value="{{$freq->id}}">{{$freq->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col sessions">
                                    <input type="number" class="form-control" placeholder="Session Per Day " value="{{$tournament->session_per_day}}" name="session_per_day">
                                </div>
                            </div>
                            <div class = "row is_attempt"> 
                                <div class="col">
                                    <div class="form-group">
                                        <select name="is_attempt" class="@error('is_attempt') is-invalid @enderror form-control" required >
                                            @if($tournament->is_attempt == 0)
                                            <option value="0" selected>Daily</option>
                                            <option disabled value>User Frequency</option>
                                            <option value="1">Once</option>
                                            @endif 
                                            
                                            @if($tournament->is_attempt == 1)
                                            <option value="1">Once</option>
                                            <option disabled value>User Frequency</option>
                                            <option value="0" selected>Daily</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class = "row"> 
                                <div class="col">
                                    <div class="form-group">
                                        <select name="no_of_players" class="@error('domain_id') is-invalid @enderror form-control" required >
                                            <option selected value="{{$tournament->no_players}}" >{{$tournament->no_players}}</option>
                                            <option  disabled  value >--Select No. of Players--</option>
                                            <option value="10">10 Players</option>
                                            <option value="20">20 Players</option>
                                            <option value="30">30 Players</option>
                                            <option value="40">40 Players</option>
                                            <option value="50">50 Players</option>
                                            <option value="60">60 Players</option>
                                            <option value="70">70 Players</option>
                                            <option value="80">80 Players</option>
                                            <option value="90">90 Players</option>
                                            <option value="100">100 Players</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <input type="number" name="duration" class="form-control" value="{{$tournament->duration}}" placeholder="Duration in (Minutes)">
                                </div>
                            </div>                  
                            <div class = "row"> 
                                <div class="col">
                                    <input id="datetimepicker" class="form-control"  type="text" autocomplete="off" value="{{$tournament->start_time}}" name="start_time" placeholder="start time" >
                                </div>
                                <div class="col">
                                    <input id="datetimepicker2" class="form-control"  type="text" value="{{$tournament->end_time}}" autocomplete="off" name="end_time" placeholder="End Time" >
                                </div>
                            </div>

                            <div class = "row"> 

                                <div class="col">
                                <br>
                                    <input id="no_of_question" class="form-control"  type="number" value="{{$tournament->no_of_question}}" autocomplete="off" name="no_of_question" placeholder="No of Question" >
                                </div>

                                <div class="col">
                                <br>
                                    <input type="number" class="form-control" autocomplete="off" value="{{$tournament->interval_session}}" name="interval_session" id="interval_session" placeholder="Interval b/w session in (Minutes)">
                                </div>

                            </div>

                            <div class="row">
                                <div class="col">
                                    <br>
                                    <div class="form-group">
                                            <div class="field" align="left">
                                                <label>Banner Image</label> 
                                                <input type="file" id="files" name="media_name" accept="image/*" multiple   />
                                            </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <br>
                                    <div class="form-group">
                                        <div class="field" align="left">
                                            <label>Sponsor Images</label> 
                                            <input type="file" id="files" name="sponsor_media_name" accept="image/*" multiple   />
                                        </div>
                                    </div>
                                </div>
                               
                            </div>                        
                    </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            
                            <button type="submit" data-toggle="modal" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
            </div>
    </div>
</div>

@endif 

@if($tournament->type ==1 )
<div class="modal fade bd-example-modal-lg update_normal_quize_model" id="edit-model{{$key}}" tabindex="-1" role="dialog" aria-labelledby="update_normal_quize_model" style="display: none;" aria-hidden="true" >
    <div class="modal-dialog modal-lg">
            <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Edit Normal Quize</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                    </div>
                    <div class="modal-body">
                        <form class="col-md-10 mx-auto" method="post" action="{{ route('tournament.update',$tournament->id) }}" enctype="multipart/form-data">
                            <div class="row">
                                @method('PUT')
                                @csrf
                                    <div class="col">
                                        <input type="text" class="form-control" name="title" value="{{$tournament->title}}" placeholder="Title">
                                    </div>
                                <input type="hidden" name="quize_type" value='0'>
                                  <div class="col">
                                        <div class="form-group">
                                            <select name="age_group_id" class="@error('age_group_id') is-invalid @enderror form-control" required >
                                                <option value="{{$tournament->age_group->id}}" selected>{{$tournament->age_group->name}}</option>
                                                <option disabled  value> -- Select Age Group --</option>
                                                @foreach($age_groups as $age_group)
                                                    <option value="{{$age_group->id}}">{{$age_group->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                  </div>

                            </div>
                            <div class = "row"> 
                                <div class="col">
                                    <!-- <div class="form-group">
                                        <select name="difficulty_level_id" class="@error('difficulty_level_id') is-invalid @enderror form-control" required >
                                            <option value="{{$tournament->difficulty_level->id}}" selected>{{$tournament->difficulty_level->name}}</option>
                                            <option  disabled selected value > -- Select Difficulty Level --</option>
                                            @foreach($difficulty_levels as $difficulty_level)
                                                <option value="{{$difficulty_level->id}}">{{$difficulty_level->name}}</option>
                                            @endforeach
                                            
                                        </select>
                                    </div> -->
                                </div>
                                <div class="col">
                                    <!-- <div class="form-group">
                                        <select name="theme_id" class="@error('theme_id') is-invalid @enderror form-control" required >
                                            <option value="{{$tournament->theme->id}}" selected>{{$tournament->theme->title}}</option>
                                            <option disabled  value > --Select Theme--</option>
                                            @foreach($themes as $theme)
                                                <option value="{{$theme->id}}">{{$theme->title}}</option>
                                            @endforeach
                                        </select>
                                    </div> -->
                                </div>
                            </div>
                            <!-- <div class = "row"> 
                                <div class="col">
                                    <div class="form-group">
                                        <select name="domain_id" class="@error('domain_id') is-invalid @enderror form-control" required >
                                        <option value="{{$tournament->domain->id}}" selected>{{$tournament->domain->name}}</option>    
                                        <option  disabled  value>--Select Domain--</option>
                                            @foreach($domains as $domain)
                                                <option value="{{$domain->id}}">{{$domain->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <select name="sub_domain_id" class="@error('sub_domain_id') is-invalid @enderror form-control" required >
                                            <option value="{{$tournament->sub_domain->id}}" selected>{{$tournament->sub_domain->name}}</option>
                                            <option  disabled value>-- Select Sub Domain--</option>
                                            @foreach($subDomains as $subDomain)
                                                <option value="{{$subDomain->id}}">{{$subDomain->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div> -->
                            <div class = "row"> 
                                <div class="col">
                                    <div class="form-group">
                                        <select name="frequency_id" class="@error('frequency_id') is-invalid @enderror form-control frequency" required >
                                            <option value="{{$tournament->frequency->id}}" selected>{{$tournament->frequency->title}}</option>
                                            <option value="">Select Frequency</option>
                                            @foreach($frequencies as $freq)
                                                <option value="{{$freq->id}}">{{$freq->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col sessions">
                                    <input type="number" class="form-control" placeholder="Session Per Day " value="{{$tournament->session_per_day}}" name="session_per_day">
                                </div>
                            </div>
                            <div class = "row is_attempt"> 
                                <div class="col">
                                    <div class="form-group">
                                        <select name="is_attempt" class="@error('is_attempt') is-invalid @enderror form-control" required >
                                            @if($tournament->is_attempt == 0)
                                            <option value="0" selected>Daily</option>
                                            <option disabled value>User Frequency</option>
                                            <option value="1">Once</option>
                                            @endif 
                                            
                                            @if($tournament->is_attempt == 1)
                                            <option value="1">Once</option>
                                            <option disabled value>User Frequency</option>
                                            <option value="0" selected>Daily</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class = "row"> 
                                <div class="col">
                                    <div class="form-group">
                                        <select name="no_of_players" class="@error('domain_id') is-invalid @enderror form-control" required >
                                            <option selected value="{{$tournament->no_players}}" >{{$tournament->no_players}}</option>
                                            <option  disabled  value >--Select No. of Players--</option>
                                            <option value="10">10 Players</option>
                                            <option value="20">20 Players</option>
                                            <option value="30">30 Players</option>
                                            <option value="40">40 Players</option>
                                            <option value="50">50 Players</option>
                                            <option value="60">60 Players</option>
                                            <option value="70">70 Players</option>
                                            <option value="80">80 Players</option>
                                            <option value="90">90 Players</option>
                                            <option value="100">100 Players</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <input type="number" name="duration" class="form-control" value="{{$tournament->duration}}" placeholder="Duration in (Minutes)">
                                </div>
                            </div>                  
                            <div class = "row"> 
                                <div class="col">
                                    <input id="datetimepicker" class="form-control"  type="text" autocomplete="off" value="{{$tournament->start_time}}" name="start_time" placeholder="start time" >
                                </div>
                                <div class="col">
                                    <input id="datetimepicker2" class="form-control"  type="text" value="{{$tournament->end_time}}" autocomplete="off" name="end_time" placeholder="End Time" >
                                </div>
                            </div>

                            <div class = "row"> 

                                <div class="col">
                                <br>
                                    <input id="no_of_question" class="form-control"  type="number" value="{{$tournament->no_of_question}}" autocomplete="off" name="no_of_question" placeholder="10" >
                                </div>

                                <div class="col">
                                <br>
                                    <input type="number" class="form-control" autocomplete="off" value="{{$tournament->interval_session}}" name="interval_session" id="interval_session" placeholder="Interval b/w session in (Minutes)">
                                </div>

                            </div>

                            <div class="row">
                                <div class="col">
                                    <br>
                                    <div class="form-group">
                                            <div class="field" align="left">
                                                <label>Banner Image</label> 
                                                <input type="file" id="files" name="media_name" accept="image/*" multiple   />
                                            </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <br>
                                    <div class="form-group">
                                        <div class="field" align="left">
                                            <label>Sponsor Images</label> 
                                            <input type="file" id="files" name="sponsor_media_name" accept="image/*" multiple   />
                                        </div>
                                    </div>
                                </div>
                               
                            </div>                        
                    </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            
                            <button type="submit" data-toggle="modal" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
            </div>
    </div>
</div>

@endif

<!--  edoit quize start here -->
@endforeach


@endsection
@section('js')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.js" integrity="sha512-+UiyfI4KyV1uypmEqz9cOIJNwye+u+S58/hSwKEAeUMViTTqM9/L4lqu8UxJzhmzGpms8PzFJDzEqXL9niHyjA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
   $(document).ready(function() {
      var cart=1;
   	$('#table').DataTable();
    // $('#add_more_post').hide();
    $("#videos").removeAttr("required");
    var x=1;

    $(document).on('submit','.delete', function() {
        var c = confirm("Are you sure want to delete ?");
        return c; //you can just return c because it will be true or false
    });

    $(document).on('change','.status', function() {


if(confirm("Are you sure want to change the status ?")) {
    var tournament_id = $(this).attr('tournament_id');
    window.location.href = "/admin/tournament/"+tournament_id;
   }
   else{
     if($(this).prop('checked') == true){
        $(this).prop('checked', false); // Unchecks it
     } else{
        $(this).prop('checked', true);

     }
   }
  });
    $(document).on('change','.frequency', function() {
          if($(this).val()==1){
            $('.sessions').show();
            $('.is_attempt').hide();
            $('#interval_session').removeAttr('readonly')
          }else{
            $('.is_attempt').show();
              $('.sessions').hide();
          } 
          //interval_session
          if($(this).val()==2){
         $('#interval_session').attr('readonly','readonly')
          }

          if($(this).val()==3){
            $('#interval_session').attr('readonly','readonly')
          }
    });
    // set on click on add tournament quize
    // $("#add_normal_quize_button").on('click',function()
    // {
    //     $("#normal_quize_model").modal('show');
    // });

    // $("#special_quize_model-btn").on('click',function(){
    //     $("#special_quize_model").modal('show');
    // });



 
    $('#datetimepicker').datetimepicker();   
    $('#datetimepicker2').datetimepicker();   
    if (window.File && window.FileList && window.FileReader) {
        
        $("#files").on("change", function(e) {
        var files = e.target.files,
        filesLength = files.length;
        for (var i = 0; i < filesLength; i++) {
        var f = files[i]
        var fileReader = new FileReader();
        fileReader.onload = (function(e) {
          var file = e.target;
          $("<span class=\"pip\">" +
            "<input type=\"button\"  value=\"x\" class=\"remove\" /><img class=\"imageThumb\" style=\"width:120px;\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
            "<br/> ").insertAfter("#files");
          $(".remove").click(function(){
            $(this).parent(".pip").remove();
          });
        });
        fileReader.readAsDataURL(f);
      }
        });
    }
   else 
   {
    alert("Your browser doesn't support to File API");
    }

    if (window.File && window.FileList && window.FileReader) {
        $("#videos").on("change", function(e) {
        var files = e.target.files,
        filesLength = files.length;
        for (var i = 0; i < filesLength; i++) {
            var f = files[i]
            var fileReader = new FileReader();
            fileReader.onload = (function(e) {
            var file = e.target;
            $("<span class=\"pip\">" +
            "<input type=\"button\"  value=\"x\" class=\"remove\" /><video style=\"width:200px;\"  controls><source class=\"imageThumb\"  src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>  </video>" +
            "<br/> ").insertAfter("#videos");
            $(".remove").click(function(){
                $(this).parent(".pip").remove();
            });
            });
        fileReader.readAsDataURL(f);
      }
     });
    }
    else 
    {
        alert("Your browser doesn't support to File API");
    }

});

function addMore(){
   $('.more').append('<div class="row box "><div class="form-group col-md-10 "><input type="text" maxlength="50" class=" form-control box" name="details[]" placeholder="Details Here" required></div><div class="form-group col-md-2"><a href="#" class="btn btn-danger button-remove" >Remove</a></div></div>')
}

$(document).on("click", ".button-remove", function() {
    $(this).closest(".box").remove();
});
</script>
      @endsection
