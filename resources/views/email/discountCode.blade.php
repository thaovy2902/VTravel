@component('mail::message')
# Cảm ơn về sự ủng hộ của bạn

## Chúng tôi tặng bạn mã giảm giá {{ $discountCode->percent }}%

Hạn sử dụng đến {{ $discountCode->expire }}

@component('mail::panel')
{{ $discountCode->code }}
@endcomponent

Cảm ơn,<br>
{{ config('app.name') }}
@endcomponent
