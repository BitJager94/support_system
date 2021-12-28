@component('mail::message')
# Greetings {{$name}} Staff

Your Question Was Answered
<br>
We were glad that you were satisfied
<br>
Best Regards<br>
{{ config('app.name') }}
@endcomponent
