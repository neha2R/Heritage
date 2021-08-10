@component('mail::message')
{{-- Greeting --}}

# Hello!

{{-- Intro Lines --}}

You had a request for email verification from Heritage.
Your email verification code is # <b><span style="font-size: 24px;">{{$otp}}</span></b>




{{-- Outro Lines --}}


{{-- Salutation --}}

Regards,
{{ config('app.name') }}


{{-- Subcopy --}}
@component('mail::subcopy')
<br/>
<tr><td style="text-align: center;"><img style="max-width:23% !important;sss" src="#" class="img img-responsive myclass logo" alt="logo"></td></tr>

@endcomponent
@endcomponent
