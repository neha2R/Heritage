@extends('layouts.app') @section('content')
<!-- Header Section start here -->
<div class="app-main__outer">
	<div class="app-main__inner">
		<div class="app-page-title">
			<div class="page-title-wrapper">
				<div class="page-title-heading"> Domain
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
							<div class="card-header display-inline mt-3">{{ __('Add Domain') }}
								<!-- <a href="{{ route('domain.create') }}"  class="float-right mb-2 mr-2 btn-transition btn btn-outline-primary">Primary
                 </a> -->

								<button type="button" class=" float-right btn mr-2 mb-2 btn-primary" data-toggle="modal" data-target=".add-sub-model">Add Sub Domain</button>

								<button type="button" class=" float-right btn mr-2 mb-2 btn-primary" data-toggle="modal" data-target=".add-model">Add Domain</button>

							</div> @if(session()->has('success'))
                           <div class="alert alert-dismissable alert-success">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button> <strong>
            {!! session()->get('success') !!}
        </strong> </div> @endif @if(session()->has('error'))
							<div class="alert alert-dismissable alert-error">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button> <strong>
            {!! session()->get('error') !!}
        </strong> </div> @endif
							<div class="card-body">
								<div class="table-responsive">
									<table id="table" class="mb-0 table table-striped">
										<thead>
											<tr>
												<th>#</th>
												<th>Name</th>
												<th>Sub Domain</th>
												<th>Status</th>
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
											</label></td>
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
		@foreach($domains as $key=>$domain)
		<!-- Add Model Start Here -->
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
												<th>#</th>
												<th>Name</th>
												<th>Status</th>
											</tr>
										</thead>
										<tbody>
                      @foreach($domain->subdomain as $subdomain)
					  <tr>
												<th scope="row">{{$key+1}}</th>
												<td>{{$subdomain->name}}</td>
												<td>{{$subdomain->status}}</td>
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
		<!-- Add Model Ends here -->
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
						<form id="signupForm" class="col-md-10 mx-auto" method="post" action="{{ route('domain.store') }}" novalidate="novalidate"> @csrf
							<div class="form-group">
								<label for="name">Domain name</label>
								<input type="text" class="@error('name') is-invalid @enderror form-control" id="name" name="name" placeholder="Domain name required"> @error('name')
								<div class="alert alert-danger">{{ $message }}</div> @enderror </div>
							<!-- <div class="form-group">
                                    <button type="submit" class="btn btn-primary" name="signup" value="Sign up">Sign up</button>
                                </div> --></div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Save changes</button>
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
						<form id="signupForm" class="col-md-10 mx-auto" method="post" action="{{ route('addsubdomain') }}" novalidate="novalidate"> @csrf

						<div class="form-group">
								<label for="domain_id">Select Domain</label>
								<select class="@error('domain_id') is-invalid @enderror form-control" id="domain_id" name="domain_id" required>
                                 <option value="">Select Domain</option>
								 @foreach($domains as $domain)
								 <option value="{{$domain->id}}">{{$domain->name}}</option>
                                 @endforeach
								</select>
								 @error('domain_id')
								<div class="alert alert-danger">{{ $message }}</div> @enderror </div>

							<div class="form-group">
								<label for="name">Sub Domain name</label>
								<input type="text" class="@error('subdomain_name') is-invalid @enderror form-control" id="subdomain_name" name="subdomain_name" placeholder="Domain name"> @error('subdomain_name')
								<div class="alert alert-danger">{{ $message }}</div> @enderror </div>
							</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Save changes</button>
						</form>
					</div>
				</div>
			</div>
		</div>
		<!-- Add Sub Domain Model Ends here -->

		@endsection
		<!-- Ends Here -->@section('js')
		<script>
		$(document).ready(function() {
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
          window.location.href = "/admin/domain/"+domainid;
         }
        });

		</script>

        @endsection()
