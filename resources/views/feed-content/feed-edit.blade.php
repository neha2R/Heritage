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
          Feed Eidt 
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
                        
                        <form  class="col-md-10 mx-auto" method="post" action="{{ route('update-feed-attchment') }}" enctype="multipart/form-data" >
                            <!-- novalidate="novalidate" -->
                            @csrf
                            <input type="hidden" name="feed_content_id" value="{{$data['id']}}" >
                            <div class="form-group">
                                <select name="theme_id" class="@error('theme_id') is-invalid @enderror form-control" required >
                                    <option selected value="{{$data['theme_id']}}">{{$data['theme_name']}}</option>
                                    <option disabled  value> -- Select Theme --</option>

                                    @foreach($themes as $theme)
                                        <option value="{{$theme->id}}">{{$theme->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <select name="domain_id" class="@error('domain_id') is-invalid @enderror form-control" required >
                                <option selected value="{{$data['domain_id']}}">{{$data['domain_name']}}</option>
                                    <option disabled  value> -- Select Domain --</option>
                                    @foreach($domains as $domain)
                                        <option value="{{$domain->id}}">{{$domain->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <select name="feed_id" id="feed_id" class="@error('feed_id') is-invalid @enderror form-control" required >
                                    <option selected value="{{$data['feed_id']}}">{{$data['feed_name']}}</option>
                                    <option disabled  value> -- Select Feed --</option>
                                    @foreach($feeds as $feed)
                                        <option value="{{$feed->id}}">{{$feed->title}}</option>
                                    @endforeach
                                </select>
                            </div>


                           
               
                            <div class="form-group">
                                <label for="tags"># Tags</label> 
                                <input type="text" class="@error('from') is-invalid @enderror form-control" value="{{$data['tags']}}" name="tags" placeholder="# Tags example(heritage,exam,education)" maxlength="100" >
                            </div>
                            @if($feed_type==1)
                            <div class="form-group">
                                <label for="title">External Link</label>
                                <input type="text" class="@error('external_link') is-invalid @enderror form-control" maxlength="50" value="{{$data['external_link']['0']}}" name="external_link[]" placeholder="https://www.google.com/" >
                            </div>
                            @endif
               
                            <div class="form-group">
                                <label for="title">Title</label> 
                                <input type="text" class="@error('title') is-invalid @enderror form-control" value="{{$data['fix_title']}}" maxlength="50" name="fix_title" placeholder="Title" >
                            </div>

                            <div class="form-group">
                                <label for="name" id="duration">Description</label> 
                                    <textarea class="@error('name') is-invalid @enderror form-control"    name="description" placeholder="Description" maxlength="200" id="description" >{{$data['fix_description']}}</textarea>
                            </div>
                            <button class="btn btn-primary" style="float:right">Update Feed content</button>
                            <br>
                            <hr>
                            <br>
                            
                           
                            @if($feed_type == 1)
                                
                                   
                                        <div id="single_post" >
                                            <div class="row">
                                                
                                                    <div class = "col">
                                                         @foreach($data['images_url'] as $image_url)    
                                                             <img src="{{$image_url}}" alt="..." class="img-thumbnail"><br>
                                                          @endforeach
                                                    </div>
                                                
                                                    <div class="col">
                                                        @foreach($data['media_ids'] as $media_id)
                                                         <input type="file" id="files" name="media_name[{{$media_id}}]" accept="image/*" multiple />
                                                         @endforeach
                                                    </div>
                                                    <div class="col">
                                                        @foreach($data['media_ids'] as $media_id)
                                                            <input class="form-check-input" type="checkbox" name="delete_media[{{$media_id}}]" id="{{$media_id}}"/><br>
                                                        @endforeach
                                                    </div>
                                                
                                            </div>
                                        </div>
                                   
                                
                            @endif

                            @if($feed_type == 2)
                            @php $x=1; @endphp
                            @foreach($data['media'] as $key=>$value)
                            <div  class="container">
                                Card {{$x}}
                                <div class="form-group">
                                    <label for="title">Title</label>
                                        <input type="text" class="@error('title') is-invalid @enderror form-control" maxlength="50" name="media[{{$key}}][title]" value="{{$value['title']}}" placeholder="Title" >
                                </div>
                                
                                <div class="form-group">
                                    <label for="name" id="duration">Description</label> 
                                    <textarea class="@error('name') is-invalid @enderror form-control"   name="media[{{$key}}][description]"  placeholder="Description" maxlength="200" id="description" >{{$value['description']}}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="external_link">External Link</label>
                                        <input type="text" class="@error('title') is-invalid @enderror form-control" name="media[{{$key}}][external_link]"  value="{{$value['title']}}" placeholder="https://www.google.com/" >
                                </div>
                            
                                
                                <div class="form-group">
                                    <label for="video_link">Video Link</label>
                                        <input type="text" class="@error('title') is-invalid @enderror form-control" name="media[{{$key}}][video_link]"  value="{{$value['video_link']}}" placeholder="Vidoe Link" >
                                </div>
                                
                                <div class="row">
                                    
                                        <div class = "col">
                                           
                                            @foreach($value['media_name'] as $attachement_id => $media_url)  
                                                @if($value['media_type'][$attachement_id][0]==0)  
                                                <img src="{{$media_url}}" alt="..." class="img-thumbnail"><br>
                                                @endif
                                                @if($value['media_type'][$attachement_id][0]==1)  
                                                    <div class="embed-responsive embed-responsive-16by9">
                                                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/v64KOxKVLVg" allowfullscreen></iframe>
                                                    </div>
                                                    <hr>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-8">
                                                            <img src="{{$value['placholder_image']}}" alt="..." class="img-thumbnail"><br>
                                                        </div>
                                                        <div class=col-4>
                                                            <input type="file" id="files" name="placholder_image[{{$key}}]"  accept="Image/*"  />
                                                        </div>
                                                    </div>
                                                    
                                                @endif
                                                
                                            @endforeach
                                        </div>
                                                
                                         <div class="col">
                                            @foreach($value['media_name'] as $attachement_id => $media_url)
                                                @if($value['media_type'][$attachement_id][0]==0)        
                                                    <input type="file" id="files" name="media[{{$key}}][{{$attachement_id}}]" accept="image/*"  />
                                                @endif  
                                                @if($value['media_type'][$attachement_id][0]==1)
                                                <input type="file" id="files" name="media[{{$key}}][{{$attachement_id}}]" accept="Video/*"  />
                                                @endif
                                            @endforeach
                                        </div>
                                        <div class="col">
                                            @foreach($value['media_name'] as $attachement_id => $media_url)
                                                <input class="form-check-input" type="checkbox" name="media[{{$key}}]['delete_media'][{{$attachement_id}}]" /><br>
                                            @endforeach
                                        </div> 
                                </div>


                            </div> 
                            <hr>
                            <br>
                            @php$x= $x+1; @endphp
                         @endforeach
                            @endif
                            <button type="submit" class="btn btn-primary">Update Feed</button>
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
   

    </script>


      @endsection
