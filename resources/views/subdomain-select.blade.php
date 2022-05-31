<option>--- Select Sub Domain ---</option>
@if(!empty($subdomains))
@foreach($subdomains as $key => $subdomain)
<option value="{{ $subdomain->id }}">{{ $subdomain->name }}</option>
@endforeach
@endif