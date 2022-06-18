@component('mail::message')
# Password Reset

If you're lost your password or wish to reset it, use the link below to get started.

@component('mail::button', [
  'url' => config('frontend.frontend_url') . config('frontend.response_password_reset_url') . $token,
  'color' => 'primary'
])
Reset Your Password
@endcomponent

Thank you,<br>
{{ VTravel }}
@endcomponent
