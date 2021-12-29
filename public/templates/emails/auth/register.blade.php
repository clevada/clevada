@component('mail::message')
# Introduction

Hello, {{ $name }}.

Your account has beeen created.

@component('mail::button', ['url' => route('login')])
ACCOUNT LOGIN
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
