@extends('layouts.app')
@section('content')
@php
use App\QuestionsSetting;
@endphp
<!-- Header Section start here -->
<div class="app-main__outer">
   <div class="app-main__inner">
      <div class="app-page-title">
         <div class="page-title-wrapper">
            <div class="page-title-heading">
               Edit Question
               <div class="page-title-subheading"> </div>
            </div>
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
                  </div>
                  <div class="card-body">
                     <form id="editform" enctype="multipart/form-data" class="col-md-10 mx-auto" method="post" action="{{ route('question.update',$question->id) }}">
                        @method('PUT')
                        @csrf
                        <input type="hidden" name="page" value="{{$page}}"/>
                        <div class="row">
                           <div class="col-md-9">
                              @php $i=1; @endphp
                              <span class="image-upload">
                                 <label for="file-input{{$i}}">
                                    <i class="fa fa-paperclip form-control-feedback file-input{{$i}}"></i>
                                 </label>
                                 <input id="file-input{{$i}}" name="question_media" class="file-input" type="file" myattr="file-input00{{$i}}" accept="*" />
                                 <input type="hidden" name="question_media_old" value="{{$question->question_media}}" />
                                 <input type="hidden" name="question_media_type_old" value="" id="question_media_type_old" />
                                 <input type="hidden" name="question_media_type_edit" value="" id="question_media_type_edit" />
                              </span>
                              <div class="form-group inner-addon right-addon">
                                 <!-- <label for="name">Quiz Speed</label> -->


                                 <textarea type="text" class="@error('question') is-invalid @enderror form-control" name="question" id="question" placeholder="Type a question">{{$question->question}}</textarea>
                                 <!-- <span class="image-upload form-control-feedback">
                                 <label for="file-input">
                                 <i class="fa fa-paperclip" aria-hidden="true"></i>
                                 </label>
                                 <input id="file-input" type="file"/>
                                 </span> -->
                              </div>
                           </div>
                           <div class="col-md-3 yes" id="img1">
                              @php $display = 'style=display:none' @endphp
                              {{-- @if($question->type=='2') --}}
                              <audio controls @if($question->type!='2'){{$display}} @endif id="audiofile-input00{{$i}}" >
                                 <source style="width:100px" src="{{asset('storage/'.$question->question_media)}}" type="audio/mpeg">
                                 Your browser does not support the audio tag.
                              </audio>
                              {{-- @endif
                           @if($question->type=='1') --}}
                              <img @if($question->type!='1'){{$display}} @endif id="file-input00{{$i}}" src="{{asset('storage/'.$question->question_media)}}" class="it preview-show1 preview1" />
                              <!-- <input type="button" id="removeImage6" value="x" class="edit-btn1 btn-rmv1 rmv" /> -->
                              {{-- @endif                        @if($question->type=='3') --}}
                              <video @if($question->type!='2'){{$display}} @endif width="141" class="video" id="videofile-input00{{$i}}" controls>
                                 <source src="{{asset('storage/'.$question->question_media)}}" id="video_here6">
                                 Your browser does not support HTML5 video.
                              </video>
                              {{-- @endif
                           @if($question->type=='0') --}}
                              <img @if($question->type!='0'){{$display}} @endif id="file-input00{{$i}}" src="" class="preview-show1 preview1 it" />
                              <input type="button" id="removeImage6" myattr="file-input00{{$i}}" value="x" class="edit-btn1 btn-rmv1 rmv removeImage6" />
                              {{-- @endif --}}
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-10">
                              <div class="form-group inner-addon right-addon">
                                 <!-- <label for="name">Quiz Speed</label> -->
                                 <!-- <span class="image-upload">
                                 <label for="file-input7">
                                 <i class="fa fa-paperclip form-control-feedback"></i>
                                 </label>
                                 <input id="file-input7" name="option1_media" class="file-input" type="file" accept="*"/>
                                 <input type="hidden" name="option1_media_old" value="{{$question->option1_media}}"/>
                                 </span> -->
                                 <input type="text" value="{{$question->option1}}" class="@error('option1') is-invalid @enderror form-control" name="option1" placeholder="Option 1" required>
                              </div>
                           </div>
                           <!-- <div class="col-md-2 yes" id="img2">
                           <img id="ImgPreview7"src="{{asset('storage/'.$question->option1_media)}}" class="preview-show2 preview2 it" />
                           <input type="button" id="removeImage7" value="x" class="edit-btn2 btn-rmv2 rmv" />
                           <video width="141" class="video" id="video2" style="display:none" controls>
                              <source src="" id="video_here7">
                              Your browser does not support HTML5 video.
                           </video>
                           </div> -->
                        </div>
                        <div class="row">
                           <div class="col-md-10">
                              <div class="form-group inner-addon right-addon">
                                 <!-- <label for="name">Quiz Speed</label> -->
                                 <!-- <span class="image-upload">
                                 <label for="file-input8">
                                 <i class="fa fa-paperclip form-control-feedback"></i>
                                 </label>
                                 <input id="file-input8" name="option2_media" class="file-input" type="file" accept="*"/>
                                 <input type="hidden" name="option2_media_old" value="{{$question->option2_media}}"/>
                                 </span> -->
                                 <input type="text" value="{{$question->option2}}" class="@error('option2') is-invalid @enderror form-control" name="option2" placeholder="Option 2" required>
                              </div>
                           </div>
                           <!-- <div class="col-md-2 yes" id="img3">
                           <img id="ImgPreview8"src="{{asset('storage/'.$question->option2_media)}}" class="preview-show3 preview3 it" />
                           <input type="button" id="removeImage8" value="x" class="edit-btn3 btn-rmv3 rmv" />
                           <video width="141" class="video" id="video3" style="display:none" controls>
                              <source src="" id="video_here8">
                              Your browser does not support HTML5 video.
                           </video>
                           </div> -->
                        </div>
                        <div class="row">
                           <div class="col-md-10">
                              <div class="form-group inner-addon right-addon">
                                 <!-- <label for="name">Quiz Speed</label> -->
                                 <!-- <span class="image-upload">
                                 <label for="file-input9">
                                 <i class="fa fa-paperclip form-control-feedback"></i>
                                 </label>
                                 <input id="file-input9"  name="option3_media" class="file-input" type="file" accept="*"/>
                                 <input type="hidden" name="option3_media_old" value="{{$question->option3_media}}"/>
                                 </span> -->
                                 <input type="text" value="{{$question->option3}}" class="@error('option3') is-invalid @enderror form-control" name="option3" placeholder="Option 3" >
                              </div>
                           </div>
                           <!-- <div class="col-md-2 yes" id="img4">
                           <img id="ImgPreview9"src="{{asset('storage/'.$question->option3_media)}}" class="preview-show4 preview4 it" />
                           <input type="button" id="removeImage9" value="x" class="edit-btn4 btn-rmv4 rmv"  />
                           <video width="141" class="video" id="video4" style="display:none" controls>
                              <source src="" id="video_here9">
                              Your browser does not support HTML5 video.
                           </video>
                           </div> -->
                        </div>
                        <div class="row">
                           <div class="col-md-10">
                              <div class="form-group inner-addon right-addon">
                                 <!-- <label for="name">Quiz Speed</label> -->
                                 <!-- <span class="image-upload">
                                 <label for="file-input10">
                                 <i class="fa fa-paperclip form-control-feedback"></i>
                                 </label>
                                 <input id="file-input10"  name="option4_media" class="file-input" type="file" accept="*"/>
                                 <input type="hidden" name="option4_media_old" value="{{$question->option4_media}}"/>
                                 </span> -->
                                 <input type="text" value="{{$question->option4}}" class="@error('option4') is-invalid @enderror form-control" name="option4" placeholder="Option 4" >
                              </div>
                           </div>
                           <!-- <div class="col-md-2 yes" id="img5">
                           <img id="ImgPreview10"src="{{asset('storage/'.$question->option4_media)}}" class="preview-show5 preview5 it" />
                           <input type="button" id="removeImage10" value="x" class="edit-btn5 btn-rmv5 rmv" />
                           <video width="141" class="video" id="video5" style="display:none" controls>
                              <source src="" id="video_here10">
                              Your browser does not support HTML5 video.
                           </video>
                           </div> -->
                        </div>
                        <div class="form-group inner-addon right-addon">
                           <select class="@error('option3') is-invalid @enderror form-control" required name="right_option">
                              <option value="">Correct Option</option>
                              <option value="1" @if ($question->right_option=='1') selected="selected" @endif>Option 1</option>
                              <option value="2" @if ($question->right_option=='2') selected="selected" @endif>Option 2</option>
                              <option value="3" @if ($question->right_option=='3') selected="selected" @endif>Option 3</option>
                              <option value="4" @if ($question->right_option=='4') selected="selected" @endif>Option 4</option>
                           </select>
                        </div>
                        @php
                        $setting=QuestionsSetting::where('question_id',$question->id)->where('name','parent')->first();
                        @endphp
                        <div class="row append">
                           <div class="col-md-6">
                              <div class="form-group inner-addon right-addon">
                                 <select class="@error('option3') is-invalid @enderror form-control" required name="domain_id">
                                    <option value="">Domain</option>
                                    @foreach($domains as $domain)
                                    <option value="{{$domain->id}}" {{$setting->domain_id==$domain->id?'selected':''}}>{{$domain->name}}</option>
                                    @endforeach
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group inner-addon right-addon">
                                 <select class="@error('option3') is-invalid @enderror form-control" required name="subdomain_id">
                                    <option value="">Sub Domain</option>
                                    @foreach($subdomains as $subdomain)
                                    <option value="{{$subdomain->id}}" {{$setting->subdomain_id==$subdomain->id?'selected':''}}>{{$subdomain->name}}</option>
                                    @endforeach
                                 </select>
                              </div>
                           </div>
                        </div>
                        <div class="row append">
                           <div class="col-md-6">
                              <div class="form-group inner-addon right-addon">
                                 <select class="@error('option3') is-invalid @enderror form-control" required name="age_group_name">
                                    <option value="">Age Group</option>
                                    @foreach($age_groups as $age_group)
                                    <option value="{{$age_group->id}}" {{$setting->age_group_id==$age_group->id?'selected':''}}>{{$age_group->name}}</option>
                                    @endforeach
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group inner-addon right-addon">
                                 <select class="@error('option3') is-invalid @enderror form-control" required name="difficulty_level_name">
                                    <option value="">Difficulty Level</option>
                                    @foreach($diffulcitylevels as $diffulcitylevel)
                                    <option value="{{$diffulcitylevel->id}}" {{$setting->difficulty_level_id==$diffulcitylevel->id?'selected':''}}>{{$diffulcitylevel->name}}</option>
                                    @endforeach
                                 </select>
                              </div>
                           </div>
                        </div>
                        <div class="form-group moreone">
                           @php
                           $settings=QuestionsSetting::where('question_id',$question->id)->where('name','sub')->get();
                           @endphp
                           @if(!empty($settings))
                           @foreach($settings as $setting)
                           <div class="row box-one">
                              <div class="form-group col-md-5">
                                 <select class="@error('option3') is-invalid @enderror form-control" required name="age_group_id[]">
                                    <option value="">Age Group</option>
                                    @foreach($age_groups as $age_group)
                                    <option value="{{$age_group->id}}" {{$setting->age_group_id==$age_group->id?'selected':''}}>{{$age_group->name}}</option>
                                    @endforeach
                                 </select>
                              </div>
                              <div class="form-group col-md-5">
                                 <select class="@error('option3') is-invalid @enderror form-control" required name="difficulty_level_id[]">
                                    <option value="">Difficulty Level</option>
                                    @foreach($diffulcitylevels as $diffulcitylevel)
                                    <option value="{{$diffulcitylevel->id}}" {{$setting->difficulty_level_id==$diffulcitylevel->id?'selected':''}}>{{$diffulcitylevel->name}}</option>
                                    @endforeach
                                 </select>
                              </div>
                              <div class="form-group col-md-2"><button class="btn btn-danger button-remove-one">Remove</button>
                              </div>
                           </div>
                           @endforeach
                           @endif

                        </div>
                        <div class="form-group row">
                           <button class="form-group btn btn-success ml-auto" onclick="addMoreOne()">Add more..</button>
                        </div>
                        <div class="form-group row">
                           <div class="col-md-12">
                              <input type="text" class="form-control" placeholder="Hint" name="hint" value="{{$question->hint}}" />
                           </div>
                        </div>
                        <div class="form-group more">
                           <select class="form-control" name="ques_type" required>
                              <option value="">Select Question type </option>
                              <option @if ($question->ques_type=='1') selected="selected" @endif value="1"> Normal</option>
                              <option @if ($question->ques_type=='2') selected="selected" @endif value="2"> True False</option>
                              <option @if ($question->ques_type=='3') selected="selected" @endif value="3"> Match the following</option>
                           </select>
                        </div>
                        <div class="form-group row">
                           <input type="submit" value="Update" class="btn btn-success ml-auto" />
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
@section('js')
<script src="https://cdn.ckeditor.com/ckeditor5/34.1.0/classic/ckeditor.js"></script>

<script>
   ClassicEditor
      .create(document.querySelector('#question'))
      .catch(error => {
         console.error(error);
      });

   function readURL(input, imgControlName) {

      if (input.files && input.files[0]) {
         var reader = new FileReader();
         reader.onload = function(e) {
            var extension = input.files[0]['name'].split('.').pop().toLowerCase();
            // var validExtensions = ["jpg","pdf","jpeg","gif","png"];

            // if (validExtensions.indexOf(extension))
            // {


            // }
            if (imgControlName == '#ImgPreview1') {
               $('#question_media_type').val(extension);
            }
            if (imgControlName == '#ImgPreview6') {
               $('#question_media_type_edit').val(extension);
            }
            switch (extension) {
               case 'png':
               case 'jpeg':
               case 'jpg':

                  console.log(extension);
                  $("#video1").hide();
                  $(imgControlName).show();
                  $(imgControlName).attr('src', e.target.result);
                  break;
               case 'mp4':
                  // console.log(extension);
                  $('.video').show();
                  $("#ImgPreview1").hide();
                  $('#img1').removeClass('yes');
                  var $source = $('#video1');
                  $source[0].src = URL.createObjectURL(input.files[0]);
                  $source.parent()[0].load();
                  break;

               case 'mp3':
                  $('.video').hide();
                  $("#ImgPreview1").hide();
                  $('#img1').removeClass('yes');
                  $('.audio').show();
                  var $source = $('#audio1');
                  $source[0].src = URL.createObjectURL(input.files[0]);
                  $source.parent()[0].load();
                  break;

               default:
                  $('#divFiles').text('File type: Unknown');
                  break;
            }

            // var validExtensions2 = ["mp4"];
            // if (validExtensions2.indexOf(extension)) {


            //          }
         }
         reader.readAsDataURL(input.files[0]);
      }


   }




   // edit read url 
   function editreadURL(input, imgControlName) {

      if (input.files && input.files[0]) {
         var reader = new FileReader();
         reader.onload = function(e) {
            var extension = input.files[0]['name'].split('.').pop().toLowerCase();
            // var validExtensions = ["jpg","pdf","jpeg","gif","png"];

            // if (validExtensions.indexOf(extension))
            // {


            // }
            if (imgControlName == '#ImgPreview1') {
               $('#question_media_type').val(extension);
            }
            if (imgControlName == '#ImgPreview6') {
               $('#question_media_type_edit').val(extension);
            }
            switch (extension) {
               case 'png':
               case 'jpeg':
               case 'jpg':

                  //  console.log(extension);
                  $('#video' + imgControlName).hide();
                  $('#' + imgControlName).show();
                  $('#' + imgControlName).attr('src', e.target.result);
                  break;
               case 'mp4':

                  $('#video' + imgControlName).show();
                  $('#' + imgControlName).hide();
                  $('#video' + imgControlName).removeAttr('src');
                  $('#img1').removeClass('yes');
                  var $source = $('#video' + imgControlName);

                  console.log($source);
                  $source[0].src = URL.createObjectURL(input.files[0]);
                  $source.parent()[0].load();
                  break;

               case 'mp3':
                  $('.video').hide();
                  $("#ImgPreview1").hide();
                  $('#img1').removeClass('yes');
                  $('.audio').show();
                  var $source = $('#audio1');
                  $source[0].src = URL.createObjectURL(input.files[0]);
                  $source.parent()[0].load();
                  break;

               default:
                  $('#divFiles').text('File type: Unknown');
                  break;
            }

            // var validExtensions2 = ["mp4"];
            // if (validExtensions2.indexOf(extension)) {


            //          }
         }
         reader.readAsDataURL(input.files[0]);
      }


   }






   $(document).on('change', '#file-input1', function() {

      // add your logic to decide which image control you'll use
      var imgControlName = "#ImgPreview1";
      readURL(this, imgControlName);
      $('.preview1').addClass('it');
      $('.btn-rmv1').addClass('rmv');
   });
   $(document).on('click', '#removeImage1', function(e) {
      e.preventDefault();
      $("#file-input1").val("");
      $("#ImgPreview1").attr("src", "");
      // $("#video1").attr("src", " ");
      $(".video").attr("src", "");
      $(".audio").attr("src", "");
      $(".video").hide();
      $(".audio").hide();


      $('.preview1').removeClass('it');
      $('.btn-rmv1').removeClass('rmv');

   });


   $(document).on('change', '#file-input2', function() {

      // add your logic to decide which image control you'll use
      var imgControlName = "#ImgPreview2";
      readURL(this, imgControlName);
      $('.preview2').addClass('it');
      $('.btn-rmv2').addClass('rmv');
   });
   $(document).on('click', '#removeImage2', function(e) {
      e.preventDefault();
      $("#file-input2").val("");
      $("#ImgPreview2").attr("src", "");
      $('.preview2').removeClass('it');
      $('.btn-rmv2').removeClass('rmv');

   });


   $(document).on('change', '#file-input3', function() {

      // add your logic to decide which image control you'll use
      var imgControlName = "#ImgPreview3";
      readURL(this, imgControlName);
      $('.preview3').addClass('it');
      $('.btn-rmv3').addClass('rmv');
   });
   $(document).on('click', '#removeImage3', function(e) {
      e.preventDefault();
      $("#file-input3").val("");
      $("#ImgPreview3").attr("src", "");
      $('.preview3').removeClass('it');
      $('.btn-rmv3').removeClass('rmv');

   });

   $(document).on('change', '#file-input4', function() {

      // add your logic to decide which image control you'll use
      var imgControlName = "#ImgPreview4";
      readURL(this, imgControlName);
      $('.preview4').addClass('it');
      $('.btn-rmv4').addClass('rmv');
   });
   $(document).on('click', '#removeImage4', function(e) {
      e.preventDefault();
      $("#file-input4").val("");
      $("#ImgPreview4").attr("src", "");
      $('.preview4').removeClass('it');
      $('.btn-rmv4').removeClass('rmv');

   });


   $(document).on('change', '#file-input5', function() {

      // add your logic to decide which image control you'll use
      var imgControlName = "#ImgPreview5";
      readURL(this, imgControlName);
      $('.preview5').addClass('it');
      $('.btn-rmv5').addClass('rmv');
   });

   $(document).on('click', '#removeImage5', function(e) {
      e.preventDefault();
      $("#file-input5").val("");
      $("#ImgPreview5").attr("src", "");
      $('.preview5').removeClass('it');
      $('.btn-rmv5').removeClass('rmv');

   });




   //edit

   $(document).on('change', '.file-input', function(e) {

      // add your logic to decide which image control you'll use
      // var imgControlName = "#ImgPreview6";
      var imgControlName = $(this).attr('myattr');
      console.log(imgControlName);
      editreadURL(this, imgControlName);
      $('.preview-show1').addClass('it');
      $('.edit-btn1').addClass('rmv');
   });

   $(document).on('click', '.removeImage6', function(e) {
      e.preventDefault();
      var imgControlName = $(this).attr('myattr');
      console.log(imgControlName);
      $("#" + imgControlName).val("");
      $("#" + imgControlName).attr("src", "");
      $("#" + imgControlName).hide();
      // $("#video"+imgControlName).attr("src", "");
      //  $("#audio"+imgControlName).attr("src", "");
      $('.preview-show1').removeClass('it');
      $('.edit-btn1').removeClass('rmv');



   });


   $(document).on('change', '#file-input7', function() {

      // add your logic to decide which image control you'll use
      var imgControlName = "#ImgPreview7";
      readURL(this, imgControlName);
      $('.preview-show2').addClass('it');
      $('.edit-btn2').addClass('rmv');
   });
   $(document).on('click', '#removeImage7', function(e) {
      e.preventDefault();
      $("#file-input7").val("");
      $("#ImgPreview7").attr("src", "");
      $('.preview-show2').removeClass('it');
      $('.edit-btn2').removeClass('rmv');

   });


   $(document).on('change', '#file-input8', function() {

      // add your logic to decide which image control you'll use
      var imgControlName = "#ImgPreview8";
      readURL(this, imgControlName);
      $('.preview-show3').addClass('it');
      $('.edit-btn3').addClass('rmv');
   });
   $(document).on('click', '#removeImage8', function(e) {
      e.preventDefault();
      $("#file-input8").val("");
      $("#ImgPreview8").attr("src", "");
      $('.preview-show3').removeClass('it');
      $('.edit-btn3').removeClass('rmv');

   });

   $(document).on('change', '#file-input9', function() {

      // add your logic to decide which image control you'll use
      var imgControlName = "#ImgPreview9";
      readURL(this, imgControlName);
      $('.preview-show4').addClass('it');
      $('.edit-btn4').addClass('rmv');
   });
   $(document).on('click', '#removeImage9', function(e) {
      e.preventDefault();
      $("#file-input9").val("");
      $("#ImgPreview9").attr("src", "");
      $('.preview-show4').removeClass('it');
      $('.edit-btn4').removeClass('rmv');

   });


   $(document).on('change', '#file-input10', function() {

      // add your logic to decide which image control you'll use
      var imgControlName = "#ImgPreview10";
      readURL(this, imgControlName);
      $('.preview-show5').addClass('it');
      $('.edit-btn5').addClass('rmv');
   });
   $(document).on('click', '#removeImage10', function(e) {
      e.preventDefault();
      $("#file-input10").val("");
      $("#ImgPreview10").attr("src", "");
      $('.preview-show5').removeClass('it');
      $('.edit-btn5').removeClass('rmv');

   });


   function addMoreOne() {
      $('.moreone').append('<div class="row box-one"><div class="form-group col-md-5"><select class=" form-control" required  name="age_group_id[]" ><option value="">Age Group</option>@foreach($age_groups as $age_group)<option value="{{$age_group->id}}"  >{{$age_group->name}}</option>@endforeach</select></div> <div class="form-group col-md-5"> <select class=" form-control" required  name="difficulty_level_id[]" >     <option value="">Difficulty Level</option>@foreach($diffulcitylevels as $diffulcitylevel) <option value="{{$diffulcitylevel->id}}" >{{$diffulcitylevel->name}}</option>@endforeach</select></div><div class="form-group col-md-2"><button class="btn btn-danger button-remove-one" >Remove</button></div>')
   }

   function addMore() {
      $('.more').append('<div class="row box"><div class="form-group col-md-5"><select class=" form-control" required  name="age_group_id[]" ><option value="">Age Group</option>@foreach($age_groups as $age_group)<option value="{{$age_group->id}}"  >{{$age_group->name}}</option>@endforeach</select></div> <div class="form-group col-md-5"> <select class=" form-control" required  name="difficulty_level_id[]" >     <option value="">Difficulty Level</option>@foreach($diffulcitylevels as $diffulcitylevel) <option value="{{$diffulcitylevel->id}}" >{{$diffulcitylevel->name}}</option>@endforeach</select></div><div class="form-group col-md-2"><button class="btn btn-danger button-remove" >Remove</button></div>')

   }


   $(document).on("click", ".button-remove-one", function() {
      $(this).closest(".box-one").remove();
   });

   $(document).on("click", ".button-remove", function() {
      $(this).closest(".box").remove();
   });

   $(document).on('change', "select[name='domain_id']", function() {

      var domain_id = $(this).val();
      var token = $("input[name='_token']").val();
      $.ajax({
         url: "<?php echo route('select-subdomain') ?>",
         method: 'POST',
         data: {
            domain_id: domain_id,
            _token: token
         },
         success: function(data) {
            $("select[name='subdomain_id'").html('');
            $("select[name='subdomain_id'").html(data.options);
         }
      });
   });
</script>
@endsection