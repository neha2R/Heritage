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
@endsection
@section('content')
<!-- Header Section start here -->
<div class="app-main__outer">
<div class="app-main__inner">
<div class="app-page-title">
   <div class="page-title-wrapper">
      <div class="page-title-heading">
          Feed
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
               {{ __('Add Feed') }}
               <!-- <a href="{{ route('domain.create') }}"  class="float-right mb-2 mr-2 btn-transition btn btn-outline-primary">Primary
                  </a> -->

               <button type="button" class=" float-right btn mr-2 mb-2 btn-primary" data-toggle="modal" data-target=".add-model"> <i class="fas fa-plus-circle"></i> Create Feed</button>
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
                              <th>Title</th>
                              <th>Type</th>
                              <th>Theme </th>
                              <th>Status </th>
                              <th>Edit</th>
                              <th>Delete</th>
                           </tr>
                        </thead>
                        <tbody>
                           @foreach($feedContents as $key=>$feedContent)
                           <tr>
                              <th scope="row">{{$key+1}}</th>
                              <th scope="row">{{$feedContent->feed_medium->title}}</th>  
                              <th scope="row">{{$feedContent->feedtype->title}}</th>
                             <td>{{$feedContent->theme->title}}</td>
                            <td></td>
                              <td><button type="button" class="edit-btn-bg btn mr-2 mb-2 btn-primary" data-toggle="modal" data-target="#edit-model{{$key}}"><i class="fas fa-pencil-alt"></i></button>
                              </td>
                              <td>
                                 <form class="delete" action="{{route('feed-content.destroy',$feedContent->id)}}" method="POST">
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
            <h5 class="modal-title" id="exampleModalLongTitle">Add Feed</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
         </div>
         <div class="modal-body">
            <form id="signupForm" class="col-md-10 mx-auto" method="post" action="{{ route('feed-content.store') }}" enctype="multipart/form-data" >
            <!-- novalidate="novalidate" -->
               @csrf
               <div class="form-group">
                  <select name="theme_id" class="@error('theme_id') is-invalid @enderror form-control" required >
                     <option>Theme</option>
                     @foreach($themes as $theme)
                     <option value="{{$theme->id}}">{{$theme->title}}</option>
                     @endforeach
                  </select>
               </div>
               <div class="form-group">
                  <select name="domain_id" class="@error('domain_id') is-invalid @enderror form-control" required >
                     <option value="">Domain</option>
                     @foreach($domains as $domain)
                     <option value="{{$domain->id}}">{{$domain->name}}</option>
                     @endforeach
                  </select>
               </div>
               <div class="form-group">
                  <select name="feed_id" id="feed_id" class="@error('feed_id') is-invalid @enderror form-control" required >
                     <!-- <option>Type</option> -->
                     <option onchnage value=""> Select Feed Type </option>
                     @foreach($feeds as $feed)
                     <option value="{{$feed->id}}" onchnage>{{$feed->title}}</option>
                     @endforeach
                  </select>
               </div>

               <div id="module_title_description" style="display:none">
               </div>
               
               <div class="form-group">
                  <label for="tags"># Tags</label> 
                  <input type="text" class="@error('from') is-invalid @enderror form-control"  name="tags" placeholder="# Tags example(heritage,exam,education)" maxlength="100" >
               </div>

               <div class="form-group">
                  <label for="title">External Link</label>
                  <input type="text" class="@error('external_link') is-invalid @enderror form-control" maxlength="50" name="external_link[]" placeholder="https://www.google.com/" >
               </div>
               
               <div class="form-group">
                   <label for="title">Title</label> 
                  <input type="text" class="@error('title') is-invalid @enderror form-control" maxlength="50" name="title[]" placeholder="Title" >
               </div>

           
          
               <div class="form-group">
                   <label for="name" id="duration">Description</label> 
                  <textarea class="@error('name') is-invalid @enderror form-control"   name="description[]" placeholder="Description" maxlength="200" id="description" >
                   </textarea>
               </div>
               <div id="single_post" style="display:none">
                     <div class="form-group">
                        <div class="field" align="left">
                            <h3>Upload your images</h3> 
                           <input type="file" id="files" name="media_name[]" accept="image/*" multiple   />
                        </div>
                  </div>
               </div>

               <div id="modules" style="display:none">
                  <div class="form-group row">
                        <div class="field col" >
                            <h3>Upload your Videos</h3> 
                           <input type="file" id="videos" name="media_name_[0][]" accept="video/*" multiple  />
                        </div>
                        <div class="col" >
                             <span>Video Link</span>
                        <input type="text" class="@error('video_link') is-invalid @enderror form-control" maxlength="50" name="video_link[]" placeholder="https://www.youtube.com/" >
                        </div>
                  </div> 
                   <!-- ===== Placholder Image for Video ================ -->
                  <div id="placeholder_image">
                     <div class="form-group row">
                        <div class="field col" >
                            <h3>Placeholder Image your Videos</h3> 
                           <input type="file" id="palceholder_image" name="placeholder_image[]" accept="image/*"  />
                        </div>
                     </div>                 
                  </div>
                  <div id="append"></div>
                  <button type="button" id="add_more_post" class="btn btn-sm btn-success float-right" >Add more post</button>                
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


@foreach($feedContents as $key=>$feedContent)

<!-- Edit Model Start Here -->
<div class="modal fade bd-example-modal-lg show" id="edit-model{{$key}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-lg">
     <div class="modal-content">
       <div class="modal-header">
         <h5 class="modal-title" id="exampleModalLongTitle">Edit Feed </h5>
         <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
      </div>
      <div class="modal-body">

	  <form  class="col-md-10 mx-auto" method="post" action="{{ route('feed-content.update',$feedContent->id) }}" >
	  @method('PUT')

               @csrf
               <!-- 
               <div class="form-group">
                  <label for="name">Quiz Speed Type</label>
                  <select name="quiz_speed_type" class="@error('quiz_speed_type') is-invalid @enderror form-control" required onchange="changeDurationOne(this.value)">
                     <option>Select Any</option>
                     <option value="single" >Per Question</option>
                     <option value="all">Whole Quiz</option>
                  </select>
               </div>
               -->

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
      var cart=1;
   	$('#table').DataTable();
     // $('#add_more_post').hide();
      $("#videos").removeAttr("required");
      var x=1;
      

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


      // var single_post_display = $("#single_post").css( "display" );
      // var module_display = $("#module").css( "display" );
      // if(single_post_display == 'none')
      // {
      //    $("#files").removeAttr("required");
      // }
      // else if(module_display == 'none')
      // {
      //    $("#videos").removeAttr("required");
      //    $("#palceholder_image").removeAttr("required");
         
      // }

      $(document).on('change','#feed_id', function() {
    


         if($(this).val() == 1){
            $("#single_post").show(); // Unchecks it
            $("#modules").hide(); 
           // $("#video").removeAttr("required");
            // select value 1 than hide button add more post and empty append div
            //$('#add_more_post').hide();
            $('#append').empty();
           // $('#placeholder_image').hide();
            $("#placeholder_image").removeAttr("required");
            $("#videos").removeAttr("required");
            $("#files").attr("required","required");
            $("#module_title_description").hide();
               $('#module_title_description').empty();

            //$("#files").attr("required");

            } if($(this).val()==2)
            {
               // 
               $('#add_more_post').show();
               $('#placeholder_image').show();
               $("#single_post").hide(); // Unchecks it
               $("#modules").show(); 

               $("#videos").attr("required");
               $("#placeholder_image").attr("required");
               $("#files").removeAttr("required");
              


               $("#module_title_description").show();
               var title_description = '<div class="form-group">\
                   <label for="title">Title</label>\
                   <input type="text" class="@error("title") is-invalid @enderror form-control" maxlength="50" name="title_fix" placeholder="Title" >\
               </div>\
               <div class="form-group">\
                   <label for="name" id="duration">Description</label>\
                   <textarea class="@error("name") is-invalid @enderror form-control"   name="description_fix" placeholder="Description" maxlength="200" id="description" >\
                   </textarea>\
               </div>';

               $("#module_title_description").append(title_description);
            }
            else{
              // $("#single_post").hide(); // Unchecks it
               $("#modules").hide(); 
               // $('#add_more_post').hide();
               // $('#placeholder_image').hide();
               //$('#append').empty();
         }
       
      });



      $(document).on('click','#add_more_post', function() {
         
         var x = document.getElementById("signupForm");
        

       


         var post = '<div class="form-group">\
                  <label for="title">External Link</label>\
                  <input type="text" class="@error("external_link") is-invalid @enderror form-control" maxlength="50" name="external_link[]" placeholder="https://www.google.com/" >\
               </div>\
               <div class="form-group">\
                  <label for="title">Titel</label>\
                  <input type="text" class="@error("title") is-invalid @enderror form-control" maxlength="50" name="title[]" placeholder="Title" required>\
               </div>\
               <div class="form-group">\
                   <label for="name" id="duration">description</label>\
                  <textarea class="@error("name") is-invalid @enderror form-control"   name="description[]" placeholder="Description" maxlength="200" id="description" >\
                   </textarea>\
               </div>\
               <div id="modules">\
                  <div class="form-group row">\
                        <div class="field col" >\
                            <h3>Upload your Videos</h3>\
                           <input type="file" id="videos" name="media_name_['+cart+'][]" accept="video/*" multiple  required/>\
                        </div>\
                        <div class="col" >\
                             <span>Video Link</span>\
                        <input type="text" class="@error("video_link") is-invalid @enderror form-control" maxlength="50" name="video_link[]" placeholder="https://www.youtube.com/" >\
                        </div>\
                  </div>\
                  </div>\
               <div id="placeholder_image">\
                  <div class="form-group row">\
                        <div class="field col" >\
                           <h3>Placeholder Image your Videos</h3>\
                           <input type="file" id="palceholder_image" name="placeholder_image[]" accept="image/*"   required/>\
                        </div>\
                        <div class="col" >\
                             <span>Placeholder Image Link</span>\
                        <input type="text" class="@error("video_link") is-invalid @enderror form-control" maxlength="50" name="placeholder_image" placeholder="Palceholder Image" >\
                        </div>\
                  </div>\
                  </div>\
                  '
               ;

        cart++;
         $( "#append" ).append( post );
         // x.insertBefore(new_field, x.childNodes[pos]);

         
  
      });


    $(document).on('submit','.delete', function() {
var c = confirm("Are you sure want to delete ?");
return c; //you can just return c because it will be true or false
});

 

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
  } else {
    alert("Your browser doesn't support to File API")
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
  } else {
    alert("Your browser doesn't support to File API")
  }


});



    </script>


      @endsection
