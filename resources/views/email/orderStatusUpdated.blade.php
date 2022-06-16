@component('mail::message')
# {{ '#'.$order->code }}

## Xin chào {{ $order->customer_name }}
### Cảm ơn Anh/Chị đã đặt tour tại VTravel

@component('mail::panel')
@if ($order->Status == 3)
Đã được chấp nhận
@else
@if($order->Status == 4)
Đã bị hủy. Reason: {{ $order->reason_cancel }}
@endif
@endif
@endcomponent

Thank you,<br>
VTravel
@endcomponent
