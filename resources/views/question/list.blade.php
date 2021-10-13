@extends('layouts.app')
@section('content')
@php
use App\QuestionsSetting;
@endphp

@livewireStyles




@livewireScripts
<!-- Bulk Model Ends here -->
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
      // var validExtensions = ["jpg","pdf","jpeg","gif","png"];
      
      // if (validExtensions.indexOf(extension))
      // {
     
       
      // }
      if(imgControlName=='#ImgPreview1')
      {
         $('#question_media_type').val(extension);
      }
      if(imgControlName=='#ImgPreview6')
      {
         $('#question_media_type_edit').val(extension);
      }
      switch (extension) {
            case 'png': case 'jpeg': case 'jpg':
               
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
   if(imgControlName=='#ImgPreview1')
   {
      $('#question_media_type').val(extension);
   }
   if(imgControlName=='#ImgPreview6')
   {
      $('#question_media_type_edit').val(extension);
   }
   switch (extension) {
         case 'png': case 'jpeg': case 'jpg':
            
            //  console.log(extension);
      $('#video'+imgControlName).hide();
      $('#'+imgControlName).show();
      $('#'+imgControlName).attr('src', e.target.result);
      break;
         case 'mp4':
 
      $('#video'+imgControlName).show();
      $('#'+imgControlName).hide();
      $('#video'+imgControlName).removeAttr('src');
      $('#img1').removeClass('yes');
      var $source = $('#video'+imgControlName);
      
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
   // $("#video1").attr("src", " ");
   $(".video").attr("src", "");
    $(".audio").attr("src", "");
     $(".video").hide();
    $(".audio").hide();
  

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




   //edit

   $(document).on('change','.file-input', function(e) {

   // add your logic to decide which image control you'll use
   // var imgControlName = "#ImgPreview6";
   var imgControlName = $(this).attr('myattr');
   console.log(imgControlName);
   editreadURL(this, imgControlName);
   $('.preview-show1').addClass('it');
   $('.edit-btn1').addClass('rmv');
   });

   $(document).on('click','.removeImage6', function(e) {
   e.preventDefault();
   var imgControlName = $(this).attr('myattr');
console.log(imgControlName);
    $("#"+imgControlName).val("");
   $("#"+imgControlName).attr("src","");
    $("#"+imgControlName).hide();
   // $("#video"+imgControlName).attr("src", "");
   //  $("#audio"+imgControlName).attr("src", "");
   $('.preview-show1').removeClass('it');
   $('.edit-btn1').removeClass('rmv');

   

   });


   $(document).on('change','#file-input7', function() {

   // add your logic to decide which image control you'll use
   var imgControlName = "#ImgPreview7";
   readURL(this, imgControlName);
   $('.preview-show2').addClass('it');
   $('.edit-btn2').addClass('rmv');
   });
   $(document).on('click','#removeImage7', function(e) {
   e.preventDefault();
   $("#file-input7").val("");
   $("#ImgPreview7").attr("src", "");
   $('.preview-show2').removeClass('it');
   $('.edit-btn2').removeClass('rmv');

   });


   $(document).on('change','#file-input8', function() {

   // add your logic to decide which image control you'll use
   var imgControlName = "#ImgPreview8";
   readURL(this, imgControlName);
   $('.preview-show3').addClass('it');
   $('.edit-btn3').addClass('rmv');
   });
   $(document).on('click','#removeImage8', function(e) {
   e.preventDefault();
   $("#file-input8").val("");
   $("#ImgPreview8").attr("src", "");
   $('.preview-show3').removeClass('it');
   $('.edit-btn3').removeClass('rmv');

   });

   $(document).on('change','#file-input9', function() {

   // add your logic to decide which image control you'll use
   var imgControlName = "#ImgPreview9";
   readURL(this, imgControlName);
   $('.preview-show4').addClass('it');
   $('.edit-btn4').addClass('rmv');
   });
   $(document).on('click','#removeImage9', function(e) {
   e.preventDefault();
   $("#file-input9").val("");
   $("#ImgPreview9").attr("src", "");
   $('.preview-show4').removeClass('it');
   $('.edit-btn4').removeClass('rmv');

   });


   $(document).on('change','#file-input10', function() {

   // add your logic to decide which image control you'll use
   var imgControlName = "#ImgPreview10";
   readURL(this, imgControlName);
   $('.preview-show5').addClass('it');
   $('.edit-btn5').addClass('rmv');
   });
   $(document).on('click','#removeImage10', function(e) {
   e.preventDefault();
   $("#file-input10").val("");
   $("#ImgPreview10").attr("src", "");
   $('.preview-show5').removeClass('it');
   $('.edit-btn5').removeClass('rmv');

   });


   function addMoreOne(){
   $('.moreone').append('<div class="row box-one"><div class="form-group col-md-5"><select class=" form-control" required  name="age_group_id[]" ><option value="">Age Group</option>@foreach($age_groups as $age_group)<option value="{{$age_group->id}}"  >{{$age_group->name}}</option>@endforeach</select></div> <div class="form-group col-md-5"> <select class=" form-control" required  name="difficulty_level_id[]" >     <option value="">Difficulty Level</option>@foreach($diffulcitylevels as $diffulcitylevel) <option value="{{$diffulcitylevel->id}}" >{{$diffulcitylevel->name}}</option>@endforeach</select></div><div class="form-group col-md-2"><a href="#" class="btn btn-danger button-remove-one" >Remove</a></div>')
   }

   function addMore(){
   $('.more').append('<div class="row box"><div class="form-group col-md-5"><select class=" form-control" required  name="age_group_id[]" ><option value="">Age Group</option>@foreach($age_groups as $age_group)<option value="{{$age_group->id}}"  >{{$age_group->name}}</option>@endforeach</select></div> <div class="form-group col-md-5"> <select class=" form-control" required  name="difficulty_level_id[]" >     <option value="">Difficulty Level</option>@foreach($diffulcitylevels as $diffulcitylevel) <option value="{{$diffulcitylevel->id}}" >{{$diffulcitylevel->name}}</option>@endforeach</select></div><div class="form-group col-md-2"><a href="#" class="btn btn-danger button-remove" >Remove</a></div>')

   }


   $(document).on("click", ".button-remove-one", function() {
    $(this).closest(".box-one").remove();
   });

   $(document).on("click", ".button-remove", function() {
    $(this).closest(".box").remove();
   });




</script>
@endsection
