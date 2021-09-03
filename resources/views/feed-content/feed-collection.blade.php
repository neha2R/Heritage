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
.form-control {
    /* width:22% !important; */
}
</style>
<link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/css/dataTables.checkboxes.css" rel="stylesheet" />

@endsection
@section('content')
<!-- Header Section start here -->
<div class="app-main__outer">
<div class="app-main__inner">
<div class="app-page-title">
   <div class="page-title-wrapper">
      <div class="page-title-heading">
          Add Collection
         <div class="page-title-subheading"> </div>
      </div>
   </div>
</div>


<!-- Collection Section start here  -->
<form action="{{route('feed-collection-store')}}"  method="POST">
    @csrf
    <input type="hidden" name="type" value="1" />
    <div class="container">
        <div class="card">
            <div class="card-body">
                
                    
                    <div class="row">
                        
                                <div class="col-2">
                                    <label for="Theme">Theme</label>
                                </div>
                                <div class="col-10">
                                    <div class="form-group">
                                        <select name="theme_id" class="@error('domain_id') is-invalid @enderror form-control form-select-lg mb-3" aria-label="Default select example" required >
                                           
                                            @foreach($themes as $theme)
                                                <option value="{{$theme->id}}">{{$theme->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="row">
                                <div class="col">
                                    Domain
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <select name="domain_id" class="@error('domain_id') is-invalid @enderror form-control" required >
                                           
                                            @foreach($domains as $domain)
                                                <option value="{{$domain->id}}">{{$domain->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="row">
                                <div class="col">
                                    Sub Domain
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <select name="sub_domain_id" class="@error('domain_id') is-invalid @enderror form-control" required >
                                        @foreach($sub_domains as $sub_domain)
                                                <option value="{{$sub_domain->id}}">{{$sub_domain->name}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col">
                            <div class="row">
                                <div class="col">
                                    Type
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <select name="feed_id" class="@error('domain_id') is-invalid @enderror form-control" required >
                                     
                                                <option value="3">Collection</option>
                                           
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="row">
                                <div class="col">
                                    Title
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <input type="text" name="title" class="form-control" placeholder="Title"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col">
                            <div class="row">
                                <div class="col">
                                    Tag
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <input type="text" name="tag" class="form-control" placeholder="Tag"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="row">
                                <div class="col">
                                    Description
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                      
                                        <textarea class="@error('name') is-invalid @enderror form-control"   name="description" placeholder="Description" maxlength="200" id="description" >
                                        </textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                        
                        
                    
                   
            </div>
        </div>
    </div>

<hr>
<!-- Collection section end here  -->
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
                              <th>Select</th>
                              <th>Title</th>
                              <th>Type</th>
                              <th>Theme</th>
                              <th>Status</th>
                              <th>Edit</th>
                              <th>Delete</th>  
                           </tr>
                        </thead>
                        <tbody>
                            @foreach($single_posts as $single_post)
                                <tr>
                                    <td><input type="checkbox" class="form-control" name="single_post[]" value="{{$single_post->id}}"/></td>
                                    <td>{{$single_post->title}}</td>
                                    <td>{{$single_post->type}}</td>
                                    <td>{{$single_post->theme->title}}</td>
                                    <td>Status</td>
                                    <td><button type="button" class="edit-btn-bg btn mr-2 mb-2 btn-primary" data-toggle="modal"><i class="fas fa-pencil-alt"></i></button></td>
                                    <td>
                                        
                                    <button type="submit" class=" btn mr-2 mb-2 btn-primary " ><i class="far fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-12">
            
           
            <hr>
<!-- <p><b>Selected rows data:</b></p> -->
</div>


            </div>
           
         </div>
        
      </div>
      <button type="submit"  class="btn btn-primary" style="float:right; margin-top:10px;">Save Collection</button>
   </div>
   
</div>

</form>
@endsection
@section('model')



@endsection
 
@section('js')

<script type="text/javascript" src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script>

<script>
   $(document).ready(function() {
      var cart=1;
   	// $('#table').DataTable();
       var table = $('#table').DataTable({

});

       $('#btn-submit').on('click', function(e){
   e.preventDefault();

   var data = table.$('input[type="checkbox"]').serializeArray();

   // Include extra data if necessary
   // data.push({'name': 'extra_param', 'value': 'extra_value'});
console.log(data);
//    $.ajax({
//       url: '/path/to/your/script.php',
//       data: data
//    }).done(function(response){
//       console.log('Response', response);
//    });
});
$("#checkAll").click(function(){
    $('input:checkbox').not(this).prop('checked', this.checked);
});
});
    </script>


      @endsection
