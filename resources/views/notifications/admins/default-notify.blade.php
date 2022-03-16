@component('mail::message')
**Hi {{ $user->first_name }},**

{!! $message !!}

@include('emails.signature')
@endcomponent
