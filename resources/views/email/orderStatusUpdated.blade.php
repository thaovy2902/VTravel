@component('mail::message')
# {{ '#'.$order->code }}

## Hi {{ $order->customer_name }}
### Thank you for choosing to book with VTravel

@component('mail::panel')
@if ($order->status == 3)
Was approved
@else
@if($order->status == 4)
Was cancelled. Reason: {{ $order->reason_cancel }}
@endif
@endif
@endcomponent

Thank you,<br>
VTravel
@endcomponent
