@extends('layouts.app')
@section('content')
<!-- Header Section start here -->
<div class="app-main__outer">
<div class="app-main__inner">
<div class="app-page-title">
   <div class="page-title-wrapper">
      <div class="page-title-heading">
         FAQs
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
               {{ __('Add FAQ') }}s
               <!-- <a href="{{ route('domain.create') }}"  class="float-right mb-2 mr-2 btn-transition btn btn-outline-primary">Primary
                  </a> -->

               <button type="button" class=" float-right btn mr-2 mb-2 btn-primary" data-toggle="modal" data-target=".add-model"> <i class="fas fa-plus-circle"></i> Create FAQs</button>
            </div>
            @if(session()->has('success'))
            <div class="alert alert-dismissable alert-success">
               <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button> <strong>
               {!! session()->get('success') !!}
               </strong>
            </div>
            @endif @if(session()->has('error'))
            <div class="alert alert-dismissable alert-danger">
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
                              <th>Sr. No</th>
                              <th>Title</th>
                              <th>Description</th>
                              <th>Status</th>
                              <th>Edit</th>
                              <th>Delete</th>
                           </tr>
                        </thead>
                        <tbody>
                        @foreach($faq as $key=>$Faq)
                           <tr>
                              <th scope="row">{{$key+1}}</th>
                              <th scope="row">{{$Faq->title}}</th>
                              <td >
                              <div class="row">
                                       <div class="col-12 text-truncate">
                                       {{$Faq->content}}
                                       </div>
                              </div>

                              </td>
                              <td><label class="switch">
                                 @if($Faq->status=='1')
                                 @php $status='checked'; @endphp
                                 @else
                                 @php $status=''; @endphp
                                 @endif
                                 <input {{$status}}  type="checkbox" class="status" contentid="{{$Faq->id}}">
                                 <span class="slider round"></span>
                                 </label>

                              </td>
                              <td><button type="button" class="edit-btn-bg btn mr-2 mb-2 btn-primary" data-toggle="modal" data-target="#edit-model{{$key}}"><i class="fas fa-pencil-alt"></i></button>
                              </td>
                              <td>
                                 <form class="delete" action="{{route('faq.destroy',$Faq->id)}}" method="POST">
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
<div class="modal fade bd-example-modal-sm show nextstep" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true">
   <div class="modal-dialog modal-md">
      <div class="modal-content">

         <div class="modal-body text-center">
<h3 class="text-success"><b>Congrulations ! </b></h3>
<p>You have added a FAQs, now you can continue</p>
<div>
<a href="#"  data-toggle="modal" data-target=".add-model">Add more FAQs</a></div>
<div>Or</div>
<a href="/admin/faq">Add New FAQs</a>

         </div>

      </div>
   </div>
</div>
<!-- Add Model Ends here -->


<!-- Add Model Start Here -->
<div class="modal fade bd-example-modal-lg show add-model" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Add FAQs</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
         </div>
         <div class="modal-body">
            <form id="signupForm" class="col-md-10 mx-auto" method="post" action="{{ route('faq.store') }}" >
            <!-- novalidate="novalidate" -->
               @csrf
               <div class="form-group">
                  <label for="title">Title</label>
                  <input type="text" maxlength="50" class="@error('title') is-invalid @enderror form-control" maxlength="50" name="title" placeholder="Title" required>
               </div>
               <div class="form-group">
                  <label for="name">Description</label>
                  <textarea maxlength="300" class="@error('content') is-invalid @enderror form-control"  name="content" placeholder="description"  required> </textarea>
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

@foreach($faq as $key=>$Faq)

<!-- Edit Model Start Here -->
<div class="modal fade bd-example-modal-lg show" id="edit-model{{$key}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-lg">
     <div class="modal-content">
       <div class="modal-header">
         <h5 class="modal-title" id="exampleModalLongTitle">Edit FAQs</h5>
         <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
      </div>
      <div class="modal-body">

	  <form  class="col-md-10 mx-auto" method="post" action="{{ route('faq.update',$Faq->id) }}" >
	  @method('PUT')

               @csrf
               <div class="form-group">
                  <label for="name">Title</label>
                  <input type="text" maxlength="50" class="@error('title') is-invalid @enderror form-control" value="{{$Faq->title}}" maxlength="50" name="title" placeholder="Title" required>
               </div>
               <div class="form-group">
                  <label for="name">Description</label>
                  <textarea maxlength="300"   class="@error('content') is-invalid @enderror form-control"  name="content" placeholder="Description" required>{{$Faq->content}}</textarea>
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
   $(document).ready(function() {

   	$('#table').DataTable();

   });


   $(document).on('change','.status', function() {
    if(confirm("Are you sure want to change the status ?")) {
        var contentid = $(this).attr('contentid');
        window.location.href = "/admin/faq/"+contentid;
       }
       else{
         if($(this).prop('checked') == true){
            $(this).prop('checked', false); // Unchecks it
         } else{
            $(this).prop('checked', true);

         }
       }
      });


    $(document).on('submit','.delete', function() {
var c = confirm("Are you sure want to delete ?");
return c; //you can just return c because it will be true or false
});
    </script>
      @endsection
