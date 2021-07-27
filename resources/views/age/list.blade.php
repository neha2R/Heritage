@extends('layouts.app')
@section('content')
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
                              <th>Age</th>
                              <th>Status</th>
                              <th>Edit</th>
                              <th>Delete</th>
                           </tr>
                        </thead>
                        <tbody>
                           @foreach($ages as $key=>$age)
                           <tr>
                              <th scope="row">{{$key+1}}</th>
                              <td>{{$age->name}}</td>
                              <td>{{$age->to}} - {{$age->from}}</td>
                              <td><label class="switch">
                                 @if($age->status=='1')
                                 @php $status='checked'; @endphp
                                 @else
                                 @php $status=''; @endphp
                                 @endif
                                 <input {{$status}}  type="checkbox" class="status" ageid="{{$age->id}}">
                                 <span class="slider round"></span>
                                 </label>

                              </td>
                              <td><button type="button" class=" btn mr-2 mb-2 btn-primary" data-toggle="modal" data-target="#edit-model{{$key}}"><i class="fas fa-pencil-alt"></i></button>
                              </td>
                              <td>
                                 <form class="delete-age" action="{{route('age.destroy',$age->id)}}" method="POST">
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
