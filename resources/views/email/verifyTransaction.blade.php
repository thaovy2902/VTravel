@component('mail::message')
# Xác nhận giao dịch

Sao chép mã bên dưới và dán vào ô xác thực

@component('mail::panel')
{{ $code }}
@endcomponent

Cảm ơn,<br>
{{ config('app.name') }}
@endcomponent