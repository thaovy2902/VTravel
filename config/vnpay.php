<?php

return [
  'vnp_TmnCode' => env('VNP_TMNCODE', 'NT7ECBHU'),
  'vnp_HashSecret' => env('VNP_HASHSECRET', 'UDXGOWBNRKDPFZYDPHOCMQCDMYQVOEDJ'),
  'vnp_Url' => 'http://sandbox.vnpayment.vn/paymentv2/vpcpay.html',
  'vnp_Returnurl' => env('VNP_RETURNURL', 'http://localhost:8000/check-out'),
  'vnp_OrderInfo' => 'Thanh toán hóa đơn du lịch - Travel Bot',
  'vnp_OrderType' => env('VNP_ORDERTYPE', 'billpayment'),
  'vnp_Locale' => env('VNP_LOCALE', 'vn')
];
