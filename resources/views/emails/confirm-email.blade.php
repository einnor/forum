@component('mail::message')
# One Last Step

We just need you to confirm your e-mail address to prove that you are a human. You get it, right? Cool.

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
