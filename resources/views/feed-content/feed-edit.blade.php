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
      z-index: 9999;
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
               Edit Feed
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
                        {{ __('Edit Feed') }}
                        <!-- <a href="{{ route('domain.create') }}"  class="float-right mb-2 mr-2 btn-transition btn btn-outline-primary">Primary
                        </a> -->
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
                        <form class="col-md-10 mx-auto" method="post" action="{{ route('update-feed-attchment') }}" enctype="multipart/form-data">
                           <!-- novalidate="novalidate" -->
                           @csrf
                           <input type="hidden" name="feed_content_id" value="{{$feed->id}}">

                           <input type="hidden" name="page" value="{{$page}}">

                           <div class="form-group">
                              <select name="theme_id" class="@error('theme_id') is-invalid @enderror form-control" required>
                                 <option disabled value> -- Select Theme --</option>
                                 @foreach($themes as $theme)
                                 <option value="{{$theme->id}}" {{$theme->id==$feed->theme_id?'selected':''}}>{{$theme->title}}</option>
                                 @endforeach
                              </select>
                           </div>
                           <div class="form-group">
                              <select name="domain_id" class="@error('domain_id') is-invalid @enderror form-control" required>
                                 <option disabled value> -- Select Domain --</option>
                                 @foreach($domains as $domain)
                                 <option value="{{$domain->id}}" {{$domain->id==$feed->domain_id?'selected':''}}>{{$domain->name}}</option>
                                 @endforeach
                              </select>
                           </div>
                           <div class="form-group">
                              <input type="text" value="{{$feed->feedtype->title}}" disabled class="form-control" />
                              <input type="hidden" value="{{$feed->feed_id}}" name="type" />
                           </div>
                           <div class="form-group">
                              <label for="tags"># Tags</label>
                              <input type="text" class="@error('from') is-invalid @enderror form-control" value="{{$feed->tags}}" name="tags" placeholder="# Tags example(heritage,exam,education)" maxlength="100">
                           </div>
                           <div class="form-group">
                              <label for="title">Title</label>
                              <input type="text" class="@error('title') is-invalid @enderror form-control" value="{{$feed->title}}" maxlength="50" name="fix_title" placeholder="Title">
                           </div>
                           <div class="form-group">
                              <label for="name" id="duration">Description</label>
                              <textarea class="@error('name') is-invalid @enderror form-control" name="description" placeholder="Description" id="description">{{$feed->description}}</textarea>
                           </div>
                           <br>
                           <hr>
                           <br>
                           @if($feed->feed_id=='1')
                           <div id="single_post">
                              <div class="form-group">
                                 <label for="external_link">External Link</label>
                                 <input type="text" class="@error('external_link') is-invalid @enderror form-control" name="external_link" value="{{($feed->feed_medium) ? $feed->feed_medium->external_link : ''}}" placeholder="https://www.google.com/">
                              </div>
                              <div class="form-group">
                                 <label for="external_link">Media Type</label>
                                 <select class="form-control" name="media_type" onchange="show_single(this.value)">
                                    <option {{($feed->feed_medium) ? $feed->feed_medium->video_link==""?'selected':'':''}} value="0">Image</option>
                                    <option {{($feed->feed_medium) ? $feed->feed_medium->video_link!=""?'selected':'' :''}} value="1">Video</option>
                                 </select>
                              </div>
                              <div id="single_image" style="display:{{($feed->feed_medium) ? $feed->feed_medium->video_link==""?'show':'none' :''}}">
                                 <div class="row">
                                    <input type="file" name="files[]" multiple class="form-control edit_files" />
                                    @if(!empty($feed->feed_medium->feed_attachments))
                                    @foreach($feed->feed_medium->feed_attachments as $attachment)
                                    <span class="pip"> <input type="button" value="x" class="remove_edit"> <img src="{{asset('storage/'.$attachment->media_name)}}" width="100px" height="100px" />
                                       <input type="hidden" name="old_images[]" value="{{$attachment->media_name}}" /> </span>
                                    @endforeach
                                    @endif
                                 </div>
                              </div>
                              <div id="single_video" style="display:{{($feed->feed_medium) ? $feed->feed_medium->video_link!=""?'show':'none' :''}}">
                                 <div class="form-group">
                                    <label for="video_link">Video Link</label>
                                    <input type="text" class="@error('title') is-invalid @enderror form-control" name="video_link" value="" placeholder="Vidoe Link" value="{{!empty($feed->feed_medium)?$feed->feed_medium->video_link:''}}">
                                 </div>
                                 <div class="row">
                                    @php $mediaid = ($feed->feed_medium) ? $feed->feed_medium->id : ''; @endphp
                                    @php $video=\App\FeedAttachment::where('feed_media_id',$mediaid)->where('media_type','1')->first(); @endphp
                                    <div class="col">
                                       <div class="row">
                                          <div class="col-8">
                                             <input class="form-control" type="file" name="placeholder_image" accept="Image/*" />
                                             <input class="form-control" type="file" name="video" accept="video/*" />
                                             {{($feed->feed_medium) ? $feed->feed_medium->feed_attachement :''}}
                                             <input type="hidden" value="{{!empty($video)?$video->media_name:''}}" name="old_video" />
                                             @php $plaimage = ($feed->feed_medium) ? $feed->feed_medium->placholder_image :''; @endphp
                                             @if($plaimage)
                                             <img src="{{$feed->feed_medium->placholder_image!=""?asset('storage/'.$feed->feed_medium->placholder_image):''}}" heigth="100px" width="100px">
                                             <input type="hidden" value="{{!empty($feed->feed_medium)?$feed->feed_medium->placholder_image:''}}" name="old_placeholder" />
                                             @endif
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           @endif
                           @if($feed->feed_id=='2')
                           <div class="container">
                              <input type="hidden" id="total_child" value="{{count($feed->feed_media)}}" />
                              <table class="table">
                                 @foreach($feed->feed_media as $key=>$feeds)
                                 <tr>
                                    <td>
                                       <h3>Card {{$key+1}}</H3>
                                    </td>
                                    <td style="text-align:right">
                                       <a type="button" class="edit-btn-bg btn mr-2 mb-2 btn-primary" href="{{route('edit_media',['id'=>$feeds->id])}}"><i class="fas fa-pencil-alt"></i></a>
                                    </td>
                                 </tr>
                                 @endforeach
                              </table>
                              @endif
                              @if($feed->feed_id=='2')
                              <div class="row">
                                 <!-- <a class="edit-btn-bg btn mr-2 mb-2 btn-success ml-auto" href="{{route('add_media',['id'=>$feed->id])}}">Add More</a> -->
                              </div>
                              @endif
                              @if($feed->feed_id=='3')
                              <div class="container">
                                 <input type="hidden" id="total_child" value="{{count($feed->feed_media)}}" />
                                 <table class="table">
                                    @foreach($feed->feed_media as $key=>$feeds)
                                    <tr>
                                       <td>
                                          <h3>Card {{$key+1}}</H3>
                                       </td>
                                       <td style="text-align:right">
                                          <a type="button" class="edit-btn-bg btn mr-2 mb-2 btn-primary" href="{{route('edit_media',['id'=>$feeds->id])}}"><i class="fas fa-pencil-alt"></i></a>
                                       </td>
                                    </tr>
                                    @endforeach
                                 </table>
                                 @endif
                                 @if($feed->feed_id=='3')
                                 <div class="row">
                                    <!-- <a class="edit-btn-bg btn mr-2 mb-2 btn-success ml-auto" href="{{route('add_media',['id'=>$feed->id])}}">Add More</a> -->
                                 </div>
                                 @endif
                                 <div class="row">
                                    <button type="submit" class="btn btn-primary ml-auto">Update Feed</button>
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
   <script>
      function show_div(val, id) {
         if (val == '0') {

            $('#image_' + id).show();
            $('#video_' + id).hide();
         } else {
            $('#image_' + id).hide();
            $('#video_' + id).show();
         }
      }

      function show_single(val) {
         if (val == '0') {
            $('#image').show();
            $('#single_video').hide();
         } else {
            $('#single_image').hide();
            $('#single_video').show();
         }
      }
      var new_post = 0;

      function add_more() {
         if (new_post == '4') {
            $('#add_more').hide();
            alert("You can only add 4 new more post.");
         } else {
            val = $('#total_child').val();
            var post = "";
            //   val=parseInt(val)+1;
            post += '<h3 class="mt-3"> Feed Post ' + val + '</h3>' +
               '<div class="form-group">' +
               '<label for="title">Title</label>' +
               "<input type=\"text\" class=\"@error('title') is-invalid @enderror form-control\" maxlength=\"50\" name=\"card[" + val + "][title]\" value=\"\" placeholder=\"Title\" >" +
               '</div>' +
               '<div class="form-group">' +
               '<label for="name" id="duration">Description</label> ' +
               "<textarea class=\"@error('name') is-invalid @enderror form-control\"   name=\"card[" + val + "][description]\"  placeholder=\"Description\" maxlength=\"200\" id=\"description\" ></textarea>" +
               '</div>' + '<div class="form-group">' +
               '<label for="external_link">External Link</label>' +
               "<input type=\"text\" class=\"@error('title') is-invalid @enderror form-control\"  name=\"card[" + val + "][link]\"  value=\"\" placeholder=\"https://www.google.com/\" >" +
               '</div>' +
               '<div class="form-group">' +
               '<label for="external_link">Media Type</label>' +
               "<select class=\"form-control\"  name=\"card[" + val + "][media_type]\" onchange=\"show_div(this.value,'+val+')\">" +
               '<option  value="0">Image</option>' +
               '<option value="1">Video</option>' +
               '</select>' +
               '</div>' +

               '<div id="image_' + val + '" style="display:none">' +
               "<input type=\"file\"  name=\"card[" + val + "][files]\" multiple class=\"form-control\"/>" +
               '<div class="row">' +
               '</div>' +

               '</div>' +
               '<div id="video_' + val + '" style="display:none">' +

               '<div class="form-group">' +
               '<label for="video_link">Video Link</label>' +
               "<input type=\"text\" class=\"@error('title') is-invalid @enderror form-control\" name=\"card[" + val + "][video_link]\" value=\"\" placeholder=\"Vidoe Link\" >" +
               '</div>' +
               '<div class="row">' +
               '<div class = "col">' +
               '<div class="embed-responsive embed-responsive-16by9">' +
               '<iframe class="embed-responsive-item" src="https://www.youtube.com/embed/v64KOxKVLVg" allowfullscreen></iframe>' +
               '</div>' +
               '<hr>' +
               '<br>' +
               '<div class="row">' +
               '<div class="col-8">' +
               '<img src="" alt="..." class="img-thumbnail"><br>' +
               '</div>' +
               '<div class=col-4>' +
               '<input type="file" id="files" name=""  accept="Image/*"  />' +
               '</div>' +
               '</div>' +

               '</div>' +

               '<div class="col">' +

               '<input type="file" id="files" name="media[]" accept="image/*"  />' +

               '<input type="file" id="files" name="media[]" accept="Video/*"  />' +

               '</div>' +
               '<div class="col">' +

               '<input class="form-check-input" type="checkbox" name="" /><br>' +

               '</div>' +
               '</div>';


            $('#append_child').append(post);
            $('#total_child').val(val);
            new_post = new_post + 1;
         }
      }

      $(document).on('click', ".remove_edit", function() {
         $(this).parent(".pip").remove();
         $(this).val('');


      });
   </script>
   @endsection