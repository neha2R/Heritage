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
                        
                        <form id="signupForm" class="col-md-10 mx-auto" method="post" action="{{ route('feed-content.store') }}" enctype="multipart/form-data" >
                            <!-- novalidate="novalidate" -->
                            @csrf
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

                            <div class="form-group">
                                <label for="title">External Link</label>
                                <input type="text" class="@error('external_link') is-invalid @enderror form-control" maxlength="50" value="{{$data['external_link']['0']}}" name="external_link[]" placeholder="https://www.google.com/" >
                            </div>
               
                            <div class="form-group">
                                <label for="title">Title</label> 
                                <input type="text" class="@error('title') is-invalid @enderror form-control" value="{{$data['fix_title']}}" maxlength="50" name="fix_title" placeholder="Title" >
                            </div>

                            <div class="form-group">
                                <label for="name" id="duration">Description</label> 
                                    <textarea class="@error('name') is-invalid @enderror form-control"    name="description[]" placeholder="Description" maxlength="200" id="description" >{{$data['fix_description']}}</textarea>
                            </div>
                            <button class="btn btn-primary" style="float:right">Update Feed content</button>
                            <br>
                            <hr>
                            <br>
                            
                            <form action="#" method="Post" >
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
                                                         <input type="file" id="files" name="media_name[{{$media_id}}][]" accept="image/*" multiple />
                                                         @endforeach
                                                    </div>
                                                    <div class="col">
                                                        @foreach($data['media_ids'] as $media_id)
                                                            <input class="form-check-input" type="checkbox" id="{{$media_id}}"/><br>
                                                        @endforeach
                                                    </div>
                                                
                                            </div>
                                        </div>
                                   
                                
                            @endif

                            @if($feed_type == 2)
                            <div id="modules" >
                                <div class="form-group row">
                                    <div class="field col" >
                                        <h3>Upload your Videos</h3> 
                                        <input type="file" id="videos" name="media_name_[0][]"  accept="video/*" multiple  />
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
                            </div>
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
