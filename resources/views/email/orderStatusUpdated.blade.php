@component('mail::message')
# {{ '#'.$order->code }}

## Xin chào {{ $order->customer_name }}
### Cảm ơn Anh/Chị đã đặt tour tại {{ config('app.name') }}

@component('mail::panel')
@if ($order->status == 3)
Đã được chấp nhận
@else
@if($order->status == 4)
Đã bị hủy. Lý do: {{ $order->reason_cancel }}
@endif
@endif
@endcomponent

Cảm ơn,<br>
{{ config('app.name') }}
@endcomponent
