<form method="post" enctype="multipart/form-data" action="{{Route('upload_bulk')}}">
    @csrf
  <input type="file" name="bulk"/>
  <input type="submit"/>
</form>