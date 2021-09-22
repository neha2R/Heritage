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
          Edit Feed Media
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
                        {{ __('Edit Feed Media') }}
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
                        
                        <form  class="col-md-10 mx-auto" method="post" action="{{ route('update_feed_media') }}" enctype="multipart/form-data" >
                            <!-- novalidate="novalidate" -->
                            @csrf
                            <input type="hidden" name="feed_content_id" value="{{$feed->feed_content_id}}" >
                            <div class="form-group">
                        
                            
                                <input type="hidden" value="{{$feed->id}}" name="id" />
                            </div>


                           
               
                            <!-- <div class="form-group">
                                <label for="tags"># Tags</label> 
                                <input type="text" class="@error('from') is-invalid @enderror form-control" value="{{$feed->tags}}" name="tags" placeholder="# Tags example(heritage,exam,education)" maxlength="100" >
                            </div>
                             -->
                            
                            <div class="form-group">
                                <label for="title">Title</label> 
                                <input type="text" class="@error('title') is-invalid @enderror form-control" value="{{$feed->title}}" maxlength="50" name="title" placeholder="Title" >
                            </div>

                            <div class="form-group">
                                <label for="name" id="duration">Description</label> 
                                    <textarea class="@error('name') is-invalid @enderror form-control"    name="description" placeholder="Description" maxlength="200" id="description" >{{$feed->description}}</textarea>
                            </div>
                           
                            <br>
                            <hr>
                            <br>
                            
                          
                            <div id="single_post" >
                                <div class="form-group">
                                    <label for="external_link">External Link</label>
                                        <input type="text" class="@error('title') is-invalid @enderror form-control" name="external_link"  value="{{$feed->external_link}}" placeholder="https://www.google.com/" >
                                </div>


                                <div class="form-group">
                                    <label for="external_link">Media Type</label>
                                        <select class="form-control" name="media_type" onchange="show_single(this.value)" >
                                            <option {{$feed->video_link==""?'selected':''}} value="0">Image</option>
                                            <option {{$feed->video_link!=""?'selected':''}} value="1">Video</option>
                                        </select>
                                </div>
                                <div id="image" style="display:{{$feed->video_link==""?'show':'none'}}">
                                <input type="file" name="files[]" multiple class="form-control"/>
                                          
                                      @if(!empty($feed->feed_attachments))
                                       @foreach($feed->feed_attachments as $attachment)
                                         @if($attachment->media_type=='0')
                                          <img src="{{asset('storage/'.$attachment->media_name)}}" width="100px" height="100px"/>
                                           <input type="hidden" name="old_images[]" value="{{$attachment->media_name}}"/>
                                           @endif
                                       @endforeach
                                       @endif
                                </div>
                            <div id="video" style="display:{{$feed->video_link!=""?'show':'none'}}">
                                <div class="form-group">
                                    <label for="video_link">Video Link</label>
                                    
                                        <input type="text" class="@error('title') is-invalid @enderror form-control" name="video_link"   placeholder="Vidoe Link" value="{{$feed->video_link}}">
                                </div>
                                
                                <div class="row">
                                    @php $video=\App\FeedAttachment::where('feed_media_id',$feed->id)->where('media_type','1')->first(); @endphp
                                        <div class = "col">
                                                    <div class="row">
                                                        <div class="col-8">
                                                            <input  class="form-control" type="file"  name="placeholder_image"  accept="Image/*"  />
                                                            <input  class="form-control" type="file"  name="video"  accept="video/*"  />
                                                        
                                                            <input type="hidden" value="{{!empty($video)?$video->media_name:''}}" name="old_video" />
                                                            @if($feed->placholder_image!="")
                                                            <img src="{{$feed->placholder_image!=""?asset('storage/'.$feed->placholder_image):''}}" heigth="100px" width="100px">
                                                            <input type="hidden" value="{{!empty($feed)?$feed->placholder_image:''}}" name="old_placeholder" />
                                                            @endif
                                                          
                                                        </div>
                                                    </div>
                                            
                                        </div>
                                                
                                        
                                </div>
                              
                             </div>    
                          
                                
             
                             <div class="row">
                            <button type="submit" class="btn btn-primary mt-5 ml-auto">Update Feed Media</button>
                          
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

  function show_single(val)
  {
      if(val=='0')
      {
          $('#image').show();
          $('#video').hide();
      }
      else
      {
        $('#image').hide();
        $('#video').show();
      }
  }
 

    </script>


      @endsection
