<option>--- Select Domain ---</option>
@if(!empty($domains))
@foreach($domains as $key => $domain)
<option value="{{ $domain->id }}">{{ $domain->name }}</option>
@endforeach
@endif