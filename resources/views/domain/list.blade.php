@extends('layouts.app')
@section('content')
<!-- Header Section start here -->
<div class="app-main__outer">
               <div class="app-main__inner">
                  <div class="app-page-title">
                     <div class="page-title-wrapper">
                        <div class="page-title-heading">                          
                         <div>
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
                <div class="card-header">{{ __('Add Domain') }}
                <button class="float-right mb-2 mr-2 btn-transition btn btn-outline-primary">Primary
                                        </button>  
              </div>
                
                
                <div class="card-body">
                   <div class="table-responsive">
                      <table id="table" class="mb-0 table table-striped">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>Username</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <th scope="row">1</th>
                                                <td>Mark</td>
                                                <td>Otto</td>
                                                <td>@mdo</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">2</th>
                                                <td>Jacob</td>
                                                <td>Thornton</td>
                                                <td>@fat</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">3</th>
                                                <td>Larry</td>
                                                <td>the Bird</td>
                                                <td>@twitter</td>
                                            </tr>
                                            </tbody>
                                        </table>
                  </div>
                </div>
            </div>
        </div>
      </div>
  </div>
</div>

<!-- Ends Here -->
@endsection

@section('js')
<script>
  $(document).ready(function() {
    $('#table').DataTable();
} );
  </script>
@endsection()
