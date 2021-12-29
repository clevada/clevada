@component('mail::message')
# Test email

The body of your message.

@component('mail::button', ['url' => $app_url])
{{ config('app.name') }}
@endcomponent

Thanks,<br>
{{ config('app.name') }}

---
{!! $signature !!}
@endcomponent
