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

   .remove_edit {
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

   .remove_edit:hover {
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
                     <div class="card-header display-inline mt-3">

                        <form method="GET" action="feed-content">
                           <button type="submit" class=" float-right btn mr-2 mb-2 btn-primary"> <i class="fa fa-search"></i> Search</button>
                           <input type="text" name="search" required class=" float-right  mr-2 mb-2 form-control " style="width:200px" />
                        </form>
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
                           <table class="mb-0 table table-striped text-left">
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
                                 @if($feedContents->count() < 1) <tr class="text-center">
                                    <td colspan="7">No Record Found</td>
                                    </tr>
                                    @endif
                                    @foreach($feedContents as $key=>$feedContent)
                                    @php $i = $feedContents->perPage() * ($feedContents->currentPage() - 1); $key++;@endphp
                                    <tr>
                                       <th scope="row">{{$i+=$key}}</th>
                                       <th scope="row">{{$feedContent->title}}</th>
                                       <th scope="row">{{!empty($feedContent->feedtype)?$feedContent->feedtype->title:'N/A'}}</th>
                                       <td>{{$feedContent->theme->title}}</td>
                                       <td><label class="switch">
                                             @if($feedContent->status=='1')
                                             @php $status='checked'; @endphp
                                             @else
                                             @php $status=''; @endphp
                                             @endif
                                             <input {{$status}} type="checkbox" class="status" feedcontent_id="{{$feedContent->id}}">
                                             <span class="slider round"></span>
                                          </label>

                                       </td>
                                       @php $page =$feedContents->currentPage(); @endphp
                                       <td><a href="{{route('get_feed_content_by_id',['id'=>$feedContent->id,'page'=>$page])}}" class="edit-btn-bg btn mr-2 mb-2 btn-primary"><i class="fas fa-pencil-alt"></i></button>
                                       </td>
                                       <td>
                                          <form class="delete" action="{{route('feed-content.destroy',$feedContent->id)}}" method="POST">
                                             @method('DELETE')
                                             @csrf
                                             <button type="submit" class=" btn mr-2 mb-2 btn-primary "><i class="far fa-trash-alt"></i></button>
                                          </form>
                                       </td>
                                    </tr>
                                    @endforeach

                              </tbody>

                           </table>
                           <div style="float:right">
                              {{$feedContents->withQueryString()->links()}}
                           </div>
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
                  <div class=row>
                     <div class="col-6" style="text-align:center">
                        <button type="button" class="btn btn-secondary" data-toggle="modal" id="add_new_card_button" data-target=".add-model">Add New Card</button>
                     </div>
                     <div class="col-6" style="text-align:center">

                        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="add_existing_card_button" data-target="#add_existing_card_model">Add Existing Card</button>
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
                  <form id="signupForm" class="col-md-10 mx-auto" method="post" action="{{ route('feed-content.store') }}" enctype="multipart/form-data">
                     <!-- novalidate="novalidate" -->
                     @csrf

                     <div class="form-group">
                        <select name="theme_id" class="@error('theme_id') is-invalid @enderror form-control" required>
                           <option disabled selected value>-- Select Theme --</option>
                           @foreach($themes as $theme)
                           <option value="{{$theme->id}}">{{$theme->title}}</option>
                           @endforeach
                        </select>
                     </div>
                     <div class="form-group">
                        <select name="domain_id" class="@error('domain_id') is-invalid @enderror form-control" required>
                           <option disabled selected value> -- Select Domain --</option>
                           @foreach($domains as $domain)
                           <option value="{{$domain->id}}">{{$domain->name}}</option>
                           @endforeach
                        </select>
                     </div>
                     <div class="form-group">
                        <select name="feed_id" onchange="showModules(this.value)" class="@error('feed_id') is-invalid @enderror form-control" required>
                           <!-- <option>Type</option> -->
                           <option onchnage disabled selected value> -- Select Feed Type -- </option>
                           @foreach($feeds as $feed)
                           <option value="{{$feed->id}}" onchnage>{{$feed->title}}</option>
                           @endforeach
                        </select>
                     </div>

                     <div class="form-group">
                        <label for="tags"># Tags</label>
                        <input type="text" class="@error('from') is-invalid @enderror form-control" name="tags" placeholder="# Tags example(heritage,exam,education)" maxlength="100" required>
                     </div>

                     <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="@error('title') is-invalid @enderror form-control" maxlength="50" name="title" placeholder="Title">
                     </div>



                     <div class="form-group">
                        <label for="name" id="duration">Description</label>
                        <textarea class="@error('name') is-invalid @enderror form-control" name="description" placeholder="Description" id="description"></textarea>
                     </div>




                     <div id="single_post" style="display:none">
                        <div class="form-group">
                           <label for="external_link">External Link</label>
                           <input type="text" class="@error('external_link') is-invalid @enderror form-control" name="external_link" placeholder="https://www.google.com/">
                        </div>
                        <div class="form-group">
                           <select name="media_type" id="type_add" class="@error('type') is-invalid @enderror form-control " onchange="showType(this.value)">
                              <!-- <option>Type</option> -->
                              <!-- <option value=""> -- Select Media Type -- </option> -->
                              <option value="0">Image</option>
                              <option value="1">Video</option>
                           </select>
                        </div>
                        <div class="form-group" id="myimage1">
                           <div class="field" align="left">
                              <label class="img-label">Upload images</label>
                              <input type="file" id="files_1" name="media_name[]" accept="image/*" multiple />
                           </div>
                        </div>

                        <div class="form-group row " id="myvideo1" style="display:none">
                           <div class="field col">
                              <label class="img-label label0">Upload Videos</label>
                              <input type="file" id="videos_1" name="media_video" accept="video/*" multiple />
                           </div>
                           <div class="col-md-6">
                              <label>Video Link</label>
                              <input type="text" class="@error('video_link') is-invalid @enderror form-control" maxlength="50" name="video_link" placeholder="https://www.youtube.com/">
                           </div>

                           <div id="placeholder_image">
                              <div class="form-group row">
                                 <div class="field col">
                                    <label>Placeholder Image for Video</label>
                                    <input type="file" id="palceholder_image_1" name="placeholder_image" accept="image/*" />
                                 </div>
                              </div>
                           </div>

                        </div>
                        <!-- <div class="form-group">
                                 <div class="field" align="left">
                                    <label class="img-label">Upload images</label> 
                                    <input type="file" id="files" name="media_name[]" accept="image/*" multiple   />
                                 </div>
                           </div> -->
                     </div>

                     <div id="collection" style="display:none">

                        <div class="form-group">
                           <div class="form-group">
                              <label for="title">Title</label>
                              <input type="text" class="@error('title') is-invalid @enderror form-control" name="card[0][title1]" maxlength="50" name="title" placeholder="Title">
                           </div>



                           <div class="form-group">
                              <label for="name" id="duration">Description</label>
                              <textarea class="@error('name') is-invalid @enderror form-control" name="card[0][description1]" name="description" placeholder="Description" id="description">
                   </textarea>
                           </div>
                           <div class="form-group">
                              <label for="title">External Link</label>
                              <input type="text" class="@error('external_link') is-invalid @enderror form-control" maxlength="50" name="card[0][external_link1]" placeholder="https://www.google.com/">
                           </div>
                           <select name="type[]" id="type_collection" mytext="label0" myimage="myimage0" myvideo="myvideo0" class="@error('type') is-invalid @enderror form-control type">
                              <!-- <option>Type</option> -->
                              <!-- <option value=""> -- Select Media Type -- </option> -->
                              <option value="0">Image</option>
                              <option value="1">Video</option>
                           </select>
                        </div>
                        <div class="form-group" id="myimage_collection">
                           <div class="field" align="left">
                              <label class="img-label">Upload images</label>
                              <input type="file" id="files_collection" name="card[0][media_video1][]" accept="image/*" multiple />
                           </div>
                        </div>

                        <div class="form-group row " id="myvideo_collection" style="display:none">
                           <div class="field col">
                              <label class="img-label label0">Upload Videos</label>
                              <input type="file" id="videos_collection" name="card[0][media_video1][]" accept="video/*" multiple />
                           </div>
                           <div class="col-md-6">
                              <label>Video Link</label>
                              <input type="text" class="@error('video_link') is-invalid @enderror form-control" maxlength="50" name="card[0][video_link1]" placeholder="https://www.youtube.com/">
                           </div>

                           <div id="placeholder_image">
                              <div class="form-group row">
                                 <div class="field col">
                                    <label>Placeholder Image for Video</label>
                                    <input type="file" id="palceholder_image" name="card[0][placeholder_image1]" accept="image/*" />
                                 </div>
                              </div>
                           </div>

                        </div>
                        <!-- ===== Placholder Image for Video ================ -->



                        <div id="append_collection"></div>
                        <button type="button" id="add_more_post_collection" class="btn btn-sm btn-success float-right">Add more post</button>
                     </div>
                     <div id="modules" style="display:none">
                        <div class="form-group">
                           <div class="form-group">
                              <label for="title">Title</label>
                              <input type="text" class="@error('title') is-invalid @enderror form-control" name="card[0][title]" maxlength="50" name="title" placeholder="Title">
                           </div>



                           <div class="form-group">
                              <label for="name" id="duration">Description</label>
                              <textarea class="@error('name') is-invalid @enderror form-control" name="card[0][description]" name="description" placeholder="Description" id="description">
                   </textarea>
                           </div>
                           <div class="form-group">
                              <label for="title">External Link</label>
                              <input type="text" class="@error('external_link') is-invalid @enderror form-control" maxlength="50" name="card[0][external_link]" placeholder="https://www.google.com/">
                           </div>
                           <div class="form-group">
                              <select name="type[]" id="type" mytext="label0" myimage="myimage0" myvideo="myvideo0" class="@error('type') is-invalid @enderror form-control type">
                                 <!-- <option>Type</option> -->
                                 <!-- <option value=""> -- Select Media Type -- </option> -->
                                 <option value="0">Image</option>
                                 <option value="1">Video</option>
                              </select>
                           </div>
                           <div class="form-group" id="myimage0">
                              <div class="field" align="left">
                                 <label class="img-label">Upload images</label>
                                 <input type="file" id="files" name="card[0][media_video][]" accept="image/*" multiple />
                              </div>
                           </div>

                           <div class="form-group row " id="myvideo0" style="display:none">
                              <div class="field col">
                                 <label class="img-label label0">Upload Videos</label>
                                 <input type="file" id="videos" name="card[0][media_video][]" accept="video/*" multiple />
                              </div>
                              <div class="col-md-6">
                                 <label>Video Link</label>
                                 <input type="text" class="@error('video_link') is-invalid @enderror form-control" maxlength="50" name="card[0][video_link]" placeholder="https://www.youtube.com/">
                              </div>

                              <div id="placeholder_image">
                                 <div class="form-group row">
                                    <div class="field col">
                                       <label>Placeholder Image for Video</label>
                                       <input type="file" id="palceholder_image" name="card[0][placeholder_image]" accept="image/*" />
                                    </div>
                                 </div>
                              </div>

                           </div>
                           <!-- ===== Placholder Image for Video ================ -->



                           <div id="append"></div>
                           <button type="button" id="add_more_post" class="btn btn-sm btn-success float-right">Add more post</button>
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
                  <form id="signupForm_edit" class="col-md-10 mx-auto" method="post" action="{{ route('feed-content.update',$feedContent->id) }}" enctype="multipart/form-data">
                     <!-- novalidate="novalidate" -->
                     @method('PUT')
                     @csrf
                     <div class="form-group">
                        <select name="theme_id" class="@error('theme_id') is-invalid @enderror form-control" required>
                           <option disabled selected value>-- Select Theme --</option>
                           @foreach($themes as $theme)
                           <option value="{{$theme->id}}" {{$feedContent->theme_id==$theme->id?'selected':''}}>{{$theme->title}}</option>
                           @endforeach
                        </select>
                     </div>
                     <div class="form-group">
                        <select name="domain_id" class="@error('domain_id') is-invalid @enderror form-control" required>
                           <option disabled selected value> -- Select Domain --</option>
                           @foreach($domains as $domain)
                           <option value="{{$domain->id}}" {{$feedContent->domain_id==$domain->id?'selected':''}}>{{$domain->name}}</option>
                           @endforeach
                        </select>
                     </div>
                     <div class="form-group">
                        <input type="hidden" name="feed_id" value="{{$feedContent->feed_id}}" />
                        <input type="text" value="{{!empty($feedContent->feedtype)?$feedContent->feedtype->title:'N/A'}}" class="form-control" disabled />
                     </div>

                     <div class="form-group">
                        <label for="tags"># Tags</label>
                        <input type="text" class="@error('from') is-invalid @enderror form-control" value="{{$feedContent->tags}}" name="tags" placeholder="# Tags example(heritage,exam,education)" maxlength="100">
                     </div>

                     <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="@error('title') is-invalid @enderror form-control" value="{{$feedContent->title}}" maxlength="50" name="title" placeholder="Title">
                     </div>



                     <div class="form-group">
                        <label for="name" id="duration">Description</label>
                        <textarea class="@error('name') is-invalid @enderror form-control" name="description" placeholder="Description" id="description">
                        {{$feedContent->description}}</textarea>
                     </div>




                     <div id="single_pos" style="display:{{$feedContent->feed_id=='1'?'show':'none'}}">
                        <div class="form-group">
                           <label for="title">External Link</label>

                           <input type="text" class="@error('external_link') is-invalid @enderror form-control" maxlength="50" name="external_link" placeholder="https://www.google.com/">
                        </div>
                        <div class="form-group">
                           <select name="media_type" class="type_add_edit form-control" class="@error('type') is-invalid @enderror form-control type">
                              <option>Type</option>
                              <!-- <option value=""> -- Select Media Type -- </option> -->
                              <option value="0" {{!empty($feedContent->feed_medium) && $feedContent->feed_medium->video_link==""?'selected':''}}>Image</option>
                              <option value="1" {{!empty($feedContent->feed_medium) && $feedContent->feed_medium->video_link!=""?'selected':''}}>Video</option>
                           </select>
                        </div>

                        <div class="form-group myimage1" style="display:{{!empty($feedContent->feed_medium) && $feedContent->feed_medium->video_link==null?'show':'none'}}">
                           <div class="field" align="left">
                              <label class="img-label">Uploaded images</label>
                              <input type="file" class="edit_files" name="media_name[]" accept="image/*" multiple />
                              <div class="old_photos" key="{{$key}}">

                                 @if(!empty($feedContent->feed_medium->feed_attachments))
                                 @foreach($feedContent->feed_medium->feed_attachments as $image)
                                 <span class="pip"><input type="hidden" value="{{$image->media_name}}" name="old_images[]" /><input type="button" value="x" class="remove_edit"><img class="imageThumb" style="width:120px;" src="{{asset('storage/'.$image->media_name)}}"></span>
                                 @endforeach
                                 @endif
                              </div>
                           </div>
                        </div>

                        <div class="form-group row myvideo1" style="display:{{!empty($feedContent->feed_medium) && $feedContent->feed_medium->video_link!=null?'show':'none'}}">
                           <div class="field col">
                              <label class="img-label label0">Upload Videos</label>
                              <input type="file" class="videos_1_edit" name="media_video" accept="video/*" multiple />
                           </div>
                           <div class="col-md-6">
                              <label>Video Link</label>
                              <input type="text" class="@error('video_link') is-invalid @enderror form-control" maxlength="50" name="video_link" placeholder="https://www.youtube.com/">
                           </div>

                           <div id="placeholder_image">
                              <div class="form-group row">
                                 <div class="field col">
                                    <label>Placeholder Image for Video</label>
                                    <input type="file" class="palceholder_image_1" name="placeholder_image" accept="image/*" />
                                 </div>
                              </div>
                           </div>

                        </div>
                     </div>

                     <div id="collections" style="display:{{$feedContent->feed_id=='3'?'show':'none'}}">
                     </div>
                     <div id="modules" style="display:{{$feedContent->feed_id=='2'?'show':'none'}}">
                        @php $i="0"; @endphp
                        @foreach($feedContent->feed_media as $feed)
                        @php $i++; @endphp

                        <div class="form-group">
                           <label for="title">Title</label>
                           <input type="text" class="@error('title') is-invalid @enderror form-control" value="{{$feed->title}}" maxlength="50" name="more_title[]" placeholder="Title">
                        </div>



                        <div class="form-group">
                           <label for="name" id="duration">Description</label>
                           <textarea class="@error('name') is-invalid @enderror form-control" name="more_description[]" placeholder="Description">
                           {{$feed->description}}</textarea>
                        </div>

                        <div class="form-group">
                           <label for="title">External Link</label>

                           <input value="{{$feed->external_link}}" type="text" class="@error('external_link') is-invalid @enderror form-control" maxlength="50" name="more_external_link[]" placeholder="https://www.google.com/">
                        </div>
                        <div class="form-group">
                           <select name="more_type[]" id="type" mytext="label0" myimage="myimage0" myvideo="myvideo0" class="@error('type') is-invalid @enderror form-control type">
                              <!-- <option>Type</option> -->

                              <!-- <option value=""> -- Select Media Type -- </option> -->
                              <option value="0" @if($feed->feed_attachments_single && $feed->feed_attachments_single->media_type=='0') selected @endif>Image</option>
                              <option value="1" @if($feed->feed_attachments_single && $feed->feed_attachments_single->media_type=='1') selected @endif>Video</option>
                           </select>
                        </div>
                        <div class="form-group" id="myimage0">
                           <div class="field" align="left">
                              <label class="img-label">Upload images</label>
                              <input type="file" id="files" name="more_media_image_{{$key}}[]" accept="image/*" multiple />
                           </div>
                        </div>

                        <div class="form-group row " id="myvideo0" style="display:none">
                           <div class="field col">
                              <label class="img-label label0">Upload Videos</label>
                              <input type="file" id="videos" name="more_media_video_{{$key}}[]" accept="video/*" multiple />
                           </div>
                           <div class="col-md-6">
                              <label>Video Link</label>
                              <input type="text" class="@error('video_link') is-invalid @enderror form-control" maxlength="50" name="more_video_link[]" placeholder="https://www.youtube.com/">
                           </div>

                           <div id="placeholder_image">
                              <div class="form-group row">
                                 <div class="field col">
                                    <label>Placeholder Image for Video</label>
                                    <input type="file" id="palceholder_image" name="more_placeholder_image_{{$key}}[]" accept="image/*" />
                                 </div>
                              </div>
                           </div>

                        </div>

                        <!-- ===== Placholder Image for Video ================ -->
                        @endforeach


                        <div id="append_edit_{{$key}}"></div>
                        <button type="button" id="add_more_post_edit" class="btn btn-sm btn-success float-right" onclick="add_post({{count($feedContent->feed_media)}})">Add more post</button>
                     </div>
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
         var add_more_btn_click_edit = 1;
         var cart_edit = 1;

         function showType(val) {

            if (val == '0') {

               $('#myimage1').css('display', 'block');
               $("#files_1").attr('required', true);
               $('#myvideo1').css('display', 'none');
               $('#videos_1').attr('required', false);

            } else {
               $('#myimage1').css('display', 'none');
               $('#myvideo1').css('display', 'block');
               $("#videos_1").attr("required", true);
               $('#myimage1').css('required', false);
            }
         }

         function showModules(val) {

            if (val == '1') {

               $("#single_post").css('display', 'block'); // Unchecks it
               $("#modules").css('display', 'none');
               // $("#video").removeAttr("required");
               // select value 1 than hide button add more post and empty append div
               //$('#add_more_post').hide();
               $('#append').empty();
               $("#collection").css('display', 'none');

               // $('#placeholder_image').hide();
               // $("#placeholder_image").removeAttr("required");
               // $("#videos").removeAttr("required");
               // $("#files").attr("required","required");
               $("#module_title_description").hide();
               $('#module_title_description').empty();

               //$("#files").attr("required");

            } else if (val == '2') {

               // 
               $('#add_more_post').show();
               $('#placeholder_image').show();
               $("#single_post").css('display', 'none'); // Unchecks it
               $("#modules").css('display', 'block');
               $("#collection").css('display', 'none');

               // $("#videos").attr("required");
               // $("#placeholder_image").attr("required");
               // $("#files").removeAttr("required");
            } else {

               $('#add_more_post_collection').show();
               $('#placeholder_image_collection').show();
               $("#single_post").css('display', 'none'); // Unchecks it
               $("#modules").css('display', 'none');
               $("#collection").css('display', 'block');
               // $("#videos_collection").attr("required");
               // $("#placeholder_image").attr("required");
               // $("#files_collection").removeAttr("required");
            }
         }

         function add_post(val) {
            var i = $('#edit_key_' + val).val()
            if (add_more_btn_click_edit < 4) {
               i = parseInt(i) + parseInt(add_more_btn_click_edit);
               var x = document.getElementById("signupForm_edit");
               var post = '<div class="form-group">\
                  <label for="title">Card  ' + parseInt(cart_edit + 1) + ' Title </label>\
                  <input type="text" class="@error("title") is-invalid @enderror form-control" maxlength="50" name="more_title_' + i + '[]" placeholder="Title" required>\
               </div>\
               <div class="form-group">\
                   <label for="name" id="duration">Card ' + parseInt(cart_edit + 1) + ' Description</label>\
                  <textarea class="@error("name") is-invalid @enderror form-control"   name="more_description_' + i + '[]" placeholder="Description" id="description" ></textarea>\
               </div>\
               <div class="form-group">\
                  <label for="title">Card ' + parseInt(cart_edit + 1) + ' External Link</label>\
                  <input type="text" class="@error("external_link") is-invalid @enderror form-control" maxlength="50" name="more_external_link' + i + '[]" placeholder="https://www.google.com/" >\
               </div>\
               <div class="form-group">\
                  <select name="more_type_' + i + '[]" id="type" class="@error("type") is-invalid @enderror form-control type" mytext="label' + cart_edit + '" myimage="myimage' + cart_edit + '" myvideo="myvideo' + cart_edit + '" required >\
                     \
                     <option value="0">Image</option>\
                     <option value="1">Video</option>\
                     </select>\
               </div>\
               <div class="form-group" id="myimage' + cart_edit + '">\
               <div class="field" align="left">\
               <label class="img-label">Upload images</label>\
               <input type="file" id="files" name="more_media_image_' + i + '[]" accept="image/*" multiple>\
                           </div>\
                  </div>\
                  <div id="myvideo' + cart_edit + '" style="display:none">\
                  <div class="form-group row">\
                        <div class="field col" >\
                            <label class="label' + cart_edit + '">Upload your Videos</label>\
                           <input type="file"  id="videos" name="more_media_video_' + i + '[]" accept="video/*"  />\
                        </div>\
                        <div class="col" >\
                             <label>Video Link</label>\
                        <input type="text" class="@error("video_link") is-invalid @enderror form-control" maxlength="50" name="more_video_link_' + i + '[]" placeholder="https://www.youtube.com/" >\
                        </div>\
                  </div>\
                  <div id="placeholder_image">\
                  <div class="form-group row">\
                        <div class="field col" >\
                           <label>Placeholder Image your Videos</label>\
                           <input type="file" id="palceholder_image" name="more_placeholder_image_' + i + '[]" accept="image/*" />\
                        </div>\
                  </div>\
                  </div>\
                  </div>';


               $("#append_edit_" + val).append(post);
               cart_edit++;
               add_more_btn_click_edit++;
               // x.insertBefore(new_field, x.childNodes[pos]);
            } else {
               alert('sorry you can not add more post more then 4 posts')
               $('#add_more_post_edit').hide();
            }

         }
         $(document).ready(function() {
            var add_more_btn_click = 1;
            var add_more_btn_click_collection = 1;

            var cart = 1;

            $('#table').DataTable();
            // $('#add_more_post').hide();
            $("#videos").removeAttr("required");
            var x = 1;

            // add new card button 
            // $("#add_new_card_button").on('click',function(){

            //    $("#add-model").modal('show');
            // });

            // add existing card button
            $("#add_existing_card_button").on('click', function() {
               window.location.href = "/admin/feed-collection";
            });

            $("#videos").on('change', function() {
               var $fileUpload = $("#videos");
               if (parseInt($fileUpload.get(0).files.length) > 1) {
                  alert("You are only allowed to upload a maximum of 3 files");
                  $("videos").val("");
               }
            });
            $(document).on('change', '.status', function() {


               if (confirm("Are you sure want to change the status ?")) {
                  var feedcontent_id = $(this).attr('feedcontent_id');
                  window.location.href = "/admin/feed-content/" + feedcontent_id;
               } else {
                  if ($(this).prop('checked') == true) {
                     $(this).prop('checked', false); // Unchecks it
                  } else {
                     $(this).prop('checked', true);

                  }
               }
            });



            $(document).on('change', '.edit_files', function(e) {

               var files = e.target.files,
                  filesLength = files.length;
               for (var i = 0; i < filesLength; i++) {
                  var f = files[i]
                  var fileReader = new FileReader();
                  fileReader.onload = (function(e) {
                     var file = e.target;
                     $("<span class=\"pip\">" +
                        "<input type=\"button\"  value=\"x\" class=\"remove\" /><img class=\"imageThumb\" style=\"width:120px;\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +

                        "<br/> ").insertAfter('.edit_files');
                     $(".remove").click(function() {
                        $(this).parent(".pip").remove();
                        $('#edit_files').val('');
                     });






                  });
                  fileReader.readAsDataURL(f);
               }

            });


            $(document).on('change', '.palceholder_image_1', function(e) {




               var fileReader = new FileReader();
               fileReader.onload = (function(e) {
                  var file = e.target;
                  $("<span class=\"pip\">" +
                     "<input type=\"button\"  value=\"x\" class=\"remove\" /><img class=\"imageThumb\" style=\"width:120px;\" src=\"" + file.result + "\" title=\"" + file.name + "\"/>" +

                     "<br/> ").insertAfter('.palceholder_image_1');
                  $(".remove").click(function() {
                     $(this).parent(".pip").remove();
                     $('.palceholder_image_1').val('');
                  });






               });



            });
            $(document).on('click', ".remove_edit", function() {

               var key = $('.old_photos').attr('key');

               $(this).parent(".pip").remove();
               var count = $("#old_photos span").length;
               if (count == 0) {
                  $('#files_' + key + '_edit').prop('required', 'true');
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

            $(document).on('change', '.type', function() {
               var a = $(this).attr('mytext');
               var b = $(this).attr('myimage');
               var c = $(this).attr('myvideo');
               // alert(b); alert(c);
               if ($(this).val() == 0) {

                  $("." + a).text('Image');
                  $("#" + b).show();
                  $("#" + b).attr('required', true);
                  $("#" + c).hide();
                  $("#" + c).attr('required', false);

               }
               if ($(this).val() == 1) {
                  // $(".img-label").text('Video');
                  $("." + a).text('Video');
                  $("#" + b).hide();
                  $("#" + c).show();
                  $("#" + c).attr('required', true);
                  $("#" + b).attr('required', false);

               }
            });

            $(document).on('change', '#type_collection', function() {

               // alert(b); alert(c);
               if ($(this).val() == 0) {

                  $('#myimage_collection').show();
                  $('#myvideo_collection').hide();
                  $("#files_collection").attr("required", true);
                  $("#videos_collection").attr("required", false);
               } else {
                  $('#myimage_collection').hide();
                  $('#myvideo_collection').show();
                  $("#videos_collection").attr("required", true);
                  $("#files_collection").attr("required", false);
               }
            });





            // $(document).on('change','#feed_id', function() {
            //    if($(this).val() == 1){
            //       $("#single_post").show(); // Unchecks it
            //       $("#modules").hide(); 
            //      // $("#video").removeAttr("required");
            //       // select value 1 than hide button add more post and empty append div
            //       //$('#add_more_post').hide();
            //       $('#append').empty();
            //      // $('#placeholder_image').hide();
            //       // $("#placeholder_image").removeAttr("required");
            //       // $("#videos").removeAttr("required");
            //       // $("#files").attr("required","required");
            //       $("#videos").removeAttr("required");
            //          // $("#placeholder_image").attr("required");
            //          $("#files").removeAttr("required");
            //       $("#module_title_description").hide();
            //          $('#module_title_description').empty();

            //       //$("#files").attr("required");

            //       } if($(this).val()==2)
            //       {
            //          // 
            //          $('#add_more_post').show();
            //          $('#placeholder_image').show();
            //          $("#single_post").hide(); // Unchecks it
            //          $("#modules").show(); 

            //          $("#videos").attr("required");
            //          // $("#placeholder_image").attr("required");
            //          $("#files").removeAttr("required");



            //          $("#module_title_description").show();
            //          var title_description = '<div class="form-group">\
            //              <label for="title">Card 1 Title</label>\
            //              <input type="text" class="@error("title") is-invalid @enderror form-control" maxlength="50" name="card[0][title]" placeholder="Title" >\
            //          </div>\
            //          <div class="form-group">\
            //              <label for="name" id="duration">Card 1 Description</label>\
            //              <textarea class="@error("name") is-invalid @enderror form-control"   name="card[0][description]" placeholder="Description"  id="description" >\
            //              </textarea>\
            //          </div><div class="form-group">\
            //             <label for="title">Card 1 External Link</label>\
            //             <input type="text" class="@error("external_link") is-invalid @enderror form-control" maxlength="50" name="card[0][external_link]" placeholder="https://www.google.com/" >\
            //          </div>';

            //          $("#module_title_description").append(title_description);
            //       }
            //       else{
            //         // $("#single_post").hide(); // Unchecks it
            //          $("#modules").hide(); 
            //          // $('#add_more_post').hide();
            //          // $('#placeholder_image').hide();
            //          //$('#append').empty();
            //    }

            // });



            $(document).on('click', '#add_more_post', function() {

               if (add_more_btn_click < 4) {
                  var x = document.getElementById("signupForm");
                  var post = '<div class="form-group">\
                  <label for="title">Card  ' + parseInt(cart + 1) + ' Title </label>\
                  <input type="text" class="@error("title") is-invalid @enderror form-control" maxlength="50" name="card[' + cart + '][title]" placeholder="Title" required>\
               </div>\
               <div class="form-group">\
                   <label for="name" id="duration">Card ' + parseInt(cart + 1) + ' Description</label>\
                  <textarea class="@error("name") is-invalid @enderror form-control"   name="card[' + cart + '][description]" placeholder="Description"  id="description" >\
                   </textarea>\
               </div>\
               <div class="form-group">\
                  <label for="title">Card ' + parseInt(cart + 1) + ' External Link</label>\
                  <input type="text" class="@error("external_link") is-invalid @enderror form-control" maxlength="50" name="card[' + cart + '][external_link]" placeholder="https://www.google.com/" >\
               </div>\
               <div class="form-group">\
                  <select name="type[]" id="type" class="@error("type") is-invalid @enderror form-control type" mytext="label' + cart + '" myimage="myimage' + cart + '" myvideo="myvideo' + cart + '" required >\
                     \
                     <option value="0">Image</option>\
                     <option value="1">Video</option>\
                     </select>\
               </div>\
               <div class="form-group" id="myimage' + cart + '">\
               <div class="field" align="left">\
               <label class="img-label">Upload images</label>\
               <input type="file" id="files" name="card[' + cart + '][media_video][]" accept="image/*" multiple>\
                           </div>\
                  </div>\
                  <div id="myvideo' + cart + '" style="display:none">\
                  <div class="form-group row">\
                        <div class="field col" >\
                            <label class="label' + cart + '">Upload your Videos</label>\
                           <input type="file"  id="videos" name="card[' + cart + '][media_video][]" accept="video/*"  />\
                        </div>\
                        <div class="col" >\
                             <label>Video Link</label>\
                        <input type="text" class="@error("video_link") is-invalid @enderror form-control" maxlength="50" name="card[' + cart + '][video_link]" placeholder="https://www.youtube.com/" >\
                        </div>\
                  </div>\
                  <div id="placeholder_image">\
                  <div class="form-group row">\
                        <div class="field col" >\
                           <label>Placeholder Image your Videos</label>\
                           <input type="file" id="palceholder_image" name="card[' + cart + '][placeholder_image]" accept="image/*" />\
                        </div>\
                  </div>\
                  </div>\
                  </div>';


                  $("#append").append(post);
                  cart++;
                  add_more_btn_click++;
                  // x.insertBefore(new_field, x.childNodes[pos]);
               } else {
                  $('#add_more_post').hide();
               }

            });


            $(document).on('click', '#add_more_post_collection', function() {

               if (add_more_btn_click_collection < 4) {
                  var x = document.getElementById("signupForm");
                  var post = '<div class="form-group">\
                  <label for="title">Card  ' + parseInt(cart + 1) + ' Title </label>\
                  <input type="text" class="@error("title") is-invalid @enderror form-control" maxlength="50" name="card[' + cart + '][title1]" placeholder="Title" required>\
               </div>\
               <div class="form-group">\
                   <label for="name" id="duration">Card ' + parseInt(cart + 1) + ' Description</label>\
                  <textarea class="@error("name") is-invalid @enderror form-control"   name="card[' + cart + '][description1]" placeholder="Description"  id="description" >\
                   </textarea>\
               </div>\
               <div class="form-group">\
                  <label for="title">Card ' + parseInt(cart + 1) + ' External Link</label>\
                  <input type="text" class="@error("external_link") is-invalid @enderror form-control" maxlength="50" name="card[' + cart + '][external_link1]" placeholder="https://www.google.com/" >\
               </div>\
               <div class="form-group">\
                  <select name="type[]" id="type" class="@error("type") is-invalid @enderror form-control type" mytext="label' + cart + '" myimage="myimage' + cart + '" myvideo="myvideo' + cart + '" required >\
                     \
                     <option value="0">Image</option>\
                     <option value="1">Video</option>\
                     </select>\
               </div>\
               <div class="form-group" id="myimage' + cart + '">\
               <div class="field" align="left">\
               <label class="img-label">Upload images</label>\
               <input type="file" id="files" name="card[' + cart + '][media_video1][]" accept="image/*" multiple>\
                           </div>\
                  </div>\
                  <div id="myvideo' + cart + '" style="display:none">\
                  <div class="form-group row">\
                        <div class="field col" >\
                            <label class="label' + cart + '">Upload your Videos</label>\
                           <input type="file"  id="videos" name="card[' + cart + '][media_video1][]" accept="video/*"  />\
                        </div>\
                        <div class="col" >\
                             <label>Video Link</label>\
                        <input type="text" class="@error("video_link") is-invalid @enderror form-control" maxlength="50" name="card[' + cart + '][video_link1]" placeholder="https://www.youtube.com/" >\
                        </div>\
                  </div>\
                  <div id="placeholder_image">\
                  <div class="form-group row">\
                        <div class="field col" >\
                           <label>Placeholder Image your Videos</label>\
                           <input type="file" id="palceholder_image" name="card[' + cart + '][placeholder_image1]" accept="image/*" />\
                        </div>\
                  </div>\
                  </div>\
                  </div>';


                  $("#append_collection").append(post);
                  cart++;
                  add_more_btn_click_collection++;
                  // x.insertBefore(new_field, x.childNodes[pos]);
               } else {
                  $('#add_more_post_collection').hide();
               }

            });



            $(document).on('submit', '.delete', function() {
               var c = confirm("Are you sure want to delete ?");
               return c; //you can just return c because it will be true or false
            });

            if (window.File && window.FileList && window.FileReader) {
               $("#files_1").on("change", function(e) {
                  var files = e.target.files,
                     filesLength = files.length;
                  for (var i = 0; i < filesLength; i++) {
                     var f = files[i]
                     var fileReader = new FileReader();
                     fileReader.onload = (function(theFile, count) {
                      return function(e) {
                        var file = e.target;
                        $("<span class=\"pip\">" +
                           "<input type=\"button\"  value=\"x\" class=\"remove\" /><img class=\"imageThumb\" style=\"width:120px;\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
                           "<br/>" + "Image" + " " + "-" + count + "<br/>").insertAfter("#files_1");
                        $(".remove").click(function() {
                           $(this).parent(".pip").remove();
                        });
                     };
                     })(f,i+1);
                     fileReader.readAsDataURL(f);
                  }
               });
            } else {
               alert("Your browser doesn't support to File API")
            }

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
                        $(".remove").click(function() {
                           $(this).parent(".pip").remove();
                        });

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
                        $(".remove").click(function() {
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

         $(document).on('change', "select[name='theme_id']", function() {

            var theme_id = $(this).val();
            var token = $("input[name='_token']").val();
            $.ajax({
               url: "<?php echo route('select-domain') ?>",
               method: 'POST',
               data: {
                  theme_id: theme_id,
                  _token: token
               },
               success: function(data) {
                  $("select[name='domain_id'").html('');
                  $("select[name='domain_id'").html(data.options);
               }
            });
         });
      </script>


      @endsection