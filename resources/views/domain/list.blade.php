@extends('layouts.app') @section('content')
<!-- Header Section start here -->
<div class="app-main__outer">
<div class="app-main__inner">
<div class="app-page-title">
   <div class="page-title-wrapper">
      <div class="page-title-heading">
         Domain
         <div class="page-title-subheading"> </div>
      </div>
   </div>
</div>
<!-- End here -->
<!-- Content Section start here -->
<div class="row">
   <div class="container">
      <div class="row justify-content-center">
         <div class="col-md-12">
            <div class="card">
               <div class="card-header display-inline mt-3">
                  {{ __('Add Domain') }}
                  <!-- <a href="{{ route('domain.create') }}"  class="float-right mb-2 mr-2 btn-transition btn btn-outline-primary">Primary
                     </a> -->
                  <button type="button" class=" float-right btn mr-2 mb-2 btn-primary" data-toggle="modal" data-target=".add-sub-model"> <i class="fas fa-plus-circle"></i> Add Sub Domain</button>
                  <button type="button" class=" float-right btn mr-2 mb-2 btn-primary" data-toggle="modal" data-target=".add-model"> <i class="fas fa-plus-circle"></i> Add Domain</button>
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
                    {{ $message }}</strong></div>
                @endforeach
               <div class="card-body">
                  <div class="table-responsive">
                     <table id="table" class="mb-0 table table-striped">
                        <thead>
                           <tr>
                              <th>#</th>
                              <th>Name</th>
                              <th>Sub Domain</th>
                              <th>Status</th>
                              <th>Edit</th>
                              <th>Delete</th>
                           </tr>
                        </thead>
                        <tbody>
                           @foreach($domains as $key=>$domain)
                           <tr>
                              <th scope="row">{{$key+1}}</th>
                              <td>{{$domain->name}}</td>
                              <td><button type="button" class=" btn mr-2 mb-2 btn-primary" data-toggle="modal" data-target="#sub-model-list{{$key}}">Sub Domain</button>
                              </td>
                              <td><label class="switch">
                                 @if($domain->status=='1')
                                 @php $status='checked'; @endphp
                                 @else
                                 @php $status=''; @endphp
                                 @endif
                                 <input {{$status}}  type="checkbox" class="status" domainid="{{$domain->id}}">
                                 <span class="slider round"></span>
                                 </label>

                              </td>
                              <td><button type="button" class="edit-btn-bg btn mr-2 mb-2 btn-primary" data-toggle="modal" data-target="#edit-model{{$key}}"><i class="fas fa-pencil-alt"></i></button>
                              </td>
                              <td>
                                 <form class="delete-domain" action="{{route('domain.destroy',$domain->id)}}" method="POST">
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
@endsection @section('model')

<!-- Add Model Start Here -->
<div class="modal fade bd-example-modal-sm show nextstep" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true">
   <div class="modal-dialog modal-md">
      <div class="modal-content">

         <div class="modal-body text-center">
<h3 class="text-success"><b>Congrulations ! </b></h3>
<p>You have added a new domain, now you can</p>
<div>
<a href="#"  data-toggle="modal" data-target=".add-model">Add a new domain</a></div>
<div>Or</div>
<a href="#"  data-toggle="modal" data-target=".add-sub-model">Add a sub domain</a></div>

         </div>

      </div>
   </div>
</div>
<!-- Add Model Ends here -->


@foreach($domains as $key=>$domain)

<!-- Sub Domain List Model Start Here -->
<div class="modal fade bd-example-modal-lg show" id="sub-model-list{{$key}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true">
<div class="modal-dialog modal-lg">
   <div class="modal-content">
      <div class="modal-header">
         <h5 class="modal-title" id="exampleModalLongTitle">Sub Domain List</h5>
         <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
      </div>
      <div class="modal-body">
         <div class="table-responsive">
            <table id="table" class="mb-0 table table-striped">
               <thead>
                  <tr>
                     <th>Sr. No</th>
                     <th>Name</th>
                     <th>Status</th>
                     <th>Edit</th>
                     <th>Delete</th>
                  </tr>
               </thead>
               <tbody>
                  @foreach($domain->subdomain as $subdomain)
                  <tr>
                     <th scope="row">{{$key+1}}</th>
                     <td>{{$subdomain->name}}</td>
                     <td><label class="switch">
                                 @if($subdomain->status=='1')
                                 @php $substatus='checked'; @endphp
                                 @else
                                 @php $substatus=''; @endphp
                                 @endif
                                 <input {{$substatus}}  type="checkbox" class="substatus" subdomainid="{{$subdomain->id}}">
                                 <span class="slider round"></span>
                                 </label>
                              </td>
                              <td><button type="button" class="edit-btn-bg btn mr-2 mb-2 btn-primary" data-toggle="modal" data-target="#edit-subdomain-model{{$key}}"><i class="fas fa-pencil-alt"></i></button>
                              </td>
                              <td>
                                 <form class="delete-subdomain" action="{{route('subdomain',$subdomain->id)}}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit" class=" btn mr-2 mb-2 btn-primary " ><i class="far fa-trash-alt"></i></button>
                                 </form>
                              </td>
                  <tr>

                     @endforeach
               </tbody>
            </table>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <!-- <button type="submit" class="btn btn-primary">Save changes</button> -->
         </div>
      </div>
   </div>
</div>
</div>
<!--  Ends here -->

 <!-- Edit Sub Domain Model Start Here -->
 @foreach($domain->subdomain as $subdomain)

 <div class="modal fade bd-example-modal-lg show" id="edit-subdomain-model{{$key}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-lg">
     <div class="modal-content">
       <div class="modal-header">
         <h5 class="modal-title" id="exampleModalLongTitle">Edit Sub Domain </h5>
         <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
      </div>
      <div class="modal-body">

	  <form  class="col-md-10 mx-auto" method="post" action="{{ route('subdomain',$subdomain->id) }}" novalidate="novalidate">
	  @method('PUT')

               @csrf
               <div class="form-group">
                  <label for="name">Sub Domain name</label>
                  <input type="text" class="@error('name') is-invalid @enderror form-control" value="{{$subdomain->name}}"  name="name" placeholder="Domain name" required> @error('name')
                  <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
               </div>
               <!-- <div class="form-group">
                  <button type="submit" class="btn btn-primary" name="signup" value="Sign up">Sign up</button>
                  </div> -->
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



<!-- Edit Model Start Here -->
<div class="modal fade bd-example-modal-lg show" id="edit-model{{$key}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-lg">
     <div class="modal-content">
       <div class="modal-header">
         <h5 class="modal-title" id="exampleModalLongTitle">Edit Domain </h5>
         <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
      </div>
      <div class="modal-body">

	  <form  class="col-md-10 mx-auto" method="post" action="{{ route('domain.update',$domain->id) }}" novalidate="novalidate">
	  @method('PUT')

               @csrf
               <div class="form-group">
                  <label for="name">Domain name</label>
                  <input type="text" class="@error('name') is-invalid @enderror form-control" value="{{$domain->name}}"  name="name" placeholder="Domain name" required> @error('name')
                  <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
               </div>
               <!-- <div class="form-group">
                  <button type="submit" class="btn btn-primary" name="signup" value="Sign up">Sign up</button>
                  </div> -->
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
<!-- Add Model Start Here -->
<div class="modal fade bd-example-modal-lg show add-model" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Add Domain</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
         </div>
         <div class="modal-body">
            <form id="signupForm" class="col-md-10 mx-auto" method="post" action="{{ route('domain.store') }}" novalidate="novalidate">
               @csrf
               <div class="form-group">
                  <label for="name">Domain name</label>
                  <input type="text" class="@error('name') is-invalid @enderror form-control" name="name" placeholder="Domain name required"> @error('name')
                  <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
               </div>
               <!-- <div class="form-group">
                  <button type="submit" class="btn btn-primary" name="signup" value="Sign up">Sign up</button>
                  </div> -->
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
<!-- Sub Domain Add Model Start Here -->
<div class="modal fade bd-example-modal-lg show add-sub-model" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Add Sub Domain</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
         </div>
         <div class="modal-body">
            <form  class="col-md-10 mx-auto" method="post" action="{{ route('addsubdomain') }}" novalidate="novalidate">
               @csrf
               <div class="form-group">
                  <label for="domain_id">Select Domain</label>
                  <select class="@error('domain_id') is-invalid @enderror form-control" id="domain_id" name="domain_id" required>
                     <option value="">Select Domain</option>
                     @foreach($domains as $domain)
                     <option value="{{$domain->id}}">{{$domain->name}}</option>
                     @endforeach
                  </select>
                  @error('domain_id')
                  <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
               </div>
               <div class="form-group">
                  <label for="name">Sub Domain name</label>
                  <input type="text" class="@error('subdomain_name') is-invalid @enderror form-control " id="subdomain_name" name="subdomain_name" placeholder="Sub Domain name" required> @error('subdomain_name')
                  <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
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
<!-- Add Sub Domain Model Ends here -->
@endsection
<!-- Ends Here -->
@section('js')
<script>
   $(document).ready(function() {
      $(".form-control").attr('maxlength','30');

   	$('#table').DataTable();

   //             Swal.fire({
   //   title: 'Are you sure?',
   //   text: "You won't be able to revert this!",
   //   icon: 'warning',
   //   showCancelButton: true,
   //   confirmButtonColor: '#3085d6',
   //   cancelButtonColor: '#d33',
   //   confirmButtonText: 'Yes, delete it!'
   // }).then((result) => {
   //   if (result.isConfirmed) {
   //     Swal.fire(
   //       'Deleted!',
   //       'Your file has been deleted.',
   //       'success'
   //     )
   //   }
   // })
   });

   $('.status').on('change', function() {
   	if(confirm("Are you sure want to change the status ?")) {
   		var domainid = $(this).attr('domainid');
         $(".slider").addClass("sliderafter");
           window.location.href = "/admin/domain/"+domainid;
          }
          else{
            if($(this).prop('checked') == true){
               $(this).prop('checked', false); // Unchecks it
            } else{
               $(this).prop('checked', true);

            }
          }
         });




   $('.delete-domain').submit(function() {
     var c = confirm("Are you sure want to delete ?");
     return c; //you can just return c because it will be true or false
   });




         $(document).on('change','.substatus', function() {
   	if(confirm("Are you sure want to change the status ?")) {
   		var subdomainid = $(this).attr('subdomainid');
           window.location.href = "/admin/sub-domain-status/"+subdomainid;
          }
          else{
            if($(this).prop('checked') == true){
               $(this).prop('checked', false); // Unchecks it
            } else{
               $(this).prop('checked', true);

            }
          }
         });

         $(document).on('submit','.delete-subdomain', function() {

     var c = confirm("Are you sure want to delete ?");
     return c; //you can just return c because it will be true or false
   });

</script>
@endsection()
