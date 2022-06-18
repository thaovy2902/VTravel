@component('mail::message')
# Thank you for being our customer

## GET {{ $discountCode->percent }}% OFF

Expiry Date: {{ $discountCode->expire }}

@component('mail::panel')
{{ $discountCode->code }}
@endcomponent

Thank you,<br>
VTravel
@endcomponent
