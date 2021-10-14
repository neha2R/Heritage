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
    width:22% !important;
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
          Tournament Questions
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
            <form action="{{route('tournament-questions-store')}}" method="Post">
            @csrf
            <input type="hidden" name="tournament_id" value="{{$tournament->id}}"/>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="table" class="mb-0 table table-striped">
                        <thead>
                           <tr>
                              <th>
                                  <input type="checkbox" class="form-control" id="checkAll" value="checkAll"> CheckAll
                              </th>
                              <th>Question</th>
                              <!-- <th>Theme</th> -->
                              <th>Domain</th>
                           </tr>
                        </thead>
                        <tbody>
                        
                            
                            
                           @foreach($questions as $question)
                           <tr>
                           <td><input type="checkbox" class="form-control" name="questions_id[]" value="{{$question->question->id}}"/></td>
                           <td>{{$question->question->question}}</td>
                           <th>{{$question->domain->name}}</th>
                           </tr>
                           @endforeach
                           
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-12">
            <hr>

<p>Press <b>Submit</b>  form data that would be submitted.</p>

<p><button type="submit" name="submit">Submit</button></p>

<!-- <p><b>Selected rows data:</b></p> -->
</div>
</form>
            </div>
         </div>
      </div>
   </div>
</div>
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
