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
               <button type="button" class=" float-right btn mr-2 mb-2 btn-primary" data-toggle="modal" data-target=".add-model"> <i class="fas fa-plus-circle"></i> Add Question</button>

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
                              <th>Question</th>
                              <th>Domain </th>
                              <th>Group Name</th>
                              <th>Difficulty Level</th>
                              <th>View</th>
                               <th>Edit</th>
                              <th>Delete</th>
                           </tr>
                        </thead>
                        <tbody>
                           @foreach($questions as $key=>$question)
                           <tr>
                              <th scope="row">{{$key+1}}</th>
                              <th scope="row">{{$question->question}}</th>

                             <td>{{$question->questionsetting->domain->name}}</td>
                             <td>{{$question->questionsetting->age_group->name}}</td> <td>{{$question->questionsetting->difflevel->name}}</td>
                             <td><button type="button" class=" btn mr-2 mb-2 btn-primary" data-toggle="modal" data-target="#view-model{{$key}}"><i class="fas fa-eye-alt"></i></button>
                              </td>
                              <td><button type="button" class=" btn mr-2 mb-2 btn-primary" data-toggle="modal" data-target="#edit-model{{$key}}"><i class="fas fa-pencil-alt"></i></button>
                              </td>
                              <td>
                                 <form class="delete" action="{{route('question.destroy',$question->id)}}" method="POST">
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
            <h5 class="modal-title" id="exampleModalLongTitle">Add Question</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
         </div>
         <div class="modal-body">
            <form id="signupForm" class="col-md-10 mx-auto" method="post" action="{{ route('question.store') }}" enctype='multipart/form-data' >
            <!-- novalidate="novalidate" -->
               @csrf
               <div class="row">
                  <div class="col-md-10">
               <div class="form-group inner-addon right-addon">
                  <!-- <label for="name">Quiz Speed</label> -->
                    <span class="image-upload">
                        <label for="file-input1">
                        <i class="fa fa-paperclip form-control-feedback"></i>
                        </label>
                        <input id="file-input1" name="question_media" class="file-input" type="file" accept="*"/>
                     </span>
                  <input type="text" class="@error('question') is-invalid @enderror form-control"  name="question" placeholder="Type a question" required>
                  <!-- <span class="image-upload form-control-feedback">
                  <label for="file-input">
                  <i class="fa fa-paperclip" aria-hidden="true"></i>
                  </label>
                  <input id="file-input" type="file"/>
                  </span> -->

                  </div>
               </div>


                  <div class="col-md-2 yes" id="img1">
                    <img id="ImgPreview1"src="" class="preview1" />
                     <input type="button" id="removeImage1" value="x" class="btn-rmv1" />
                     <video width="141" class="video" id="video1" style="display:none" controls>
                     <source src="" id="video_here">
                        Your browser does not support HTML5 video.
                     </video>
                  </div>
                   </div>

                   <div class="row">
                  <div class="col-md-10">
               <div class="form-group inner-addon right-addon">
                  <!-- <label for="name">Quiz Speed</label> -->
                    <span class="image-upload">
                        <label for="file-input2">
                        <i class="fa fa-paperclip form-control-feedback"></i>
                        </label>
                        <input id="file-input2" name="option1_media" class="file-input" type="file" accept="*"/>
                     </span>
                  <input type="text" class="@error('option1') is-invalid @enderror form-control"  name="option1" placeholder="Option 1" required>
                  </div>
               </div>
                  <div class="col-md-2 yes" id="img2">
                    <img id="ImgPreview2"src="" class="preview2" />
                     <input type="button" id="removeImage2" value="x" class="btn-rmv2" />
                     <video width="141" class="video" id="video2" style="display:none" controls>
                     <source src="" id="video_here">
                        Your browser does not support HTML5 video.
                     </video>
                  </div>
                   </div>


                   <div class="row">
                  <div class="col-md-10">
               <div class="form-group inner-addon right-addon">
                  <!-- <label for="name">Quiz Speed</label> -->
                    <span class="image-upload">
                        <label for="file-input3">
                        <i class="fa fa-paperclip form-control-feedback"></i>
                        </label>
                        <input id="file-input3" name="option2_media" class="file-input" type="file" accept="*"/>
                     </span>
                  <input type="text" class="@error('option2') is-invalid @enderror form-control"  name="option2" placeholder="Option 2" required>
                  </div>
               </div>
                  <div class="col-md-2 yes" id="img3">
                    <img id="ImgPreview3"src="" class="preview3" />
                     <input type="button" id="removeImage3" value="x" class="btn-rmv3" />
                     <video width="141" class="video" id="video3" style="display:none" controls>
                     <source src="" id="video_here3">
                        Your browser does not support HTML5 video.
                     </video>
                  </div>
                   </div>

                   <div class="row">
                  <div class="col-md-10">
               <div class="form-group inner-addon right-addon">
                  <!-- <label for="name">Quiz Speed</label> -->
                    <span class="image-upload">
                        <label for="file-input4">
                        <i class="fa fa-paperclip form-control-feedback"></i>
                        </label>
                        <input id="file-input4" name="option3_media" class="file-input" type="file" accept="*"/>
                     </span>
                  <input type="text" class="@error('option3') is-invalid @enderror form-control"  name="option3" placeholder="Option 3" required>
                  </div>
               </div>
                  <div class="col-md-2 yes" id="img4">
                    <img id="ImgPreview4"src="" class="preview4" />
                     <input type="button" id="removeImage4" value="x" class="btn-rmv4" />
                     <video width="141" class="video" id="video4" style="display:none" controls>
                     <source src="" id="video_here4">
                        Your browser does not support HTML5 video.
                     </video>
                  </div>
                   </div>



                   <div class="row">
                  <div class="col-md-10">
               <div class="form-group inner-addon right-addon">
                  <!-- <label for="name">Quiz Speed</label> -->
                    <span class="image-upload">
                        <label for="file-input5">
                        <i class="fa fa-paperclip form-control-feedback"></i>
                        </label>
                        <input id="file-input5" name="option4_media" class="file-input" type="file" accept="*"/>
                     </span>
                  <input type="text" class="@error('option4') is-invalid @enderror form-control"  name="option4" placeholder="Option 4" required>
                  </div>
               </div>
                  <div class="col-md-2 yes" id="img5">
                    <img id="ImgPreview5"src="" class="preview5" />
                     <input type="button" id="removeImage5" value="x" class="btn-rmv5" />
                     <video width="141" class="video" id="video5" style="display:none" controls>
                     <source src="" id="video_here5">
                        Your browser does not support HTML5 video.
                     </video>
                  </div>
                   </div>

                   <div class="form-group inner-addon right-addon">
                    <select class="@error('option3') is-invalid @enderror form-control" required  name="right_option" >
                       <option value="">Correct Option</option>
                       <option value="1">Option 1</option>
                       <option value="2">Option 2</option>
                       <option value="3">Option 3</option>
                       <option value="4">Option 4</option>
                     </select>
                  </div>

                   <div class="row append">
                    <div class="col-md-6">
                    <div class="form-group inner-addon right-addon">
                    <select class="@error('option3') is-invalid @enderror form-control" required  name="domain_id" >
                       <option value="">Domain</option>
                       @foreach($domains as $domain)
                       <option value="{{$domain->id}}">{{$domain->name}}</option>
                       @endforeach
                     </select>
                  </div>
                     </div>

                     <div class="col-md-6">
                    <div class="form-group inner-addon right-addon">
                    <select class="@error('option3') is-invalid @enderror form-control" required  name="subdomain_id" >
                       <option value="">Sub Domain</option>
                       @foreach($subdomains as $subdomain)
                       <option value="{{$subdomain->id}}">{{$subdomain->name}}</option>
                       @endforeach
                     </select>
                  </div>
                     </div>

                     </div>


                   <div class="row append">
                    <div class="col-md-6">
                    <div class="form-group inner-addon right-addon">
                    <select class="@error('option3') is-invalid @enderror form-control" required  name="age_group_id" >
                       <option value="">Age Group</option>
                       @foreach($age_groups as $age_group)
                       <option value="{{$age_group->id}}">{{$age_group->name}}</option>
                       @endforeach
                     </select>
                  </div>
                     </div>

                     <div class="col-md-6">
                    <div class="form-group inner-addon right-addon">
                    <select class="@error('option3') is-invalid @enderror form-control" required  name="diffulcity_level_id" >
                       <option value="">Difficulty Level</option>
                       @foreach($diffulcitylevels as $diffulcitylevel)
                       <option value="{{$diffulcitylevel->id}}">{{$diffulcitylevel->name}}</option>
                       @endforeach
                     </select>
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
<!-- Add Model Ends here -->


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

function readURL(input, imgControlName) {

  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
      var extension = input.files[0]['name'].split('.').pop().toLowerCase();
      var validExtensions = ["jpg","pdf","jpeg","gif","png"];
      // if (validExtensions.indexOf(extension))
      // {
      //    $('.video').show();
      //    $("#ImgPreview1").hide();
      //    $('#img1').removeClass('yes');
      //    var $source = $('#video_here');
      //    $source[0].src = URL.createObjectURL(input.files[0]);
      //    $source.parent()[0].load();

      // }

      var validExtensions2 = ["mp4"];
      if (validExtensions2.indexOf(extension)) {
         // $("#video1").hide();
         // $("#ImgPreview1").show();
         $(imgControlName).attr('src', e.target.result);
      }
    }
    reader.readAsDataURL(input.files[0]);
  }


}
$(document).on('change','#file-input1', function() {

  // add your logic to decide which image control you'll use
  var imgControlName = "#ImgPreview1";
  readURL(this, imgControlName);
  $('.preview1').addClass('it');
  $('.btn-rmv1').addClass('rmv');
});
$(document).on('click','#removeImage1', function(e) {
  e.preventDefault();
  $("#file-input1").val("");
  $("#ImgPreview1").attr("src", "");
  $('.preview1').removeClass('it');
  $('.btn-rmv1').removeClass('rmv');

});


$(document).on('change','#file-input2', function() {

// add your logic to decide which image control you'll use
var imgControlName = "#ImgPreview2";
readURL(this, imgControlName);
$('.preview2').addClass('it');
$('.btn-rmv2').addClass('rmv');
});
$(document).on('click','#removeImage2', function(e) {
e.preventDefault();
$("#file-input2").val("");
$("#ImgPreview2").attr("src", "");
$('.preview2').removeClass('it');
$('.btn-rmv2').removeClass('rmv');

});


$(document).on('change','#file-input3', function() {

// add your logic to decide which image control you'll use
var imgControlName = "#ImgPreview3";
readURL(this, imgControlName);
$('.preview3').addClass('it');
$('.btn-rmv3').addClass('rmv');
});
$(document).on('click','#removeImage3', function(e) {
e.preventDefault();
$("#file-input3").val("");
$("#ImgPreview3").attr("src", "");
$('.preview3').removeClass('it');
$('.btn-rmv3').removeClass('rmv');

});

$(document).on('change','#file-input4', function() {

// add your logic to decide which image control you'll use
var imgControlName = "#ImgPreview4";
readURL(this, imgControlName);
$('.preview4').addClass('it');
$('.btn-rmv4').addClass('rmv');
});
$(document).on('click','#removeImage4', function(e) {
e.preventDefault();
$("#file-input4").val("");
$("#ImgPreview4").attr("src", "");
$('.preview4').removeClass('it');
$('.btn-rmv4').removeClass('rmv');

});


$(document).on('change','#file-input5', function() {

// add your logic to decide which image control you'll use
var imgControlName = "#ImgPreview5";
readURL(this, imgControlName);
$('.preview5').addClass('it');
$('.btn-rmv5').addClass('rmv');
});
$(document).on('click','#removeImage5', function(e) {
e.preventDefault();
$("#file-input5").val("");
$("#ImgPreview5").attr("src", "");
$('.preview5').removeClass('it');
$('.btn-rmv5').removeClass('rmv');

});
    </script>
      @endsection
