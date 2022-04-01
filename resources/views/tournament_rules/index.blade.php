  @extends('layouts.app')
  @section('content')
  <div class="app-main__outer">
      <div class="app-main__inner">
          <div class="app-page-title">
              <div class="page-title-wrapper">
                  <div class="page-title-heading">
                      Tournament Rules
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
                              <form id="signupForm" class="col-md-10 mx-auto" method="post" action="{{ route('tourrule.store') }}">
                                  <!-- novalidate="novalidate" -->
                                  @csrf
                                  <lable for="type" class="m-2">Tournament Rules </lable>
                                  <div class="row box">
                                      <div class="form-group col-md-10 ">
                                          <input type="text" class=" form-control box" value="{{$tournament->title}}" readonly>
                                      </div>
                                      <div class="form-group col-md-2"></div>
                                  </div>
                                  <input type="hidden" value="{{$tournament->id}}" name="tournament_id" />
                                  <div class="row box">
                                      <div class="form-group col-md-10 "><input type="text" maxlength="100" class=" form-control box" name="details[]" placeholder="Details here" required></div>
                                      <div class="form-group col-md-2"></div>
                                  </div>
                                  <div class="row box">
                                      <div class="form-group col-md-10 "><input type="text" maxlength="100" class=" form-control box" name="details[]" placeholder="Details here" required></div>
                                      <div class="form-group col-md-2"></div>
                                  </div>
                                  <div class="form-group more">
                                  </div>
                                  <div class="form-group row">
                                      <a href="#" class="form-group btn btn-success ml-auto" onclick="addMore()">Add more..</a>
                                  </div>
                                  <button type="submit" class="btn btn-secondary">Submit</button>
                              </form>
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
      function addMore() {
          $('.more').append('<div class="row box "><div class="form-group col-md-10 "><input type="text" maxlength="50" class=" form-control box" name="details[]" placeholder="Details Here" required></div><div class="form-group col-md-2"><a href="#" class="btn btn-danger button-remove" >Remove</a></div></div>')
      }

      $(document).on("click", ".button-remove", function() {
          $(this).closest(".box").remove();
      });
  </script>
  @endsection