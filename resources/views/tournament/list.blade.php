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
               

               <button type="button" class=" float-right btn mr-2 mb-2 btn-primary" data-toggle="modal" data-target=".add-model"> <i class="fas fa-plus-circle"></i> Add Tournament Quiz</button>
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
                              <th>Tournament Name</th>
                              <th>Frequency</th>
                              <th>Status </th>
                              <th>Edit</th>
                              <th>Delete</th>
                           </tr>
                        </thead>
                        <tbody>
                        
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


<!-- strat model quize type button  -->
    <div class="modal fade bd-example-modal-lg add-model" id="add-model" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class= row>
                        <div class = "col-6" style="text-align:center">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" id="add_normal_quize_button" data-target="#normal_quize_model" >Normal Quiz</button>
                        </div>
                        <div class = "col-6" style="text-align:center">
                            <!-- <button type="button" class="btn btn-secondary" id="add_special_quize_button" >Special Quiz</button> -->
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" id="special_quize_model-btn" data-target="#special_quize_model" >Special Quiz</button>
                        </div>
                        
                    </div>          
                </div>
            </div>
        </div>
    </div>
<!-- strat model quize type button -->




<!-- Add normal quize Start Here  -->
<div class="modal fade bd-example-modal-lg show add-normal-quize" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true" id="normal_quize_model">
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
                                <option>Age Group</option>
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
                                <option>Difficulty Level</option>
                                @foreach($difficulty_levels as $difficulty_level)
                                    <option value="{{$difficulty_level->id}}">{{$difficulty_level->name}}</option>
                                @endforeach
                                
                            </select>
                        </div>
                    </div>

                    <div class="col">
                        <div class="form-group">
                            <select name="theme_id" class="@error('theme_id') is-invalid @enderror form-control" required >
                                <option>Theme</option>
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
                                <option>Domain</option>
                                @foreach($domains as $domain)
                                    <option value="{{$domain->id}}">{{$domain->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col">
                        <div class="form-group">
                            <select name="sub_domain_id" class="@error('sub_domain_id') is-invalid @enderror form-control" required >
                                <option>Sub Domain</option>
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
                            <select name="frequency_id" class="@error('frequency_id') is-invalid @enderror form-control" required >
                                <option>Frequency</option>
                            </select>
                        </div>
                    </div>

                    <div class="col">
                        <input type="text" class="form-control" placeholder="Session Per Day" name="session_per_day">
                    </div>
                </div>


                <div class = "row"> 
                    <div class="col">
                        <div class="form-group">
                            <select name="no_of_players" class="@error('domain_id') is-invalid @enderror form-control" required >
                                <option>No. of Players</option>
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
                        <input type="text" name="duration" class="form-control" placeholder="Duration">
                    </div>
                </div>
                
                
                <div class = "row"> 
                   <div class="col">
                   <input id="datetimepicker" class="form-control"  type="text" name="start_time" placeholder="start time" >
                    </div>

                    <div class="col">
                        <input type="text" class="form-control" name="interval_session" placeholder="Interval b/w session">
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <br>
                        <div class="form-group">
                            <div class="field" align="left">
                                <h3>Upload your images</h3> 
                                <input type="file" id="files" name="media_name" accept="image/*" multiple   />
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <br>
                        <div class="form-group">
                            <div class="field" align="left">
                                <h3>Logo images</h3> 
                                <input type="file" id="files" name="logo_media_name" accept="image/*" multiple   />
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <br>
                        <button type="button" class="btn btn-success" >Add Question Manually</button>
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

<!-- Add normal quize  Ends here -->


<!-- special  quize Start Here  -->
    <div class="modal fade bd-example-modal-lg show add-special-quize" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;"   aria-hidden="true" id="special_quize_model">
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
                                    <option>Age Group</option>
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
                                    <select name="frequency_id" class="@error('difficulty_level_id') is-invalid @enderror form-control" required >
                                        <option>Frequency</option>    
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <input class="form-control" type="text" name="session_per_day" placeholder=" Session Per Day" >
                            </div>
                        </div>
                        <div class = "row"> 
                            <div class="col">
                                <div class="form-group">
                                    <select name="no_of_players" class="@error('domain_id') is-invalid @enderror form-control" required >
                                        <option>No. of Players</option>
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
                                <input id="datetimepicker"  type="text" name="start_time" class="form-control" placeholder="start time" />
                           </div>

                            <div class="col">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="interval_bw_session" placeholder="Interval b/w session">
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



                
                <div class="row">
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

                </div>

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
                                <input type="file" class="form-control" id="files" name="tournament_question_bluck"     />
                            </div>
                        </div>
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




@endsection
 
@section('js')

<script src="/jquery.js"></script>
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

    
    // set on click on add tournament quize
    $("#add_normal_quize_button").on('click',function()
    {
        $("#normal_quize_model").modal('show');
    });

    $("#special_quize_model-btn").on('click',function(){
        $("#special_quize_model").modal('show');
    });

    $('#datetimepicker').datetimepicker();
    
    
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
          
          // Old code here
          /*$("<img></img>", {
            class: "imageThumb",
            src: e.target.result,
            title: file.name + " | Click to remove"
          }).insertAfter("#files").click(function(){$(this).remove();});*/
          
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



    </script>


      @endsection
