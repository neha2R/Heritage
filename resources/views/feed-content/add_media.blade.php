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
          Add More Feed Media
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
                        {{ __('Add More Feed Media') }}
                            <!-- <a href="{{ route('domain.create') }}"  class="float-right mb-2 mr-2 btn-transition btn btn-outline-primary">Primary
                            </a> -->

                         
                    </div>

                    <div class="card-body">
                        
                        <form  class="col-md-10 mx-auto" method="post" action="{{ route('add_feed_media') }}" enctype="multipart/form-data" >
                            <!-- novalidate="novalidate" -->
                            @csrf
                          
                            <div class="form-group">
                        
                            
                                <input type="hidden" value="{{$id}}" name="feed_content_id" />
                            </div>


            
                            
                            <div class="form-group">
                                <label for="title">Title</label> 
                                <input type="text" class="@error('title') is-invalid @enderror form-control"  maxlength="50" name="title" placeholder="Title" required>
                            </div>

                            <div class="form-group">
                                <label for="name" id="duration">Description</label> 
                                    <textarea class="@error('name') is-invalid @enderror form-control"    name="description" placeholder="Description" maxlength="200" id="description" required ></textarea>
                            </div>
                           
                            <br>
                            <hr>
                            <br>
                            
                          
                            <div id="single_post" >
                                <div class="form-group">
                                    <label for="external_link">External Link</label>
                                        <input type="text" class="@error('title') is-invalid @enderror form-control" name="external_link"   placeholder="https://www.google.com/"  required>
                                </div>


                                <div class="form-group">
                                    <label for="external_link" required>Media Type</label>
                                        <select class="form-control" name="media_type" onchange="show_single(this.value)" >
                                        <option  value="">Select Any</option>
                                            <option  value="0">Image</option>
                                            <option  value="1">Video</option>
                                        </select>
                                </div>
                                <div id="image" style="display:none">
                                <input type="file" name="files[]" multiple class="form-control"/>
                                          
                                </div>
                            <div id="video" style="display:none">
                                <div class="form-group">
                                    <label for="video_link">Video Link</label>
                                    
                                        <input type="text" class="@error('title') is-invalid @enderror form-control" name="video_link"   placeholder="Vidoe Link" value="">
                                </div>
                                
                                <div class="row">
                                   
                                        <div class = "col">
                                                    <div class="row">
                                                        <div class="col-8">
                                                            <input  class="form-control" type="file"  name="placeholder_image"  accept="Image/*"  />
                                                            <input  class="form-control" type="file"  name="video"  accept="video/*"  />
                                                            
                                                          
                                                        </div>
                                                    </div>
                                            
                                        </div>
                                                
                                        
                                </div>
                              
                             </div>    
                          
                                
             
                             <div class="row">
                            <button type="submit" class="btn btn-primary mt-5 ml-auto">Add Feed Media</button>
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
