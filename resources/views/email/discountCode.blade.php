@component('mail::message')
# Cảm ơn về sự ủng hộ của bạn

## Chúng tôi tặng bạn mã giảm giá {{ $discountCode->percent }}%

Expiry Date: {{ $discountCode->expire }}

@component('mail::panel')
{{ $discountCode->code }}
@endcomponent

Thank you,<br>
VTravel
@endcomponent
