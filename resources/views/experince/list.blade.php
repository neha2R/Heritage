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

   .remove:hover {
      background: white;
      color: black;
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
               Experince
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
                        {{ __('Add Experience') }}
                        <!-- <a href="{{ route('domain.create') }}"  class="float-right mb-2 mr-2 btn-transition btn btn-outline-primary">Primary
                  </a> -->

                        <button type="button" class=" float-right btn mr-2 mb-2 btn-primary" data-toggle="modal" data-target=".add-model"> <i class="fas fa-plus-circle"></i> Create Experience</button>
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
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Status </th>
                                    <th>View</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 @foreach($experinces as $key=>$experince)
                                 <tr>
                                    <th scope="row">{{$key+1}}</th>
                                    <th scope="row">{{$experince->name}}</th>
                                    <th scope="row">₹ {{$experince->price}}</th>
                                    <td><label class="switch">
                                          @if($experince->status=='1')
                                          @php $status='checked'; @endphp
                                          @else
                                          @php $status=''; @endphp
                                          @endif
                                          <input {{$status}} type="checkbox" class="status" product_id="{{$experince->id}}">
                                          <span class="slider round"></span>
                                       </label>

                                    </td>
                                    <td><button type="button" class="btn mr-2 mb-2 btn-primary" data-toggle="modal" data-target="#view-model{{$key}}"><i class="fas fa-eye"></i></button>
                                    </td>
                                    <td><button type="button" class="edit-btn-bg btn mr-2 mb-2 btn-primary" data-toggle="modal" data-target="#edit-model{{$key}}"><i class="fas fa-pencil-alt"></i></button>
                                    </td>
                                    <td>
                                       <form class="delete" action="{{route('experince.destroy',$experince->id)}}" method="POST">
                                          @method('DELETE')
                                          @csrf
                                          <button type="submit" class=" btn mr-2 mb-2 btn-primary "><i class="far fa-trash-alt"></i></button>
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
      <div class="modal fade bd-example-modal-lg  add-model" id="add-model" tabindex="-1" role="dialog" aria-labelledby="add-model" style="display: none;" aria-hidden="true">
         <div class="modal-dialog modal-lg">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLongTitle">Add Experience</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
               </div>
               <div class="modal-body">
                  <form id="signupForm" enctype="multipart/form-data" class="col-md-10 mx-auto" method="post" action="{{ route('experince.store') }}">
                     <!-- novalidate="novalidate" -->
                     @csrf

                     <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="@error('name') is-invalid @enderror form-control" name="name" placeholder="Name" maxlength="30">
                     </div>

                     <div class="form-group">
                        <label for="Price">Price</label>
                        <input type="number" min="0" max="10000" step="0.01" class="@error('Price') is-invalid @enderror form-control" name="price" placeholder="Eample:100" required>
                     </div>

                     <div class="form-group">
                        <label for="description" id="description">Description</label>
                        <textarea class="@error('description') is-invalid @enderror form-control" name="description" placeholder="Description" maxlength="200" required>
                   </textarea>
                     </div>


                     <div class="form-group">
                        <label for="external-link">External Link</label>
                        <input type="text" class="@error('external_link') is-invalid @enderror form-control" maxlength="100" name="link" placeholder="https://www.google.com/" required>
                     </div>
                     <div class="form-group">
                        <div class="field" align="left" id="original">
                           <label class="img-label">Upload images</label>

                           <input type="file" id="files" name="images[]" accept="image/*" multiple />
                        </div>
                     </div>


                     <!-- ===== Placholder Image for Video ================ -->


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
      @foreach($experinces as $key=>$exp)
      <!-- Edit Model Start Here -->
      <div class="modal fade bd-example-modal-lg  edit-model" id="edit-model{{$key}}" tabindex="-1" role="dialog" aria-labelledby="add-model" style="display: none;" aria-hidden="true">
         <div class="modal-dialog modal-lg">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLongTitle">Edit Product</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
               </div>
               <div class="modal-body">
                  <form id="signupForm" enctype="multipart/form-data" class="col-md-10 mx-auto" method="post" action="{{ route('experince.update',$exp->id) }}">
                     <!-- novalidate="novalidate" -->
                     @method('PUT')
                     @csrf

                     <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="@error('name') is-invalid @enderror form-control" name="name" placeholder="Name" maxlength="30" value="{{$exp->name}}">
                     </div>

                     <div class="form-group">
                        <label for="Price">Price</label>
                        <input type="number" min="0" max="10000" step="0.01" class="@error('Price') is-invalid @enderror form-control" name="price" placeholder="Eample:100" required value="{{$exp->price}}">
                     </div>

                     <div class="form-group">
                        <label for="description" id="description">Description</label>
                        <textarea class="@error('description') is-invalid @enderror form-control" name="description" placeholder="Description" maxlength="200" required>
                        {{$exp->description}}</textarea>
                     </div>


                     <div class="form-group">
                        <label for="external-link">External Link</label>
                        <input type="text" class="@error('external_link') is-invalid @enderror form-control" maxlength="100" name="link" placeholder="https://www.google.com/" required value="{{$exp->link}}">
                     </div>
                     <div class="form-group">
                        <div class="field" align="left">
                           <label class="img-label">Upload images</label>

                           <input type="file" id="edit_files" name="images[]" accept="image/*" multiple />
                           <div id="old_photos">
                              @foreach($exp->images as $image)
                              <span class="pip"><input type="hidden" value="{{$image->image}}" name="old_images[]" /><input type="button" value="x" class="remove_edit"><img class="imageThumb" style="width:120px;" src="{{asset('storage/'.$image->image)}}"></span>
                              @endforeach
                           </div>
                        </div>
                     </div>



                     <!-- ===== Placholder Image for Video ================ -->


               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Continue</button>
                  </form>
               </div>

            </div>
         </div>
      </div>
      <!-- Edit Model Ends here -->
      @endforeach


      @foreach($experinces as $key=>$exp)
      <!-- View Model Start Here -->
      <div class="modal fade bd-example-modal-lg  view-model" id="view-model{{$key}}" tabindex="-1" role="dialog" aria-labelledby="add-model" style="display: none;" aria-hidden="true">
         <div class="modal-dialog modal-lg">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLongTitle">View Experinces</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
               </div>
               <div class="modal-body">
                  <!-- novalidate="novalidate" -->


                  <div class="form-group">
                     <label for="name">Name</label>
                     <input type="text" class="@error('name') is-invalid @enderror form-control" readonly name="name" placeholder="Name" maxlength="30" value="{{$exp->name}}">
                  </div>

                  <div class="form-group">
                     <label for="Price">Price</label>
                     <input type="number" min="0" max="10000" step="0.01" readonly class="@error('Price') is-invalid @enderror form-control" name="price" placeholder="Eample:100" readonly value="{{$exp->price}}">
                  </div>

                  <div class="form-group">
                     <label for="description" id="description">Description</label>
                     <textarea class="@error('description') is-invalid @enderror form-control" readonly name="description" placeholder="Description" maxlength="200" readonly>{{$exp->description}}</textarea>
                  </div>


                  <div class="form-group">
                     <label for="external-link">External Link</label>
                     <input type="text" readonly class="@error('external_link') is-invalid @enderror form-control" maxlength="100" name="link" placeholder="https://www.google.com/" readonly value="{{$exp->link}}">
                  </div>
                  <div class="form-group">
                     <div class="field" align="left">
                        <label class="img-label">Uploaded images</label>
                        <div class="form-group">
                           @foreach($exp->images as $image)

                           <span class="pip"><img class="imageThumb" style="width:120px;" src="{{asset('storage/'.$image->image)}}"></span>

                           @endforeach
                        </div>
                     </div>
                  </div>


                  <!-- ===== Placholder Image for Video ================ -->


               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>


               </div>

            </div>
         </div>
      </div>
      <!-- View Model Ends here -->
      @endforeach

      @endsection
      @section('js')
      <script>
         $(document).on('submit', '.delete', function() {
            var c = confirm("Are you sure want to delete ?");
            return c; //you can just return c because it will be true or false
         });

         $(document).ready(function() {
            var add_more_btn_click = 1;


            var cart = 1;
            $('#table').DataTable();
            // $('#add_more_post').hide();
            $("#videos").removeAttr("required");
            var x = 1;


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
                  var product_id = $(this).attr('product_id');
                  window.location.href = "/admin/experince/" + product_id;
               } else {
                  if ($(this).prop('checked') == true) {
                     $(this).prop('checked', false); // Unchecks it
                  } else {
                     $(this).prop('checked', true);

                  }
               }
            });






            $("#files").on("change", function(e) {
               var files = e.target.files,
                  filesLength = files.length;


               // $('#original').append(div);
               var j = 1;
               for (var i = 0; i < filesLength; i++) {

                  var f = files[i];

                  var fileReader = new FileReader();
                  j = 0;
                  var data = "";
                  fileReader.onload = (function(e) {
                     var file = e.target;
                     j = j + 1;
                     $("<span class=\"pip\">" +
                        "<input type=\"button\"  value=\"x\" class=\"remove\" /><img class=\"imageThumb\" style=\"width:120px;\" src=\"" + e.target.result + "\" title=\"\"/>" +
                        "<input type=\"hidden\" id=\"input_" + j + "\" name=\"original_images[]\" value=\"\" />" +
                        "<br/> ").insertAfter("#files");

                     $(".remove").click(function() {
                        $(this).parent(".pip").remove();

                     });


                  });


                  fileReader.readAsDataURL(f);

               }
               const fileList = e.target.files;
               var div = "";
               var b = 1;
               for (var i = 0; i < fileList.length; i++) {

                  $('#input_' + b).val("dfsdf");
                  b++;
               }


            });

            $("#edit_files").on("change", function(e) {

               var files = e.target.files,
                  filesLength = files.length;
               for (var i = 0; i < filesLength; i++) {
                  var f = files[i]
                  var fileReader = new FileReader();
                  fileReader.onload = (function(e) {
                     var file = e.target;
                     $("<span class=\"pip\">" +
                        "<input type=\"button\"  value=\"x\" class=\"remove\" /><img class=\"imageThumb\" style=\"width:120px;\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +

                        "<br/> ").insertAfter("#edit_files");
                     $(".remove").click(function() {
                        $(this).parent(".pip").remove();
                        $('#edit_files').val('');
                     });






                  });
                  fileReader.readAsDataURL(f);
               }
            });
         });

         $(document).on('click', ".remove_edit", function() {
            $(this).parent(".pip").remove();
            var count = $("#old_photos span").length;
            if (count == 0) {
               $('#edit_files').prop('required', 'true');
            }
         });
      </script>


      @endsection