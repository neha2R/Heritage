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

               <button type="button" class=" float-right btn mr-2 mb-2 btn-primary" data-toggle="modal" data-target=".card-type-model"> <i class="fas fa-plus-circle"></i> Create Feed</button>
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
                              <th scope="row">{{$feedContent->title}}</th>  
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

 <!-- Show Card Type Start Here  -->


 <div class="modal fade bd-example-modal-lg card-type-model" id="card-type-model" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class= row>
                        <div class = "col-6" style="text-align:center">
                            <button type="button" class="btn btn-secondary" data-toggle="modal" id="add_new_card_button" data-target=".add-model" >Add New Card</button>
                        </div>
                        <div class = "col-6" style="text-align:center">
                            
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" id="add_existing_card_button" data-target="#add_existing_card_model" >Add Existing Card</button>
                        </div>
                        
                    </div>          
                </div>
            </div>
        </div>
    </div>
 <!-- Show Card Type End  Here  -->
<!-- Add Model Start Here -->
<div class="modal fade bd-example-modal-lg  add-model" id="add-model" tabindex="-1" role="dialog" aria-labelledby="add-model" style="display: none;" aria-hidden="true">
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
                     <option disabled selected value>-- Select Theme --</option>
                     @foreach($themes as $theme)
                     <option value="{{$theme->id}}">{{$theme->title}}</option>
                     @endforeach
                  </select>
               </div>
               <div class="form-group">
                  <select name="domain_id" class="@error('domain_id') is-invalid @enderror form-control" required >
                     <option disabled selected value> -- Select Domain --</option>
                     @foreach($domains as $domain)
                     <option value="{{$domain->id}}">{{$domain->name}}</option>
                     @endforeach
                  </select>
               </div>
               <div class="form-group">
                  <select name="feed_id" id="feed_id" class="@error('feed_id') is-invalid @enderror form-control" required >
                     <!-- <option>Type</option> -->
                     <option onchnage  disabled selected value> -- Select Feed Type -- </option>
                     @foreach($feeds as $feed)
                     <option value="{{$feed->id}}" onchnage>{{$feed->title}}</option>
                     @endforeach
                  </select>
               </div>
               
               <div class="form-group">
                  <label for="tags"># Tags</label> 
                  <input type="text" class="@error('from') is-invalid @enderror form-control"  name="tags" placeholder="# Tags example(heritage,exam,education)" maxlength="100" >
               </div>
               
               <div class="form-group">
                   <label for="title">Title</label> 
                  <input type="text" class="@error('title') is-invalid @enderror form-control" maxlength="50" name="title[]" placeholder="Title" >
               </div>

           
          
               <div class="form-group">
                   <label for="name" id="duration">Description</label> 
                  <textarea class="@error('name') is-invalid @enderror form-control"   name="description[]" placeholder="Description" maxlength="200" id="description" ></textarea>
               </div>
                 
               
           

               <div id="single_post" style="display:none">
               <div class="form-group">
                  <label for="title">External Link</label>
                  <input type="text" class="@error('external_link') is-invalid @enderror form-control" maxlength="50" name="external_link" placeholder="https://www.google.com/" >
               </div>
                     <div class="form-group">
                        <div class="field" align="left">
                            <label class="img-label">Upload images</label> 
                           <input type="file" id="files" name="media_name[]" accept="image/*" multiple   />
                        </div>
                  </div>
               </div>

               <div id="module_title_description" style="display:none">
               </div>
               <div id="modules" style="display:none">
               <div class="form-group">
                  <select name="type[]" id="type" mytext="label0" myimage="myimage0" myvideo="myvideo0" class="@error('type') is-invalid @enderror form-control type"  >
                     <!-- <option>Type</option> -->
                     <option value=""> -- Select Media Type -- </option>
                     <option value="0">Image</option>
                     <option value="1">Video</option>
                     </select>
               </div>
               <div class="form-group" id="myimage0">
                        <div class="field" align="left">
                            <label class="img-label">Upload images</label> 
                           <input type="file" id="files" name="media_name[]" accept="image/*" multiple   />
                        </div>
                  </div>

                  <div class="form-group row " id="myvideo0" style="display:none">
                     <div class="field col" >
                            <label class="img-label label0" >Upload  Videos</label> 
                           <input type="file" id="videos" name="media_name_[0][]"  accept="video/*" multiple  />
                     </div>
                     <div class="col-md-6" >
                             <label>Video Link</label>
                        <input type="text" class="@error('video_link') is-invalid @enderror form-control" maxlength="50" name="video_link[]" placeholder="https://www.youtube.com/" >
                     </div>

                        <div id="placeholder_image">
                        <div class="form-group row">
                           <div class="field col" >
                              <label>Placeholder Image for Video</label> 
                              <input type="file" id="palceholder_image" name="placeholder_image[]" accept="image/*"  />
                           </div>
                        </div> 
                     </div>

                  </div> 
                   <!-- ===== Placholder Image for Video ================ -->
                
                  

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
      var add_more_btn_click=1;

      
      var cart=1;
   	$('#table').DataTable();
     // $('#add_more_post').hide();
      $("#videos").removeAttr("required");
      var x=1;
      
      // add new card button 
      // $("#add_new_card_button").on('click',function(){

      //    $("#add-model").modal('show');
      // });

      // add existing card button
      $("#add_existing_card_button").on('click',function(){
         window.location.href = "/admin/feed-collection";
      });

      $("#videos").on('change',function(){
         var $fileUpload = $("#videos");
               if (parseInt($fileUpload.get(0).files.length) > 1){
                  alert("You are only allowed to upload a maximum of 3 files");
                  $("videos").val("");
               }
      });
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

      $(document).on('change','.type', function() {
         var a = $(this).attr('mytext');
         var b = $(this).attr('myimage');
         var c = $(this).attr('myvideo');
         // alert(b); alert(c);
         if($(this).val() == 0){
            
            $("."+a).text('Image');
            $("#"+b).show();
            $("#"+c).hide();

            } 
            if($(this).val()==1)
            {
               // $(".img-label").text('Video');
               $("."+a).text('Video');
               $("#"+b).hide();
                $("#"+c).show();
            }
            });

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
               // $("#placeholder_image").attr("required");
               $("#files").removeAttr("required");
            }
            });    



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
               // $("#placeholder_image").attr("required");
               $("#files").removeAttr("required");
              


               $("#module_title_description").show();
               var title_description = '<div class="form-group">\
                   <label for="title">Card 1 Title</label>\
                   <input type="text" class="@error("title") is-invalid @enderror form-control" maxlength="50" name="title_fix" placeholder="Title" >\
               </div>\
               <div class="form-group">\
                   <label for="name" id="duration">Card 1 Description</label>\
                   <textarea class="@error("name") is-invalid @enderror form-control"   name="description_fix" placeholder="Description" maxlength="200" id="description" >\
                   </textarea>\
               </div><div class="form-group">\
                  <label for="title">Card 1 External Link</label>\
                  <input type="text" class="@error("external_link") is-invalid @enderror form-control" maxlength="50" name="external_link[]" placeholder="https://www.google.com/" >\
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
         
         if(add_more_btn_click<4)
         {
            var x = document.getElementById("signupForm");
            var post = '<div class="form-group">\
                  <label for="title">Card  '+parseInt(cart+1)+' Title </label>\
                  <input type="text" class="@error("title") is-invalid @enderror form-control" maxlength="50" name="title[]" placeholder="Title" required>\
               </div>\
               <div class="form-group">\
                   <label for="name" id="duration">Card '+parseInt(cart+1)+' Description</label>\
                  <textarea class="@error("name") is-invalid @enderror form-control"   name="description[]" placeholder="Description" maxlength="200" id="description" >\
                   </textarea>\
               </div>\
               <div class="form-group">\
                  <label for="title">Card '+parseInt(cart+1)+' External Link</label>\
                  <input type="text" class="@error("external_link") is-invalid @enderror form-control" maxlength="50" name="external_link[]" placeholder="https://www.google.com/" >\
               </div>\
               <div class="form-group">\
                  <select name="type[]" id="type" class="@error("type") is-invalid @enderror form-control type" mytext="label'+cart+'" myimage="myimage'+cart+'" myvideo="myvideo'+cart+'" required >\
                     <option value=""> -- Select Media Type -- </option>\
                     <option value="0">Image</option>\
                     <option value="1">Video</option>\
                     </select>\
               </div>\
               <div class="form-group" id="myimage'+cart+'">\
               <div class="field" align="left">\
               <label class="img-label">Upload images</label>\
               <input type="file" id="files" name=""media_name_[][]" accept="image/*" multiple>\
                           </div>\
                  </div>\
                  <div id="myvideo'+cart+'" style="display:none">\
                  <div class="form-group row">\
                        <div class="field col" >\
                            <label class="label'+cart+'">Upload your Videos</label>\
                           <input type="file"  id="videos" name="media_name_[][]" accept="video/*" multiple />\
                        </div>\
                        <div class="col" >\
                             <label>Video Link</label>\
                        <input type="text" class="@error("video_link") is-invalid @enderror form-control" maxlength="50" name="video_link[]" placeholder="https://www.youtube.com/" >\
                        </div>\
                  </div>\
                  <div id="placeholder_image">\
                  <div class="form-group row">\
                        <div class="field col" >\
                           <label>Placeholder Image your Videos</label>\
                           <input type="file" id="palceholder_image" name="placeholder_image[]" accept="image/*" />\
                        </div>\
                  </div>\
                  </div>\
                  </div>';

       
         $( "#append" ).append( post );
         cart++;
         add_more_btn_click++;
         // x.insertBefore(new_field, x.childNodes[pos]);
         }
         else
         {
            $('#add_more_post').hide();
         }
         
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
