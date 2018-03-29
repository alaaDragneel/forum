@component('mail::message')
# One Last Step

We Just Need You To Confirm Your Email Address To Prove That You'r A Human. You Fucking Get it, Right ? Cool.

@component('mail::button', ['url' => url('/register/confirm?token=' . $user->confirmation_token)])
    Confirm Email
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
