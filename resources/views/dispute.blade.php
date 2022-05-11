@extends('layouts.app') @section('content')
<!-- Header Section start here -->
<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    Disputes
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
                                                <th>Sr. No</th>
                                                <th>User Name</th>
                                                <th>Type</th>
                                                <th>Description</th>
                                                <!-- <th>Image</th> -->

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($disputes as $key=>$dispute)
                                            <tr>
                                                <th scope="row">{{$key+1}}</th>
                                                <td>{{ucwords(($dispute->user) ? $dispute->user->name : '-')}}</td>
                                                <td>@if($dispute->type == '1'){{'Quiz'}} @else {{'Tournament'}} @endif</td>
                                                <td>{{$dispute->details}}</td>
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

        @section('js')
        <script>
            $(document).ready(function() {

                $('#table').DataTable();

            });
        </script>
        @endsection()